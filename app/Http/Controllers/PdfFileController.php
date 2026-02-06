<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneratePdfRequest;
use App\Http\Requests\UploadPdfRequest;
use App\Services\PdfFileService;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class PdfFileController extends Controller
{
    public function __construct(
        protected PdfFileService $service
    ) {}

    public function index(Request $request)
    {
        try {
            $status = $request->query('status', '');
            if (!in_array($status, ['CREATED', 'UPLOADED', 'DELETED'])) {
                $status = '';
            }

            $limit = $request->query('limit', 10);
            $limit = min($limit, 100);

            $paginatedResult = $this->service->getPdfFiles($limit, $status);

            return response()->json([
                'success' => true,
                'data' => $paginatedResult->items(),
                'pagination' => [
                    'page' => (int) $request->query('page', 1),
                    'limit' => (int) $limit,
                    'total' => $paginatedResult->total()
                ]
            ]);
        } catch (Exception $e) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'errors' => $e->getMessage()
            ], 500));
        }
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
        try {
            $result = $this->service->deletePdf($id);

            return response()->json([
                'success' => true,
                'message' => 'PDF generated successfully',
                'data' => $result['data']
            ]);
        } catch (Exception $e) {
            $message = $e->getMessage();
            $errorStatusCode = 500;

            if (str_contains($message, 'not found')) {
                $errorStatusCode = 404;
                $message = 'PDF file not found';
            } elseif (str_contains($message, 'already deleted')) {
                $errorStatusCode = 422;
                $message = 'PDF file is already deleted';
            } else {
                $message = 'Failed to delete PDF';
            }

            $response = [
                'success' => false,
                'message' => $message
            ];

            if ($errorStatusCode == 500) {
                $response['errors'] = $e->getMessage();
            }

            throw new HttpResponseException(response()->json($response, $errorStatusCode));
        }
    }
}
