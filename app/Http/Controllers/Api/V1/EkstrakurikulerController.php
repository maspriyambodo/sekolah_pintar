<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\EkstrakurikulerService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EkstrakurikulerController extends Controller
{
    use ApiResponseTrait;

    private EkstrakurikulerService $ekstrakurikulerService;

    public function __construct(EkstrakurikulerService $ekstrakurikulerService)
    {
        $this->ekstrakurikulerService = $ekstrakurikulerService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'status' => $request->input('status'),
                'pembina_guru_id' => $request->input('pembina_guru_id'),
                'hari' => $request->input('hari'),
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->ekstrakurikulerService->getAllEkstrakurikuler($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Ekstrakurikuler retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ekstrakurikuler list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ekstrakurikuler list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $ekstrakurikuler = $this->ekstrakurikulerService->getEkstrakurikulerById($id);

            if (!$ekstrakurikuler) {
                return $this->notFoundResponse('Ekstrakurikuler not found');
            }

            return $this->successResponse($ekstrakurikuler);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ekstrakurikuler', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ekstrakurikuler', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'kode' => 'required|string|max:20|unique:mst_ekstrakurikuler,kode',
                'nama' => 'required|string|max:100',
                'deskripsi' => 'nullable|string',
                'pembina_guru_id' => 'nullable|integer|exists:mst_guru,id',
                'hari' => 'nullable|string|max:20',
                'jam_mulai' => 'nullable|date_format:H:i',
                'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
                'lokasi' => 'nullable|string|max:100',
                'status' => 'nullable|in:aktif,nonaktif',
            ]);

            $ekstrakurikuler = $this->ekstrakurikulerService->createEkstrakurikuler($validated);

            return $this->createdResponse($ekstrakurikuler, 'Ekstrakurikuler created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create ekstrakurikuler', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create ekstrakurikuler: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'kode' => 'sometimes|string|max:20|unique:mst_ekstrakurikuler,kode,' . $id,
                'nama' => 'sometimes|string|max:100',
                'deskripsi' => 'nullable|string',
                'pembina_guru_id' => 'nullable|integer|exists:mst_guru,id',
                'hari' => 'nullable|string|max:20',
                'jam_mulai' => 'nullable|date_format:H:i',
                'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
                'lokasi' => 'nullable|string|max:100',
                'status' => 'nullable|in:aktif,nonaktif',
            ]);

            $ekstrakurikuler = $this->ekstrakurikulerService->updateEkstrakurikuler($id, $validated);

            return $this->successResponse($ekstrakurikuler, 'Ekstrakurikuler updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Ekstrakurikuler not found');
        } catch (\Exception $e) {
            Log::error('Failed to update ekstrakurikuler', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update ekstrakurikuler: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->ekstrakurikulerService->deleteEkstrakurikuler($id);

            if (!$deleted) {
                return $this->notFoundResponse('Ekstrakurikuler not found');
            }

            return $this->successResponse(null, 'Ekstrakurikuler deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete ekstrakurikuler', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete ekstrakurikuler', 500);
        }
    }

    public function byPembina(int $pembinaGuruId): JsonResponse
    {
        try {
            $ekstrakurikuler = $this->ekstrakurikulerService->getByPembina($pembinaGuruId);

            return $this->successResponse($ekstrakurikuler, 'Ekstrakurikuler by pembina retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ekstrakurikuler by pembina', ['pembina_guru_id' => $pembinaGuruId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ekstrakurikuler', 500);
        }
    }

    public function aktif(): JsonResponse
    {
        try {
            $ekstrakurikuler = $this->ekstrakurikulerService->getAktif();

            return $this->successResponse($ekstrakurikuler, 'Active ekstrakurikuler retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve active ekstrakurikuler', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve active ekstrakurikuler', 500);
        }
    }

    public function statistik(int $id): JsonResponse
    {
        try {
            $statistik = $this->ekstrakurikulerService->getStatistik($id);

            if (empty($statistik)) {
                return $this->notFoundResponse('Ekstrakurikuler not found');
            }

            return $this->successResponse($statistik, 'Statistics retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve statistics', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve statistics', 500);
        }
    }
}
