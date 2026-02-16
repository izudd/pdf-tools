<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PdfToolController extends Controller
{
    public function index()
    {
        return view('pdf-tool.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'required|mimes:pdf|max:20480'
        ]);

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $storedName = Str::slug($originalName) . '_' . Str::random(8) . '.pdf';
            $path = $file->storeAs('pdf_uploads', $storedName);

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile(storage_path('app/' . $path));

            $uploadedFiles[] = [
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'name_without_ext' => $originalName,
                'total_pages' => $pageCount
            ];
        }

        return view('pdf-tool.process', compact('uploadedFiles'));
    }

    /**
     * Preview a specific page of an uploaded PDF
     */
    public function previewPage(Request $request)
    {
        $path = $request->query('path');
        $page = (int) $request->query('page', 1);

        $filePath = storage_path('app/' . $path);

        if (!$path || !file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Extract single page to temp file for preview
        $pdf = new Fpdi();
        $totalPages = $pdf->setSourceFile($filePath);

        if ($page < 1 || $page > $totalPages) {
            abort(400, 'Halaman tidak valid');
        }

        $template = $pdf->importPage($page);
        $size = $pdf->getTemplateSize($template);

        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($template);

        // Output directly to browser
        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="preview-page-' . $page . '.pdf"');
    }

    public function process(Request $request)
    {
        $results = [];
        $downloadFiles = [];

        foreach ($request->input('files') as $fileData) {

            try {

                $filePath = storage_path('app/' . $fileData['path']);
                $pageToKeep = (int)$fileData['page'];
                $originalName = $fileData['original_name'] ?? basename($fileData['path']);
                $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);

                if (!file_exists($filePath)) {
                    throw new \Exception("File tidak ditemukan.");
                }

                $pdf = new Fpdi();
                $totalPages = $pdf->setSourceFile($filePath);

                if ($pageToKeep < 1 || $pageToKeep > $totalPages) {
                    throw new \Exception("Halaman tidak valid (1-{$totalPages}).");
                }

                // Backup sebelum proses
                $backupDir = storage_path('app/pdf_backup');
                if (!is_dir($backupDir)) {
                    mkdir($backupDir, 0755, true);
                }
                $backupName = $nameWithoutExt . '_backup_' . date('Ymd_His') . '.pdf';
                copy($filePath, $backupDir . '/' . $backupName);

                // Extract halaman yang dipilih
                $template = $pdf->importPage($pageToKeep);
                $size = $pdf->getTemplateSize($template);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($template);

                // Simpan dengan nama: namaasli.complete.pdf
                $completeName = $nameWithoutExt . '.complete.pdf';
                $completeDir = storage_path('app/pdf_complete');
                if (!is_dir($completeDir)) {
                    mkdir($completeDir, 0755, true);
                }
                $completePath = $completeDir . '/' . $completeName;
                $pdf->Output($completePath, 'F');

                $results[] = [
                    'file' => $completeName,
                    'original' => $originalName,
                    'page_extracted' => $pageToKeep,
                    'status' => 'success',
                    'download_path' => 'pdf_complete/' . $completeName
                ];

            } catch (\Exception $e) {

                Log::error("PDF Tool Error [{$originalName}]: " . $e->getMessage());

                $results[] = [
                    'file' => $originalName ?? basename($fileData['path']),
                    'original' => $originalName ?? '',
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
        }

        // Clean temporary uploads
        foreach ($request->input('files') as $fileData) {
            $filePath = storage_path('app/' . $fileData['path']);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        return view('pdf-tool.result', compact('results'));
    }

    /**
     * Download hasil file yang sudah diproses
     */
    public function download(Request $request)
    {
        $path = $request->query('path');
        $filePath = storage_path('app/' . $path);

        if (!$path || !file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->download($filePath);
    }

    /**
     * Download semua file hasil proses dalam satu ZIP
     */
    public function downloadZip(Request $request)
    {
        $paths = $request->input('paths', []);

        if (empty($paths)) {
            abort(400, 'Tidak ada file untuk di-download');
        }

        // Validasi semua file ada
        $validFiles = [];
        foreach ($paths as $path) {
            $filePath = storage_path('app/' . $path);
            if (file_exists($filePath)) {
                $validFiles[] = [
                    'full_path' => $filePath,
                    'name' => basename($filePath)
                ];
            }
        }

        if (empty($validFiles)) {
            abort(404, 'File tidak ditemukan');
        }

        // Buat ZIP
        $zipName = 'pdf-complete_' . date('Ymd_His') . '.zip';
        $zipPath = storage_path('app/pdf_complete/' . $zipName);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Gagal membuat file ZIP');
        }

        foreach ($validFiles as $file) {
            $zip->addFile($file['full_path'], $file['name']);
        }

        $zip->close();

        // Download dan hapus ZIP setelah selesai
        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }
}
