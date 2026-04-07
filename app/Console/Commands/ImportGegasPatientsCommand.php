<?php

namespace App\Console\Commands;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportGegasPatientsCommand extends Command
{
    protected $signature = 'patients:import-gegasdata
                            {--path= : Directory containing .TXT exports (default: gegasdata in project root)}
                            {--dry-run : Parse and merge only; do not write to the database}';

    protected $description = 'Import unique patients from gegasdata UTF-16 tab-separated files';

    public function handle(): int
    {
        $dir = $this->option('path') ?: base_path('gegasdata');
        if (! is_dir($dir)) {
            $this->error("Directory not found: {$dir}");

            return self::FAILURE;
        }

        $files = glob($dir.DIRECTORY_SEPARATOR.'*.TXT');
        if ($files === false || $files === []) {
            $this->error("No .TXT files in {$dir}");

            return self::FAILURE;
        }

        sort($files);

        $aggregated = [];

        foreach ($files as $path) {
            $rows = $this->readRows($path);
            foreach ($rows as $row) {
                $key = $this->dedupeKey($row);
                if ($key === null) {
                    continue;
                }

                if (! isset($aggregated[$key])) {
                    $aggregated[$key] = $row;
                } else {
                    $aggregated[$key] = $this->mergeRows($aggregated[$key], $row);
                }
            }
        }

        $this->info('Unique patients after merge: '.count($aggregated).' (from '.count($files).' files)');

        if ($this->option('dry-run')) {
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar(count($aggregated));
        $bar->start();

        $created = 0;
        $updated = 0;

        DB::transaction(function () use ($aggregated, $bar, &$created, &$updated) {
            foreach ($aggregated as $row) {
                $payload = $this->rowToPatientAttributes($row);
                $patient = Patient::query()->where('id_number', $payload['id_number'])->first();

                if ($patient === null) {
                    Patient::query()->create($payload);
                    $created++;
                } else {
                    $patient->fill($this->mergeDbPatient($patient, $payload));
                    if ($patient->isDirty()) {
                        $patient->save();
                        $updated++;
                    }
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info("Created: {$created}, updated: {$updated}");

        return self::SUCCESS;
    }

    private function readRows(string $path): array
    {
        $raw = file_get_contents($path);
        if ($raw === false) {
            return [];
        }

        if (str_starts_with($raw, "\xFF\xFE")) {
            $raw = mb_convert_encoding($raw, 'UTF-8', 'UTF-16LE');
        } elseif (str_starts_with($raw, "\xFE\xFF")) {
            $raw = mb_convert_encoding($raw, 'UTF-8', 'UTF-16BE');
        }

        $raw = str_replace(["\r\n", "\r"], "\n", $raw);
        $raw = preg_replace('/^\x{FEFF}/u', '', $raw);
        $lines = explode("\n", $raw);
        $lines = array_values(array_filter($lines, fn (string $l) => trim($l) !== ''));

        if ($lines === []) {
            return [];
        }

        $header = array_map(fn (string $c) => trim(preg_replace('/^\x{FEFF}/u', '', $c)), explode("\t", array_shift($lines)));
        $map = [];
        foreach ($header as $i => $name) {
            if ($name !== '') {
                $map[$name] = $i;
            }
        }

        $col = static function (array $map, array $cells, string ...$names): string {
            foreach ($names as $n) {
                if (isset($map[$n], $cells[$map[$n]])) {
                    return trim((string) $cells[$map[$n]]);
                }
            }

            return '';
        };

        $fixed = [
            'date' => 0,
            'diagnosis' => 4,
            'patient' => 5,
            'id' => 6,
            'dob' => 7,
            'address' => 8,
            'phone' => 9,
        ];

        $out = [];
        foreach ($lines as $line) {
            $cells = explode("\t", $line);
            if (count($cells) < 10) {
                continue;
            }

            $get = function (string $logical) use ($map, $cells, $col, $fixed): string {
                return match ($logical) {
                    'date' => $col($map, $cells, 'თარიღი') ?: ($cells[$fixed['date']] ?? ''),
                    'diagnosis' => $col($map, $cells, 'დიაგნოზი') ?: ($cells[$fixed['diagnosis']] ?? ''),
                    'patient' => $col($map, $cells, 'პაციენტი') ?: ($cells[$fixed['patient']] ?? ''),
                    'id' => $col($map, $cells, 'პირადობა') ?: ($cells[$fixed['id']] ?? ''),
                    'dob' => $col($map, $cells, 'დაბ.თარ.') ?: ($cells[$fixed['dob']] ?? ''),
                    'address' => $col($map, $cells, 'მისამართი') ?: ($cells[$fixed['address']] ?? ''),
                    'phone' => $col($map, $cells, 'მობილური') ?: ($cells[$fixed['phone']] ?? ''),
                    default => '',
                };
            };

            $patientName = $get('patient');
            if ($patientName === '') {
                continue;
            }

            $out[] = [
                'visit_date' => $get('date'),
                'diagnosis' => $get('diagnosis'),
                'patient' => $patientName,
                'personal_id' => $get('id'),
                'dob_raw' => $get('dob'),
                'address' => $get('address'),
                'phone' => $get('phone'),
            ];
        }

        return $out;
    }

    private function dedupeKey(array $row): ?string
    {
        $digits = preg_replace('/\D+/', '', $row['personal_id'] ?? '');
        if ($digits !== '' && strlen($digits) >= 9) {
            return 'id:'.$digits;
        }

        $dob = $this->parseDob($row['dob_raw'] ?? '');
        $dobKey = $dob?->format('Y-m-d') ?? '';
        $phone = preg_replace('/\D+/', '', $row['phone'] ?? '');
        $name = mb_strtolower(preg_replace('/\s+/u', ' ', trim($row['patient'] ?? '')));

        $h = hash('sha256', $name.'|'.$dobKey.'|'.$phone);
        if ($name === '' && $dobKey === '' && $phone === '') {
            return null;
        }

        return 'h:'.$h;
    }

    private function mergeRows(array $a, array $b): array
    {
        $pickLonger = static fn (string $x, string $y) => mb_strlen($y) > mb_strlen($x) ? $y : $x;

        $merged = $a;
        $merged['patient'] = $pickLonger($a['patient'], $b['patient']);
        $merged['personal_id'] = $pickLonger($a['personal_id'], $b['personal_id']);
        $merged['address'] = $pickLonger($a['address'] ?? '', $b['address'] ?? '');
        $merged['diagnosis'] = ($b['diagnosis'] ?? '') !== '' ? $b['diagnosis'] : ($a['diagnosis'] ?? '');
        $merged['phone'] = ($b['phone'] ?? '') !== '' ? $b['phone'] : ($a['phone'] ?? '');
        $merged['dob_raw'] = $this->preferDobRaw($a['dob_raw'] ?? '', $b['dob_raw'] ?? '');

        $va = $this->parseVisitDate($a['visit_date'] ?? '');
        $vb = $this->parseVisitDate($b['visit_date'] ?? '');
        if ($vb && (! $va || $vb->gt($va))) {
            $merged['visit_date'] = $b['visit_date'];
        }

        return $merged;
    }

    private function preferDobRaw(string $x, string $y): string
    {
        $bad = ['11/11/1111', '01/01/1111'];

        $xBad = in_array(trim($x), $bad, true);
        $yBad = in_array(trim($y), $bad, true);

        if ($yBad && ! $xBad) {
            return $x;
        }
        if ($xBad && ! $yBad) {
            return $y;
        }

        return mb_strlen($y) > mb_strlen($x) ? $y : $x;
    }

    private function parseVisitDate(string $raw): ?Carbon
    {
        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }

        $c = $this->parseGeorgianDate($raw);
        if ($c === null) {
            return null;
        }

        $year = $c->year;
        if ($year < 1980 || $year > now()->addYear()->year) {
            return null;
        }

        return $c;
    }

    private function parseDob(string $raw): ?Carbon
    {
        $raw = trim($raw);
        if ($raw === '' || $raw === '11/11/1111' || $raw === '01/01/1111') {
            return null;
        }

        $c = $this->parseGeorgianDate($raw);
        if ($c === null) {
            return null;
        }

        $year = $c->year;
        if ($year < 1900 || $year > now()->year) {
            return null;
        }

        return $c;
    }

    private function parseGeorgianDate(string $raw): ?Carbon
    {
        if (! preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{2,4})$/', trim($raw), $m)) {
            return null;
        }

        $d = (int) $m[1];
        $mo = (int) $m[2];
        $y = (int) $m[3];

        if ($y < 100) {
            $y += $y <= 30 ? 2000 : 1900;
        }

        try {
            return Carbon::createFromDate($y, $mo, $d)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    private function splitName(string $full): array
    {
        $full = preg_replace('/\s+/u', ' ', trim($full));
        $parts = $full === '' ? [] : preg_split('/\s+/u', $full, -1, PREG_SPLIT_NO_EMPTY);
        if ($parts === []) {
            return ['', ''];
        }

        $last = array_shift($parts);
        $first = implode(' ', $parts);

        return [$first !== '' ? $first : $last, $last];
    }

    private function syntheticIdNumber(array $row): string
    {
        $dob = $this->parseDob($row['dob_raw'] ?? '');
        $dobKey = $dob?->format('Y-m-d') ?? '';
        $phone = preg_replace('/\D+/', '', $row['phone'] ?? '');
        $name = mb_strtolower(preg_replace('/\s+/u', ' ', trim($row['patient'] ?? '')));
        $h = strtoupper(substr(hash('sha256', $name.'|'.$dobKey.'|'.$phone), 0, 14));

        return 'G'.$h;
    }

    private function rowToPatientAttributes(array $row): array
    {
        $digits = preg_replace('/\D+/', '', $row['personal_id'] ?? '');
        $idNumber = ($digits !== '' && strlen($digits) >= 9) ? $digits : $this->syntheticIdNumber($row);

        [$firstName, $lastName] = $this->splitName($row['patient'] ?? '');

        $dob = $this->parseDob($row['dob_raw'] ?? '');
        $visit = $this->parseVisitDate($row['visit_date'] ?? '');

        $age = null;
        if ($dob) {
            $years = $dob->diffInYears(now(), false);
            $age = $years >= 0 ? $years : null;
        }

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'id_number' => $idNumber,
            'date_of_birth' => $dob,
            'age' => $age,
            'gender' => 'female',
            'phone' => $this->normalizePhone($row['phone'] ?? ''),
            'address' => ($row['address'] ?? '') !== '' ? $row['address'] : null,
            'diagnosis' => ($row['diagnosis'] ?? '') !== '' ? $row['diagnosis'] : null,
            'status' => 'active',
            'last_visit_at' => $visit,
        ];
    }

    private function normalizePhone(string $phone): ?string
    {
        $p = trim($phone);
        if ($p === '' || $p === '0') {
            return null;
        }

        return $p;
    }

    private function mergeDbPatient(Patient $existing, array $incoming): array
    {
        $pick = [];

        if (mb_strlen((string) $incoming['first_name']) > mb_strlen((string) $existing->first_name)) {
            $pick['first_name'] = $incoming['first_name'];
        }
        if (mb_strlen((string) $incoming['last_name']) > mb_strlen((string) $existing->last_name)) {
            $pick['last_name'] = $incoming['last_name'];
        }
        if ($incoming['phone'] && (! $existing->phone || mb_strlen((string) $incoming['phone']) > mb_strlen((string) $existing->phone))) {
            $pick['phone'] = $incoming['phone'];
        }
        if ($incoming['address'] && mb_strlen((string) $incoming['address']) > mb_strlen((string) ($existing->address ?? ''))) {
            $pick['address'] = $incoming['address'];
        }
        if ($incoming['diagnosis'] && (! $existing->diagnosis || $incoming['diagnosis'] !== $existing->diagnosis)) {
            $pick['diagnosis'] = $incoming['diagnosis'];
        }
        if ($incoming['date_of_birth'] && ! $existing->date_of_birth) {
            $pick['date_of_birth'] = $incoming['date_of_birth'];
            $pick['age'] = $incoming['age'];
        }
        if ($incoming['last_visit_at']) {
            $ex = $existing->last_visit_at;
            if (! $ex || $incoming['last_visit_at']->gt($ex)) {
                $pick['last_visit_at'] = $incoming['last_visit_at'];
            }
        }

        return $pick;
    }
}
