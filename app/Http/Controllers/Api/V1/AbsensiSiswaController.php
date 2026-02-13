<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AbsensiSiswa\CreateAbsensiSiswaRequest;
use App\Http\Requests\Api\V1\AbsensiSiswa\UpdateAbsensiSiswaRequest;
use App\Http\Resources\Api\V1\AbsensiSiswaResource;
use App\Services\AbsensiSiswaService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AbsensiSiswaController extends Controller
{
    use ApiResponseTrait;

    private AbsensiSiswaService $absensiSiswaService;

    public function __construct(AbsensiSiswaService $absensiSiswaService)
    {
        $this->absensiSiswaService = $absensiSiswaService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_siswa_id' => $request->input('siswa_id'),
                'status' => $request->input('status'),
                'tanggal_from' => $request->input('tanggal_from'),
                'tanggal_to' => $request->input('tanggal_to'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->absensiSiswaService->getAllAbsensi($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Absensi siswa retrieved successfully', AbsensiSiswaResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve absensi siswa list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve absensi siswa list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $absensi = $this->absensiSiswaService->getAbsensiById($id);

            if (!$absensi) {
                return $this->notFoundResponse('Absensi not found');
            }

            return $this->successResponse(new AbsensiSiswaResource($absensi));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve absensi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve absensi', 500);
        }
    }

    public function store(CreateAbsensiSiswaRequest $request): JsonResponse
    {
        try {
            $absensi = $this->absensiSiswaService->createAbsensi($request->validated());

            return $this->createdResponse(
                new AbsensiSiswaResource($absensi),
                'Absensi created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create absensi', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create absensi: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateAbsensiSiswaRequest $request, int $id): JsonResponse
    {
        try {
            $absensi = $this->absensiSiswaService->updateAbsensi($id, $request->validated());

            return $this->successResponse(
                new AbsensiSiswaResource($absensi),
                'Absensi updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Absensi not found');
        } catch (\Exception $e) {
            Log::error('Failed to update absensi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update absensi: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->absensiSiswaService->deleteAbsensi($id);

            if (!$deleted) {
                return $this->notFoundResponse('Absensi not found');
            }

            return $this->successResponse(null, 'Absensi deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete absensi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete absensi', 500);
        }
    }

    public function bySiswa(int $siswaId): JsonResponse
    {
        try {
            $absensi = $this->absensiSiswaService->getAbsensiBySiswa($siswaId);

            return $this->successResponse(
                AbsensiSiswaResource::collection($absensi),
                'Absensi retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve absensi by siswa', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve absensi', 500);
        }
    }

    public function byDateRange(Request $request): JsonResponse
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $absensi = $this->absensiSiswaService->getAbsensiByDateRange($startDate, $endDate);

            return $this->successResponse(
                AbsensiSiswaResource::collection($absensi),
                'Absensi retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve absensi by date range', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve absensi', 500);
        }
    }

    public function summary(int $siswaId): JsonResponse
    {
        try {
            $summary = $this->absensiSiswaService->getAbsensiSummary($siswaId);

            return $this->successResponse($summary, 'Absensi summary retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve absensi summary', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve absensi summary', 500);
        }
    }
}
