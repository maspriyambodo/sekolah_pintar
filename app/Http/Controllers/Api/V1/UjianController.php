<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Ujian\CreateUjianRequest;
use App\Http\Requests\Api\V1\Ujian\UpdateUjianRequest;
use App\Http\Resources\Api\V1\UjianResource;
use App\Services\UjianService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UjianController extends Controller
{
    use ApiResponseTrait;

    private UjianService $ujianService;

    public function __construct(UjianService $ujianService)
    {
        $this->ujianService = $ujianService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'mapel_id' => $request->input('mapel_id'),
                'kelas_id' => $request->input('kelas_id'),
                'jenis' => $request->input('jenis'),
                'semester' => $request->input('semester'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->ujianService->getAllUjian($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Ujian retrieved successfully', UjianResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ujian list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ujian list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $ujian = $this->ujianService->getUjianById($id);

            if (!$ujian) {
                return $this->notFoundResponse('Ujian not found');
            }

            return $this->successResponse(new UjianResource($ujian));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ujian', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ujian', 500);
        }
    }

    public function store(CreateUjianRequest $request): JsonResponse
    {
        try {
            $ujian = $this->ujianService->createUjian($request->validated());

            return $this->createdResponse(
                new UjianResource($ujian),
                'Ujian created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create ujian', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create ujian: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateUjianRequest $request, int $id): JsonResponse
    {
        try {
            $ujian = $this->ujianService->updateUjian($id, $request->validated());

            return $this->successResponse(
                new UjianResource($ujian),
                'Ujian updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Ujian not found');
        } catch (\Exception $e) {
            Log::error('Failed to update ujian', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update ujian: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->ujianService->deleteUjian($id);

            if (!$deleted) {
                return $this->notFoundResponse('Ujian not found');
            }

            return $this->successResponse(null, 'Ujian deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete ujian', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete ujian', 500);
        }
    }

    public function nilai(int $id): JsonResponse
    {
        try {
            $nilai = $this->ujianService->getNilaiByUjian($id);

            return $this->successResponse($nilai, 'Nilai retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve nilai', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve nilai', 500);
        }
    }

    public function byKelas(int $kelasId): JsonResponse
    {
        try {
            $ujian = $this->ujianService->getUjianByKelas($kelasId);

            return $this->successResponse(
                UjianResource::collection($ujian),
                'Ujian retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ujian by kelas', ['kelas_id' => $kelasId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ujian', 500);
        }
    }
}
