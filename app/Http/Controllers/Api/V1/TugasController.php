<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Tugas\CreateTugasRequest;
use App\Http\Requests\Api\V1\Tugas\UpdateTugasRequest;
use App\Http\Resources\Api\V1\TugasResource;
use App\Services\TugasService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TugasController extends Controller
{
    use ApiResponseTrait;

    private TugasService $tugasService;

    public function __construct(TugasService $tugasService)
    {
        $this->tugasService = $tugasService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_guru_mapel_id' => $request->input('mst_guru_mapel_id'),
                'mst_kelas_id' => $request->input('mst_kelas_id'),
                'status' => $request->input('status'),
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->tugasService->getAllTugas($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Tugas retrieved successfully', TugasResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tugas list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve tugas list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $tugas = $this->tugasService->getTugasById($id);

            if (!$tugas) {
                return $this->notFoundResponse('Tugas not found');
            }

            return $this->successResponse(new TugasResource($tugas));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tugas', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve tugas', 500);
        }
    }

    public function store(CreateTugasRequest $request): JsonResponse
    {
        try {
            $tugas = $this->tugasService->createTugas($request->validated());

            return $this->createdResponse(
                new TugasResource($tugas),
                'Tugas created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create tugas', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create tugas: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateTugasRequest $request, int $id): JsonResponse
    {
        try {
            $tugas = $this->tugasService->updateTugas($id, $request->validated());

            return $this->successResponse(
                new TugasResource($tugas),
                'Tugas updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Tugas not found');
        } catch (\Exception $e) {
            Log::error('Failed to update tugas', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update tugas: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->tugasService->deleteTugas($id);

            if (!$deleted) {
                return $this->notFoundResponse('Tugas not found');
            }

            return $this->successResponse(null, 'Tugas deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete tugas', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete tugas', 500);
        }
    }

    public function byKelas(int $kelasId, Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_guru_mapel_id' => $request->input('mst_guru_mapel_id'),
            ];

            $filters = array_filter($filters);
            $tugas = $this->tugasService->getTugasByKelas($kelasId, $filters);

            return $this->successResponse(
                TugasResource::collection($tugas),
                'Tugas retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tugas by kelas', ['kelas_id' => $kelasId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve tugas', 500);
        }
    }

    public function byGuruMapel(int $guruMapelId, Request $request): JsonResponse
    {
        try {
            $filters = [
                'status' => $request->input('status'),
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $tugas = $this->tugasService->getTugasByGuruMapel($guruMapelId, $filters);

            return $this->successResponse(
                TugasResource::collection($tugas),
                'Tugas retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tugas by guru mapel', ['guru_mapel_id' => $guruMapelId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve tugas', 500);
        }
    }
}
