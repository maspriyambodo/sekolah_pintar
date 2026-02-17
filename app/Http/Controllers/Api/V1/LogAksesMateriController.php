<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\LogAksesMateriResource;
use App\Services\LogAksesMateriService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogAksesMateriController extends Controller
{
    use ApiResponseTrait;

    private LogAksesMateriService $logAksesMateriService;

    public function __construct(LogAksesMateriService $logAksesMateriService)
    {
        $this->logAksesMateriService = $logAksesMateriService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_materi_id' => $request->input('mst_materi_id'),
                'mst_siswa_id' => $request->input('mst_siswa_id'),
                'tanggal' => $request->input('tanggal'),
                'tanggal_awal' => $request->input('tanggal_awal'),
                'tanggal_akhir' => $request->input('tanggal_akhir'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->logAksesMateriService->getAllLogAkses($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Log akses materi retrieved successfully', LogAksesMateriResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve log akses materi list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve log akses materi list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $log = $this->logAksesMateriService->getLogAksesById($id);

            if (!$log) {
                return $this->notFoundResponse('Log akses materi not found');
            }

            return $this->successResponse(new LogAksesMateriResource($log));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve log akses materi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve log akses materi', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'mst_materi_id' => 'required|integer|exists:mst_materi,id',
                'mst_siswa_id' => 'required|integer|exists:mst_siswa,id',
                'waktu_akses' => 'nullable|date',
                'durasi_detik' => 'nullable|integer|min:0',
                'perangkat' => 'nullable|string|max:255',
            ]);

            $log = $this->logAksesMateriService->createLogAkses($request->all());

            return $this->createdResponse(
                new LogAksesMateriResource($log),
                'Log akses materi created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create log akses materi', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create log akses materi: ' . $e->getMessage(), 500);
        }
    }

    public function updateDurasi(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'durasi_detik' => 'required|integer|min:0',
            ]);

            $log = $this->logAksesMateriService->updateDurasi($id, $request->input('durasi_detik'));

            return $this->successResponse(
                new LogAksesMateriResource($log),
                'Log akses materi updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Log akses materi not found');
        } catch (\Exception $e) {
            Log::error('Failed to update log akses materi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update log akses materi: ' . $e->getMessage(), 500);
        }
    }

    public function byMateri(int $materiId, Request $request): JsonResponse
    {
        try {
            $filters = [
                'tanggal' => $request->input('tanggal'),
                'tanggal_awal' => $request->input('tanggal_awal'),
                'tanggal_akhir' => $request->input('tanggal_akhir'),
            ];

            $filters = array_filter($filters);
            $log = $this->logAksesMateriService->getLogAksesByMateri($materiId, $filters);

            return $this->successResponse(
                LogAksesMateriResource::collection($log),
                'Log akses materi retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve log akses materi by materi', ['materi_id' => $materiId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve log akses materi', 500);
        }
    }

    public function bySiswa(int $siswaId, Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_materi_id' => $request->input('mst_materi_id'),
                'tanggal' => $request->input('tanggal'),
                'tanggal_awal' => $request->input('tanggal_awal'),
                'tanggal_akhir' => $request->input('tanggal_akhir'),
            ];

            $filters = array_filter($filters);
            $log = $this->logAksesMateriService->getLogAksesBySiswa($siswaId, $filters);

            return $this->successResponse(
                LogAksesMateriResource::collection($log),
                'Log akses materi retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve log akses materi by siswa', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve log akses materi', 500);
        }
    }

    public function popular(Request $request): JsonResponse
    {
        try {
            $limit = (int) $request->input('limit', 10);
            $log = $this->logAksesMateriService->getMateriPopular($limit);

            return $this->successResponse($log, 'Materi popular retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve materi popular', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve materi popular', 500);
        }
    }
}
