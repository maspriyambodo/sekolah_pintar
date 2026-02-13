<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Siswa\CreateSiswaRequest;
use App\Http\Requests\Api\V1\Siswa\UpdateSiswaRequest;
use App\Http\Resources\Api\V1\SiswaResource;
use App\Services\SiswaService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    use ApiResponseTrait;

    private SiswaService $siswaService;

    public function __construct(SiswaService $siswaService)
    {
        $this->siswaService = $siswaService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'kelas_id' => $request->input('kelas_id'),
                'status' => $request->input('status'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->siswaService->getAllSiswa($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Siswa retrieved successfully', SiswaResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve siswa list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve siswa list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $siswa = $this->siswaService->getSiswaById($id);

            if (!$siswa) {
                return $this->notFoundResponse('Siswa not found');
            }

            return $this->successResponse(new SiswaResource($siswa));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve siswa', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve siswa', 500);
        }
    }

    public function store(CreateSiswaRequest $request): JsonResponse
    {
        try {
            $siswa = $this->siswaService->createSiswa($request->validated());

            return $this->createdResponse(
                new SiswaResource($siswa),
                'Siswa created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create siswa', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create siswa: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateSiswaRequest $request, int $id): JsonResponse
    {
        try {
            $siswa = $this->siswaService->updateSiswa($id, $request->validated());

            return $this->successResponse(
                new SiswaResource($siswa),
                'Siswa updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Siswa not found');
        } catch (\Exception $e) {
            Log::error('Failed to update siswa', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update siswa: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->siswaService->deleteSiswa($id);

            if (!$deleted) {
                return $this->notFoundResponse('Siswa not found');
            }

            return $this->successResponse(null, 'Siswa deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete siswa', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete siswa', 500);
        }
    }

    public function byKelas(int $kelasId): JsonResponse
    {
        try {
            $siswa = $this->siswaService->getSiswaByKelas($kelasId);

            return $this->successResponse(
                SiswaResource::collection($siswa),
                'Siswa retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve siswa by kelas', ['kelas_id' => $kelasId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve siswa', 500);
        }
    }

    public function absensiSummary(int $id, Request $request): JsonResponse
    {
        try {
            $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', now()->format('Y-m-d'));

            $summary = $this->siswaService->getAbsensiSummary($id, $startDate, $endDate);

            return $this->successResponse($summary, 'Absensi summary retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve absensi summary', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve absensi summary', 500);
        }
    }

    public function naikKelas(int $id, Request $request): JsonResponse
    {
        try {
            $request->validate(['kelas_id' => 'required|integer|exists:mst_kelas,id']);

            $siswa = $this->siswaService->naikKelas($id, $request->input('kelas_id'));

            return $this->successResponse(
                new SiswaResource($siswa),
                'Siswa naik kelas successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Siswa not found');
        } catch (\Exception $e) {
            Log::error('Failed to naik kelas', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to naik kelas: ' . $e->getMessage(), 500);
        }
    }

    public function lulus(int $id): JsonResponse
    {
        try {
            $siswa = $this->siswaService->lulus($id);

            return $this->successResponse(
                new SiswaResource($siswa),
                'Siswa lulus successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Siswa not found');
        } catch (\Exception $e) {
            Log::error('Failed to lulus', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to lulus: ' . $e->getMessage(), 500);
        }
    }
}
