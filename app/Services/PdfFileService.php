<?php

namespace App\Services;

use App\Repositories\PdfFileRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class PdfFileService {
    protected PdfFileRepository $repository;

    public function __construct(PdfFileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function generateReport(array $data): array
    {
        try {
            $filename = $this->generateUniqueFilename();
            $pdfData = $this->preparePdfData($data);
            $pdfContent = $this->createPdf($pdfData);
            $filepath = $this->savePdfToStorage($filename, $pdfContent);
            $pdfReport = $this->saveToDatabase([
                'filename' => $filename,
                'filepath' => $filepath,
                'title' => $data['title'],
                'status' => 'CREATED'
            ]);

            return [
                'success' => true,
                'data' => $pdfReport
            ];
        } catch (Exception $e) {
            if (isset($filename)) {
                $this->cleanupFile($filename);
            }

            throw new Exception('Failed to generate PDF: ' . $e->getMessage());
        }
    }

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
            'generated_at' => Carbon::now()->format('d F Y H:i:s'),
            'generated_date' => Carbon::now()->format('d F Y'),
        ];
    }

    protected function createPdf(array $data): string
    {
        $pdf = Pdf::loadView('pdf.report-template', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->output();
    }

    protected function savePdfToStorage(string $filename, string $content): string
    {
        $filepath = 'pdf/' . $filename;
        Storage::disk('public')->put($filepath, $content);

        return '/storage/' . $filepath;
    }

    protected function saveToDatabase(array $data): object
    {
        $pdfReport = $this->repository->create($data);

        return (object) [
            'id' => $pdfReport->id,
            'filename' => $pdfReport->filename,
            'filepath' => $pdfReport->filepath,
            'status' => $pdfReport->status,
            'created_at' => $pdfReport->created_at->toIso8601String()
        ];
    }

    protected function cleanupFile(string $filename): void
    {
        $filepath = 'pdf/' . $filename;

        if (Storage::disk('public')->exists($filepath)) {
            Storage::disk('public')->delete($filepath);
        }
    }
}
