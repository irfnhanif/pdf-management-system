<?php

namespace App\Services;

use App\Models\PdfFile;
use App\Repositories\PdfFileRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PdfFileService
{
    protected PdfFileRepository $repository;

    public function __construct(PdfFileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function generatePdf(array $data): array
    {
        try {
            $filename = $this->generateUniqueFilename();
            $pdfData = $this->preparePdfData($data);
            $pdfContent = $this->createPdf($pdfData);
            $filepath = $this->savePdfToStorage($filename, $pdfContent);
            $pdfFile = $this->savePdfToDatabase([
                'filename' => $filename,
                'filepath' => $filepath,
                'status' => 'CREATED'
            ]);

            return [
                'data' => [
                    'id' => $pdfFile->id,
                    'filename' => $pdfFile->filename,
                    'filepath' => $pdfFile->filepath,
                    'status' => $pdfFile->status,
                    'created_at' => $pdfFile->created_at->toIso8601String()
                ]
            ];
        } catch (Exception $e) {
            if (isset($filename)) {
                $this->cleanupFile($filename);
            }

            throw new Exception('Failed to generate PDF: ' . $e->getMessage());
        }
    }

    public function uploadPdf(array $data): array
    {
        try {
            $filename = $this->generateUniqueFilename();
            $filepath = $this->savePdfToStorage($filename, $data['file']);
            $pdfFile = $this->savePdfToDatabase([
                'filename' => $filename,
                'original_name' => $data['file']->getClientOriginalName(),
                'filepath' => $filepath,
                'size' => Storage::disk('public')->size('pdf/' . $filename),
                'status' => 'UPLOADED'
            ]);

            return [
                "data" => [
                    'id' => $pdfFile->id,
                    'original_name' => $pdfFile->original_name,
                    'filename' => $pdfFile->filename,
                    'filepath' => $pdfFile->filepath,
                    'size' => $pdfFile->size,
                    'status' => $pdfFile->status,
                    'created_at' => $pdfFile->created_at->toIso8601String()
                ]
            ];
        } catch (Exception $e) {
            if (isset($filename)) {
                $this->cleanupFile($filename);
            }

            throw new Exception('Failed to upload PDF: ' . $e->getMessage());
        }
    }

    public function getPdfFiles(int $limit, string $status): LengthAwarePaginator
    {
        try {
            return $this->repository->getAll($limit, $status);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function deletePdf(string $id): array
    {
        try {
            $pdfFile = $this->repository->findById($id);

            if (!$pdfFile) {
                throw new Exception("PDF file not found");
            }

            if ($pdfFile->status === 'DELETED') {
                throw new Exception("PDF file is already deleted");
            }

            $this->repository->softDelete($id);

            $pdfFile->refresh();

            return [
                "data" => [
                    'id' => $pdfFile->id,
                    'filename' => $pdfFile->filename,
                    'status' => $pdfFile->status,
                    'deleted_at' => $pdfFile->deleted_at->toIso8601String()
                ]
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    // ======================= INTERNAL HELPER METHODS =======================
    protected function generateUniqueFilename(): string
    {
        $timestamp = Carbon::now()->format('Ymd_His');
        $uniqueId = Str::random(6);
        return "report_{$timestamp}_{$uniqueId}.pdf";
    }

    protected function preparePdfData(array $data): array
    {
        return [
            'title' => $data['title'],
            'institution_name' => $data['institution_name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'logo_url' => $data['logo_url'] ?? null,
            'content' => $data['content'],
            'generated_at' => Carbon::now()->locale('id')->translatedFormat('d F Y H:i:s'),
            'generated_date' => Carbon::now()->locale('id')->translatedFormat('d F Y'),
        ];
    }

    protected function createPdf(array $data): string
    {
        $pdf = Pdf::loadView('pdf.report-template', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isPhpEnabled', true);

        return $pdf->output();
    }

    protected function savePdfToStorage(string $filename, mixed $content): string
    {
        $filepath = 'pdf/' . $filename;

        if ($content instanceof \Illuminate\Http\UploadedFile) {
            $content->storeAs('pdf', $filename, 'public');
        } else {
            Storage::disk('public')->put($filepath, $content);
        }

        return '/storage/' . $filepath;
    }

    protected function savePdfToDatabase(array $data): PdfFile
    {
        $pdfFile = $this->repository->create($data);

        return $pdfFile;
    }

    protected function cleanupFile(string $filename): void
    {
        $filepath = 'pdf/' . $filename;

        if (Storage::disk('public')->exists($filepath)) {
            Storage::disk('public')->delete($filepath);
        }
    }
}
