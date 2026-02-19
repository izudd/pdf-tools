<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser as PdfParser;

class PdfToolController extends Controller
{
    public function index()
    {
        return view('pdf-tool.index');
    }

    /**
     * Get page count - try smalot/pdfparser first, then FPDI, then Ghostscript
     */
    private function getPageCount(string $filePath): int
    {
        // Method 1: smalot/pdfparser (supports all PDF versions)
        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);
            $pages = $pdf->getPages();
            $count = count($pages);
            if ($count > 0) {
                return $count;
            }
        } catch (\Exception $e) {
            Log::info("PdfParser fallback: " . $e->getMessage());
        }

        // Method 2: FPDI
        try {
            $fpdi = new Fpdi();
            return $fpdi->setSourceFile($filePath);
        } catch (\Exception $e) {
            Log::info("FPDI fallback: " . $e->getMessage());
        }

        // Method 3: Ghostscript
        $count = $this->getPageCountGhostscript($filePath);
        if ($count > 0) {
            return $count;
        }

        throw new \Exception("Tidak bisa membaca jumlah halaman PDF.");
    }

    /**
     * Get page count using Ghostscript
     */
    private function getPageCountGhostscript(string $filePath): int
    {
        $gsPath = $this->findGhostscript();
        if (!$gsPath) {
            return 0;
        }

        $command = sprintf(
            '%s -q -dNODISPLAY -dNOSAFER -c "(%s) (r) file runpdfbegin pdfpagecount = quit" 2>&1',
            escapeshellcmd($gsPath),
            addcslashes($filePath, '()')
        );
        $output = trim(shell_exec($command) ?? '');

        return is_numeric($output) ? (int)$output : 0;
    }

    /**
     * Find Ghostscript binary
     */
    private function findGhostscript(): ?string
    {
        foreach (['gs', '/usr/bin/gs', '/usr/local/bin/gs'] as $path) {
            $check = trim(shell_exec("which " . escapeshellarg($path) . " 2>/dev/null") ?? '');
            if (!empty($check)) {
                return $check;
            }
        }
        return null;
    }

    /**
     * Extract a single page - try FPDI first, then Ghostscript
     */
    private function extractPage(string $inputPath, int $page, string $outputPath): bool
    {
        // Method 1: FPDI
        try {
            $pdf = new Fpdi();
            $pdf->setSourceFile($inputPath);
            $template = $pdf->importPage($page);
            $size = $pdf->getTemplateSize($template);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($template);
            $pdf->Output($outputPath, 'F');
            return true;
        } catch (\Exception $e) {
            Log::info("FPDI extract fallback to GS: " . $e->getMessage());
        }

        // Method 2: Ghostscript
        $gsPath = $this->findGhostscript();
        if ($gsPath) {
            $command = sprintf(
                '%s -sDEVICE=pdfwrite -dNOPAUSE -dBATCH -dSAFER -dFirstPage=%d -dLastPage=%d -sOutputFile=%s %s 2>&1',
                escapeshellcmd($gsPath),
                $page,
                $page,
                escapeshellarg($outputPath),
                escapeshellarg($inputPath)
            );

            exec($command, $output, $returnCode);

            if ($returnCode === 0 && file_exists($outputPath)) {
                return true;
            }

            Log::warning("Ghostscript extract gagal: " . implode("\n", $output));
        }

        return false;
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'required|mimes:pdf|max:20480'
        ]);

        $uploadedFiles = [];
        $errors = [];

        foreach ($request->file('files') as $file) {

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $storedName = Str::slug($originalName) . '_' . Str::random(8) . '.pdf';
            $path = $file->storeAs('pdf_uploads', $storedName);

            try {
                $pageCount = $this->getPageCount(storage_path('app/' . $path));

                $uploadedFiles[] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'name_without_ext' => $originalName,
                    'total_pages' => $pageCount
                ];
            } catch (\Exception $e) {
                Storage::delete($path);
                Log::warning("Gagal baca [{$file->getClientOriginalName()}]: " . $e->getMessage());
                $errors[] = "File \"{$file->getClientOriginalName()}\" tidak bisa diproses: {$e->getMessage()}";
            }
        }

        if (empty($uploadedFiles)) {
            return back()->withErrors($errors);
        }

        if (!empty($errors)) {
            session()->flash('warnings', $errors);
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

        // Extract page to temp file
        $tempPath = storage_path('app/pdf_uploads/preview_' . Str::random(8) . '.pdf');

        if ($this->extractPage($filePath, $page, $tempPath)) {
            $content = file_get_contents($tempPath);
            unlink($tempPath);
            return response($content)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="preview-page-' . $page . '.pdf"');
        }

        abort(422, 'PDF tidak bisa di-preview.');
    }

    public function process(Request $request)
    {
        $results = [];

        foreach ($request->input('files') as $fileData) {

            $originalName = $fileData['original_name'] ?? basename($fileData['path']);

            try {

                $filePath = storage_path('app/' . $fileData['path']);
                $pageToKeep = (int)$fileData['page'];
                $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);

                if (!file_exists($filePath)) {
                    throw new \Exception("File tidak ditemukan.");
                }

                $totalPages = $this->getPageCount($filePath);

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
                $completeName = $nameWithoutExt . '.complete.pdf';
                $completeDir = storage_path('app/pdf_complete');
                if (!is_dir($completeDir)) {
                    mkdir($completeDir, 0755, true);
                }
                $completePath = $completeDir . '/' . $completeName;

                if (!$this->extractPage($filePath, $pageToKeep, $completePath)) {
                    throw new \Exception("Gagal mengekstrak halaman. Format PDF tidak didukung.");
                }

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
                    'file' => $originalName,
                    'original' => $originalName,
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

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }
}
