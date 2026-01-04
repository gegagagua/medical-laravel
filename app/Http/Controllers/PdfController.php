<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PdfController extends Controller
{
    /**
     * Get list of PDF/DOCX files in the pdf folder
     */
    public function index()
    {
        $pdfPath = public_path('pdf');
        $files = [];

        if (File::exists($pdfPath)) {
            $allFiles = File::files($pdfPath);
            
            foreach ($allFiles as $file) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, ['pdf', 'docx', 'doc'])) {
                    $files[] = [
                        'name' => $file->getFilename(),
                        'basename' => $file->getBasename('.' . $extension),
                        'extension' => $extension,
                        'path' => '/pdf/' . $file->getFilename(),
                    ];
                }
            }
        }

        return response()->json($files);
    }

    /**
     * Download a PDF/DOCX file
     */
    public function download($filename)
    {
        $filePath = public_path('pdf/' . $filename);
        
        if (!File::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->download($filePath);
    }
}

