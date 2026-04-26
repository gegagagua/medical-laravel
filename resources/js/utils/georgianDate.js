const MONTHS_KA = [
  'იანვარი',
  'თებერვალი',
  'მარტი',
  'აპრილი',
  'მაისი',
  'ივნისი',
  'ივლისი',
  'აგვისტო',
  'სექტემბერი',
  'ოქტომბერი',
  'ნოემბერი',
  'დეკემბერი'
];

export function formatGeorgianDate(value) {
  if (value === null || value === undefined || value === '') {
    return '—';
  }
  const s = String(value).trim();
  const m = s.match(/^(\d{4})-(\d{2})-(\d{2})/);
  if (m) {
    const y = Number(m[1]);
    const mo = Number(m[2]);
    const d = Number(m[3]);
    if (mo >= 1 && mo <= 12 && d >= 1 && d <= 31) {
      return `${d} ${MONTHS_KA[mo - 1]} ${y}`;
    }
  }
  const dt = new Date(s);
  if (Number.isNaN(dt.getTime())) {
    return '—';
  }
  return `${dt.getDate()} ${MONTHS_KA[dt.getMonth()]} ${dt.getFullYear()}`;
}
