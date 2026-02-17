<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Presensi\CreatePresensiRequest;
use App\Http\Requests\Api\V1\Presensi\UpdatePresensiRequest;
use App\Http\Resources\Api\V1\PresensiResource;
use App\Services\PresensiService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PresensiController extends Controller
{
    use ApiResponseTrait;

    private PresensiService $presensiService;

    public function __construct(PresensiService $presensiService)
    {
        $this->presensiService = $presensiService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_guru_mapel_id' => $request->input('mst_guru_mapel_id'),
                'mst_siswa_id' => $request->input('mst_siswa_id'),
                'tanggal' => $request->input('tanggal'),
                'status' => $request->input('status'),
                'tanggal_awal' => $request->input('tanggal_awal'),
                'tanggal_akhir' => $request->input('tanggal_akhir'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->presensiService->getAllPresensi($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Presensi retrieved successfully', PresensiResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve presensi list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve presensi list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $presensi = $this->presensiService->getPresensiById($id);

            if (!$presensi) {
                return $this->notFoundResponse('Presensi not found');
            }

            return $this->successResponse(new PresensiResource($presensi));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve presensi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve presensi', 500);
        }
    }

    public function store(CreatePresensiRequest $request): JsonResponse
    {
        try {
            $presensi = $this->presensiService->createPresensi($request->validated());

            return $this->createdResponse(
                new PresensiResource($presensi),
                'Presensi created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create presensi', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create presensi: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdatePresensiRequest $request, int $id): JsonResponse
    {
        try {
            $presensi = $this->presensiService->updatePresensi($id, $request->validated());

            return $this->successResponse(
                new PresensiResource($presensi),
                'Presensi updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Presensi not found');
        } catch (\Exception $e) {
            Log::error('Failed to update presensi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update presensi: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->presensiService->deletePresensi($id);

            if (!$deleted) {
                return $this->notFoundResponse('Presensi not found');
            }

            return $this->successResponse(null, 'Presensi deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete presensi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete presensi', 500);
        }
    }

    public function bySiswa(int $siswaId, Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_guru_mapel_id' => $request->input('mst_guru_mapel_id'),
                'tanggal' => $request->input('tanggal'),
                'status' => $request->input('status'),
                'tanggal_awal' => $request->input('tanggal_awal'),
                'tanggal_akhir' => $request->input('tanggal_akhir'),
            ];

            $filters = array_filter($filters);
            $presensi = $this->presensiService->getPresensiBySiswa($siswaId, $filters);

            return $this->successResponse(
                PresensiResource::collection($presensi),
                'Presensi retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve presensi by siswa', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve presensi', 500);
        }
    }

    public function byGuruMapel(int $guruMapelId, Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_siswa_id' => $request->input('mst_siswa_id'),
                'tanggal' => $request->input('tanggal'),
                'status' => $request->input('status'),
                'tanggal_awal' => $request->input('tanggal_awal'),
                'tanggal_akhir' => $request->input('tanggal_akhir'),
            ];

            $filters = array_filter($filters);
            $presensi = $this->presensiService->getPresensiByGuruMapel($guruMapelId, $filters);

            return $this->successResponse(
                PresensiResource::collection($presensi),
                'Presensi retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve presensi by guru mapel', ['guru_mapel_id' => $guruMapelId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve presensi', 500);
        }
    }

    public function byDate(Request $request): JsonResponse
    {
        try {
            $tanggal = $request->input('tanggal', now()->format('Y-m-d'));

            $filters = [
                'mst_guru_mapel_id' => $request->input('mst_guru_mapel_id'),
                'mst_siswa_id' => $request->input('mst_siswa_id'),
            ];

            $filters = array_filter($filters);
            $presensi = $this->presensiService->getPresensiByDate($tanggal, $filters);

            return $this->successResponse(
                PresensiResource::collection($presensi),
                'Presensi retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve presensi by date', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve presensi', 500);
        }
    }

    public function summary(int $siswaId, Request $request): JsonResponse
    {
        try {
            $tanggalAwal = $request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d'));
            $tanggalAkhir = $request->input('tanggal_akhir', now()->endOfMonth()->format('Y-m-d'));

            $summary = $this->presensiService->getPresensiSummary($siswaId, $tanggalAwal, $tanggalAkhir);

            return $this->successResponse($summary, 'Presensi summary retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve presensi summary', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve presensi summary', 500);
        }
    }

    public function bulkStore(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'mst_guru_mapel_id' => 'required|integer|exists:mst_guru_mapel,id',
                'tanggal' => 'required|date',
                'presensi' => 'required|array',
                'presensi.*.mst_siswa_id' => 'required|integer|exists:mst_siswa,id',
                'presensi.*.status' => 'required|integer',
            ]);

            $presensi = $this->presensiService->bulkCreatePresensi($request->all());

            return $this->createdResponse(
                PresensiResource::collection($presensi),
                'Bulk presensi created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create bulk presensi', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create bulk presensi: ' . $e->getMessage(), 500);
        }
    }
}
