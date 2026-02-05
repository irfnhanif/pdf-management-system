<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneratePdfRequest;
use App\Http\Requests\UploadPdfRequest;
use App\Services\PdfFileService;
use Exception;
use Illuminate\Http\Request;

class PdfFileController extends Controller
{
    public function __construct(
        protected PdfFileService $service
    ) {}

    public function index()
    {
        //
    }

    public function store(UploadPdfRequest $request)
    {
        try {
            $result = $this->service->uploadPdf($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'PDF generated successfully',
                'data' => $result['data']
            ], 201);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function generate(GeneratePdfRequest $request)
    {
        try {
            $result = $this->service->generatePdf($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'PDF generated successfully',
                'data' => $result['data']
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        //
    }
}
