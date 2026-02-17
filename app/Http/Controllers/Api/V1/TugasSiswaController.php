<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TugasSiswa\CreateTugasSiswaRequest;
use App\Http\Requests\Api\V1\TugasSiswa\UpdateTugasSiswaRequest;
use App\Http\Requests\Api\V1\TugasSiswa\NilaiTugasSiswaRequest;
use App\Http\Resources\Api\V1\TugasSiswaResource;
use App\Services\TugasSiswaService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TugasSiswaController extends Controller
{
    use ApiResponseTrait;

    private TugasSiswaService $tugasSiswaService;

    public function __construct(TugasSiswaService $tugasSiswaService)
    {
        $this->tugasSiswaService = $tugasSiswaService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_tugas_id' => $request->input('mst_tugas_id'),
                'mst_siswa_id' => $request->input('mst_siswa_id'),
                'status_kumpl' => $request->input('status_kumpl'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->tugasSiswaService->getAllTugasSiswa($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Tugas siswa retrieved successfully', TugasSiswaResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tugas siswa list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve tugas siswa list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $tugasSiswa = $this->tugasSiswaService->getTugasSiswaById($id);

            if (!$tugasSiswa) {
                return $this->notFoundResponse('Tugas siswa not found');
            }

            return $this->successResponse(new TugasSiswaResource($tugasSiswa));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tugas siswa', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve tugas siswa', 500);
        }
    }

    public function store(CreateTugasSiswaRequest $request): JsonResponse
    {
        try {
            $tugasSiswa = $this->tugasSiswaService->createTugasSiswa($request->validated());

            return $this->createdResponse(
                new TugasSiswaResource($tugasSiswa),
                'Tugas siswa created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create tugas siswa', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create tugas siswa: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateTugasSiswaRequest $request, int $id): JsonResponse
    {
        try {
            $tugasSiswa = $this->tugasSiswaService->updateTugasSiswa($id, $request->validated());

            return $this->successResponse(
                new TugasSiswaResource($tugasSiswa),
                'Tugas siswa updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Tugas siswa not found');
        } catch (\Exception $e) {
            Log::error('Failed to update tugas siswa', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update tugas siswa: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->tugasSiswaService->deleteTugasSiswa($id);

            if (!$deleted) {
                return $this->notFoundResponse('Tugas siswa not found');
            }

            return $this->successResponse(null, 'Tugas siswa deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete tugas siswa', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete tugas siswa', 500);
        }
    }

    public function byTugas(int $tugasId, Request $request): JsonResponse
    {
        try {
            $filters = [
                'status_kumpl' => $request->input('status_kumpl'),
            ];

            $filters = array_filter($filters);
            $tugasSiswa = $this->tugasSiswaService->getTugasSiswaByTugas($tugasId, $filters);

            return $this->successResponse(
                TugasSiswaResource::collection($tugasSiswa),
                'Tugas siswa retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tugas siswa by tugas', ['tugas_id' => $tugasId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve tugas siswa', 500);
        }
    }

    public function bySiswa(int $siswaId, Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_tugas_id' => $request->input('mst_tugas_id'),
            ];

            $filters = array_filter($filters);
            $tugasSiswa = $this->tugasSiswaService->getTugasSiswaBySiswa($siswaId, $filters);

            return $this->successResponse(
                TugasSiswaResource::collection($tugasSiswa),
                'Tugas siswa retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tugas siswa by siswa', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve tugas siswa', 500);
        }
    }

    public function nilai(NilaiTugasSiswaRequest $request, int $id): JsonResponse
    {
        try {
            $tugasSiswa = $this->tugasSiswaService->nilaiTugasSiswa($id, $request->validated());

            return $this->successResponse(
                new TugasSiswaResource($tugasSiswa),
                'Tugas siswa graded successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Tugas siswa not found');
        } catch (\Exception $e) {
            Log::error('Failed to grade tugas siswa', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to grade tugas siswa: ' . $e->getMessage(), 500);
        }
    }

    public function kumpulkan(Request $request, int $siswaId, int $tugasId): JsonResponse
    {
        try {
            // Check if already submitted
            $existing = $this->tugasSiswaService->getTugasSiswaBySiswaAndTugas($siswaId, $tugasId);
            
            if ($existing) {
                // Update existing submission
                $tugasSiswa = $this->tugasSiswaService->updateTugasSiswa($existing->id, $request->all());
                return $this->successResponse(
                    new TugasSiswaResource($tugasSiswa),
                    'Tugas berhasil diperbarui'
                );
            }

            // Create new submission
            $data = $request->all();
            $data['mst_siswa_id'] = $siswaId;
            $data['mst_tugas_id'] = $tugasId;
            
            $tugasSiswa = $this->tugasSiswaService->createTugasSiswa($data);

            return $this->successResponse(
                new TugasSiswaResource($tugasSiswa),
                'Tugas berhasil dikumpulkan'
            );
        } catch (\Exception $e) {
            Log::error('Failed to kumpulkan tugas', ['siswa_id' => $siswaId, 'tugas_id' => $tugasId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to kumpulkan tugas: ' . $e->getMessage(), 500);
        }
    }
}
