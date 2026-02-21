<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\EkstrakurikulerSiswaService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EkstrakurikulerSiswaController extends Controller
{
    use ApiResponseTrait;

    private EkstrakurikulerSiswaService $ekstrakurikulerSiswaService;

    public function __construct(EkstrakurikulerSiswaService $ekstrakurikulerSiswaService)
    {
        $this->ekstrakurikulerSiswaService = $ekstrakurikulerSiswaService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'ekstrakurikuler_id' => $request->input('ekstrakurikuler_id'),
                'siswa_id' => $request->input('siswa_id'),
                'status' => $request->input('status'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->ekstrakurikulerSiswaService->getAllPendaftaran($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Pendaftaran retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve pendaftaran list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve pendaftaran list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $pendaftaran = $this->ekstrakurikulerSiswaService->getPendaftaranById($id);

            if (!$pendaftaran) {
                return $this->notFoundResponse('Pendaftaran not found');
            }

            return $this->successResponse($pendaftaran);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve pendaftaran', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve pendaftaran', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'ekstrakurikuler_id' => 'required|integer|exists:mst_ekstrakurikuler,id',
                'siswa_id' => 'required|integer|exists:mst_siswa,id',
                'tanggal_daftar' => 'nullable|date',
            ]);

            $pendaftaran = $this->ekstrakurikulerSiswaService->daftar($validated);

            return $this->createdResponse($pendaftaran, 'Siswa berhasil mendaftar ke ekstrakurikuler');
        } catch (\Exception $e) {
            Log::error('Failed to daftar ekstrakurikuler', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to daftar: ' . $e->getMessage(), 500);
        }
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:aktif,keluar',
            ]);

            $pendaftaran = $this->ekstrakurikulerSiswaService->updateStatus($id, $validated['status']);

            return $this->successResponse($pendaftaran, 'Status updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Pendaftaran not found');
        } catch (\Exception $e) {
            Log::error('Failed to update status', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update status: ' . $e->getMessage(), 500);
        }
    }

    public function keluar(int $id): JsonResponse
    {
        try {
            $pendaftaran = $this->ekstrakurikulerSiswaService->keluar($id);

            return $this->successResponse($pendaftaran, 'Siswa berhasil keluar dari ekstrakurikuler');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Pendaftaran not found');
        } catch (\Exception $e) {
            Log::error('Failed to keluar ekstrakurikuler', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to keluar: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->ekstrakurikulerSiswaService->deletePendaftaran($id);

            if (!$deleted) {
                return $this->notFoundResponse('Pendaftaran not found');
            }

            return $this->successResponse(null, 'Pendaftaran deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete pendaftaran', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete pendaftaran', 500);
        }
    }

    public function byEkstrakurikuler(int $ekstrakurikulerId): JsonResponse
    {
        try {
            $siswa = $this->ekstrakurikulerSiswaService->getByEkstrakurikuler($ekstrakurikulerId);

            return $this->successResponse($siswa, 'Siswa by ekstrakurikuler retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve siswa by ekstrakurikuler', ['ekstrakurikuler_id' => $ekstrakurikulerId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve siswa', 500);
        }
    }

    public function bySiswa(int $siswaId): JsonResponse
    {
        try {
            $ekstrakurikuler = $this->ekstrakurikulerSiswaService->getBySiswa($siswaId);

            return $this->successResponse($ekstrakurikuler, 'Ekstrakurikuler by siswa retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ekstrakurikuler by siswa', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ekstrakurikuler', 500);
        }
    }

    public function riwayatBySiswa(int $siswaId): JsonResponse
    {
        try {
            $riwayat = $this->ekstrakurikulerSiswaService->getRiwayatBySiswa($siswaId);

            return $this->successResponse($riwayat, 'Riwayat ekstrakurikuler retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve riwayat', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve riwayat', 500);
        }
    }

    public function checkStatus(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'siswa_id' => 'required|integer|exists:mst_siswa,id',
                'ekstrakurikuler_id' => 'required|integer|exists:mst_ekstrakurikuler,id',
            ]);

            $isTerdaftar = $this->ekstrakurikulerSiswaService->isSiswaTerdaftar(
                $validated['siswa_id'],
                $validated['ekstrakurikuler_id']
            );

            return $this->successResponse([
                'siswa_id' => $validated['siswa_id'],
                'ekstrakurikuler_id' => $validated['ekstrakurikuler_id'],
                'terdaftar' => $isTerdaftar,
            ], 'Status checked successfully');
        } catch (\Exception $e) {
            Log::error('Failed to check status', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to check status', 500);
        }
    }
}
