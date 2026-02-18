<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\PpdbPendaftaranService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PpdbPendaftaranController extends Controller
{
    use ApiResponseTrait;

    private PpdbPendaftaranService $pendaftaranService;

    public function __construct(PpdbPendaftaranService $pendaftaranService)
    {
        $this->pendaftaranService = $pendaftaranService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_sekolah_id' => $request->input('mst_sekolah_id'),
                'ppdb_gelombang_id' => $request->input('ppdb_gelombang_id'),
                'status_pendaftaran' => $request->input('status_pendaftaran'),
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->pendaftaranService->getAllPendaftaran($filters, $perPage);

            return $this->paginatedResponse($paginator, 'PPDB Pendaftaran retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ppdb pendaftaran list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ppdb pendaftaran list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $pendaftaran = $this->pendaftaranService->getPendaftaranById($id);

            if (!$pendaftaran) {
                return $this->notFoundResponse('PPDB Pendaftaran not found');
            }

            return $this->successResponse($pendaftaran);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ppdb pendaftaran', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ppdb pendaftaran', 500);
        }
    }

    public function showByNo(string $noPendaftaran): JsonResponse
    {
        try {
            $pendaftaran = $this->pendaftaranService->getPendaftaranByNo($noPendaftaran);

            if (!$pendaftaran) {
                return $this->notFoundResponse('PPDB Pendaftaran not found');
            }

            return $this->successResponse($pendaftaran);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ppdb pendaftaran', ['no_pendaftaran' => $noPendaftaran, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ppdb pendaftaran', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'mst_sekolah_id' => ['required', 'integer'],
                'ppdb_gelombang_id' => ['required', 'integer'],
                'nama_lengkap' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:100'],
                'password' => ['nullable', 'string', 'min:6'],
                'nisn' => ['nullable', 'string', 'max:20'],
                'jenis_kelamin' => ['required', 'in:L,P'],
                'telp_hp' => ['nullable', 'string', 'max:20'],
                'asal_sekolah' => ['nullable', 'string', 'max:255'],
                'pilihan_jurusan_id' => ['nullable', 'integer'],
                'kartukeluarga' => ['required', 'file', 'max:2048'], // max 2mb
                'akte' => ['required', 'file', 'max:2048'], // max 2mb
                'rapor' => ['required', 'file', 'max:2048'], // max 2mb
                'ijazah' => ['required', 'file', 'max:2048'], // max 2mb
            ]);

            $pendaftaran = $this->pendaftaranService->createPendaftaran($request->all());

            return $this->createdResponse($pendaftaran, 'PPDB Pendaftaran created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create ppdb pendaftaran', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create ppdb pendaftaran: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'nama_lengkap' => ['sometimes', 'string', 'max:255'],
                'email' => ['sometimes', 'email', 'max:100'],
                'password' => ['nullable', 'string', 'min:6'],
                'nisn' => ['nullable', 'string', 'max:20'],
                'jenis_kelamin' => ['sometimes', 'in:L,P'],
                'telp_hp' => ['nullable', 'string', 'max:20'],
                'asal_sekolah' => ['nullable', 'string', 'max:255'],
                'status_pendaftaran' => ['sometimes', 'in:draft,terverifikasi,seleksi,diterima,cadangan,ditolak'],
                'pilihan_jurusan_id' => ['nullable', 'integer'],
            ]);

            $pendaftaran = $this->pendaftaranService->updatePendaftaran($id, $request->all());

            return $this->successResponse($pendaftaran, 'PPDB Pendaftaran updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('PPDB Pendaftaran not found');
        } catch (\Exception $e) {
            Log::error('Failed to update ppdb pendaftaran', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update ppdb pendaftaran: ' . $e->getMessage(), 500);
        }
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => ['required', 'in:draft,terverifikasi,seleksi,diterima,cadangan,ditolak'],
            ]);

            $pendaftaran = $this->pendaftaranService->updateStatus($id, $request->input('status'));

            return $this->successResponse($pendaftaran, 'PPDB Pendaftaran status updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('PPDB Pendaftaran not found');
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (\Exception $e) {
            Log::error('Failed to update ppdb pendaftaran status', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update ppdb pendaftaran status', 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->pendaftaranService->deletePendaftaran($id);

            if (!$deleted) {
                return $this->notFoundResponse('PPDB Pendaftaran not found');
            }

            return $this->successResponse(null, 'PPDB Pendaftaran deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete ppdb pendaftaran', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete ppdb pendaftaran', 500);
        }
    }

    public function verify(int $id): JsonResponse
    {
        try {
            $pendaftaran = $this->pendaftaranService->verifyPendaftaran($id);

            return $this->successResponse($pendaftaran, 'PPDB Pendaftaran verified successfully');
        } catch (\Exception $e) {
            Log::error('Failed to verify ppdb pendaftaran', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to verify ppdb pendaftaran', 500);
        }
    }

    public function accept(int $id): JsonResponse
    {
        try {
            $pendaftaran = $this->pendaftaranService->acceptPendaftaran($id);

            return $this->successResponse($pendaftaran, 'PPDB Pendaftaran accepted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to accept ppdb pendaftaran', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to accept ppdb pendaftaran', 500);
        }
    }

    public function reject(int $id): JsonResponse
    {
        try {
            $pendaftaran = $this->pendaftaranService->rejectPendaftaran($id);

            return $this->successResponse($pendaftaran, 'PPDB Pendaftaran rejected successfully');
        } catch (\Exception $e) {
            Log::error('Failed to reject ppdb pendaftaran', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to reject ppdb pendaftaran', 500);
        }
    }

    public function statistics(Request $request, int $sekolahId): JsonResponse
    {
        try {
            $gelombangId = $request->input('ppdb_gelombang_id');
            $statistics = $this->pendaftaranService->getStatistics($sekolahId, $gelombangId);

            return $this->successResponse($statistics, 'Statistics retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve statistics', ['sekolah_id' => $sekolahId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve statistics', 500);
        }
    }
}
