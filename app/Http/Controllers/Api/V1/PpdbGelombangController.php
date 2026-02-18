<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\PpdbGelombangService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PpdbGelombangController extends Controller
{
    use ApiResponseTrait;

    private PpdbGelombangService $gelombangService;

    public function __construct(PpdbGelombangService $gelombangService)
    {
        $this->gelombangService = $gelombangService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_sekolah_id' => $request->input('mst_sekolah_id'),
                'tahun_ajaran' => $request->input('tahun_ajaran'),
                'is_active' => $request->input('is_active'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->gelombangService->getAllGelombang($filters, $perPage);

            return $this->paginatedResponse($paginator, 'PPDB Gelombang retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ppdb gelombang list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ppdb gelombang list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $gelombang = $this->gelombangService->getGelombangById($id);

            if (!$gelombang) {
                return $this->notFoundResponse('PPDB Gelombang not found');
            }

            return $this->successResponse($gelombang);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ppdb gelombang', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ppdb gelombang', 500);
        }
    }

    public function active(int $sekolahId): JsonResponse
    {
        try {
            $gelombang = $this->gelombangService->getActiveGelombang($sekolahId);

            return $this->successResponse($gelombang, 'Active PPDB Gelombang retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve active ppdb gelombang', ['sekolah_id' => $sekolahId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve active ppdb gelombang', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'mst_sekolah_id' => ['required', 'integer'],
                'nama_gelombang' => ['required', 'string', 'max:100'],
                'tahun_ajaran' => ['required', 'string', 'max:9'],
                'tgl_mulai' => ['required', 'date'],
                'tgl_selesai' => ['required', 'date', 'after:tgl_mulai'],
                'biaya_pendaftaran' => ['nullable', 'numeric', 'min:0'],
                'is_active' => ['nullable', 'boolean'],
            ]);

            $gelombang = $this->gelombangService->createGelombang($request->all());

            return $this->createdResponse($gelombang, 'PPDB Gelombang created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create ppdb gelombang', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create ppdb gelombang: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'nama_gelombang' => ['sometimes', 'string', 'max:100'],
                'tahun_ajaran' => ['sometimes', 'string', 'max:9'],
                'tgl_mulai' => ['sometimes', 'date'],
                'tgl_selesai' => ['sometimes', 'date'],
                'biaya_pendaftaran' => ['nullable', 'numeric', 'min:0'],
                'is_active' => ['nullable', 'boolean'],
            ]);

            $gelombang = $this->gelombangService->updateGelombang($id, $request->all());

            return $this->successResponse($gelombang, 'PPDB Gelombang updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('PPDB Gelombang not found');
        } catch (\Exception $e) {
            Log::error('Failed to update ppdb gelombang', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update ppdb gelombang: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->gelombangService->deleteGelombang($id);

            if (!$deleted) {
                return $this->notFoundResponse('PPDB Gelombang not found');
            }

            return $this->successResponse(null, 'PPDB Gelombang deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete ppdb gelombang', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete ppdb gelombang', 500);
        }
    }

    public function activate(int $id): JsonResponse
    {
        try {
            $gelombang = $this->gelombangService->activateGelombang($id);

            return $this->successResponse($gelombang, 'PPDB Gelombang activated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to activate ppdb gelombang', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to activate ppdb gelombang', 500);
        }
    }

    public function deactivate(int $id): JsonResponse
    {
        try {
            $gelombang = $this->gelombangService->deactivateGelombang($id);

            return $this->successResponse($gelombang, 'PPDB Gelombang deactivated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to deactivate ppdb gelombang', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to deactivate ppdb gelombang', 500);
        }
    }
}
