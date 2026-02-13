<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Nilai\CreateNilaiRequest;
use App\Http\Requests\Api\V1\Nilai\UpdateNilaiRequest;
use App\Http\Resources\Api\V1\NilaiResource;
use App\Services\NilaiService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NilaiController extends Controller
{
    use ApiResponseTrait;

    private NilaiService $nilaiService;

    public function __construct(NilaiService $nilaiService)
    {
        $this->nilaiService = $nilaiService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'siswa_id' => $request->input('siswa_id'),
                'ujian_id' => $request->input('ujian_id'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->nilaiService->getAllNilai($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Nilai retrieved successfully', NilaiResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve nilai list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve nilai list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $nilai = $this->nilaiService->getNilaiById($id);

            if (!$nilai) {
                return $this->notFoundResponse('Nilai not found');
            }

            return $this->successResponse(new NilaiResource($nilai));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve nilai', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve nilai', 500);
        }
    }

    public function store(CreateNilaiRequest $request): JsonResponse
    {
        try {
            $nilai = $this->nilaiService->createNilai($request->validated());

            return $this->createdResponse(
                new NilaiResource($nilai),
                'Nilai created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create nilai', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create nilai: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateNilaiRequest $request, int $id): JsonResponse
    {
        try {
            $nilai = $this->nilaiService->updateNilai($id, $request->validated());

            return $this->successResponse(
                new NilaiResource($nilai),
                'Nilai updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Nilai not found');
        } catch (\Exception $e) {
            Log::error('Failed to update nilai', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update nilai: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->nilaiService->deleteNilai($id);

            if (!$deleted) {
                return $this->notFoundResponse('Nilai not found');
            }

            return $this->successResponse(null, 'Nilai deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete nilai', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete nilai', 500);
        }
    }

    public function bySiswa(int $siswaId): JsonResponse
    {
        try {
            $nilai = $this->nilaiService->getNilaiBySiswa($siswaId);

            return $this->successResponse(
                NilaiResource::collection($nilai),
                'Nilai retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve nilai by siswa', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve nilai', 500);
        }
    }

    public function byUjian(int $ujianId): JsonResponse
    {
        try {
            $nilai = $this->nilaiService->getNilaiByUjian($ujianId);

            return $this->successResponse(
                NilaiResource::collection($nilai),
                'Nilai retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve nilai by ujian', ['ujian_id' => $ujianId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve nilai', 500);
        }
    }

    public function rataRata(int $siswaId): JsonResponse
    {
        try {
            $rataRata = $this->nilaiService->getRataRataBySiswa($siswaId);

            return $this->successResponse(['rata_rata' => $rataRata], 'Rata-rata nilai retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve rata-rata nilai', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve rata-rata nilai', 500);
        }
    }
}
