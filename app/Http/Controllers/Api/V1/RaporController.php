<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Rapor\CreateRaporRequest;
use App\Http\Requests\Api\V1\Rapor\UpdateRaporRequest;
use App\Http\Resources\Api\V1\RaporResource;
use App\Services\RaporService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RaporController extends Controller
{
    use ApiResponseTrait;

    private RaporService $raporService;

    public function __construct(RaporService $raporService)
    {
        $this->raporService = $raporService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'siswa_id' => $request->input('siswa_id'),
                'semester' => $request->input('semester'),
                'tahun_ajaran' => $request->input('tahun_ajaran'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->raporService->getAllRapor($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Rapor retrieved successfully', RaporResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve rapor list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve rapor list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $rapor = $this->raporService->getRaporById($id);

            if (!$rapor) {
                return $this->notFoundResponse('Rapor not found');
            }

            return $this->successResponse(new RaporResource($rapor));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve rapor', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve rapor', 500);
        }
    }

    public function store(CreateRaporRequest $request): JsonResponse
    {
        try {
            $rapor = $this->raporService->createRapor($request->validated());

            return $this->createdResponse(
                new RaporResource($rapor),
                'Rapor created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create rapor', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create rapor: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateRaporRequest $request, int $id): JsonResponse
    {
        try {
            $rapor = $this->raporService->updateRapor($id, $request->validated());

            return $this->successResponse(
                new RaporResource($rapor),
                'Rapor updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Rapor not found');
        } catch (\Exception $e) {
            Log::error('Failed to update rapor', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update rapor: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->raporService->deleteRapor($id);

            if (!$deleted) {
                return $this->notFoundResponse('Rapor not found');
            }

            return $this->successResponse(null, 'Rapor deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete rapor', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete rapor', 500);
        }
    }

    public function bySiswa(int $siswaId): JsonResponse
    {
        try {
            $rapor = $this->raporService->getRaporBySiswa($siswaId);

            return $this->successResponse(
                RaporResource::collection($rapor),
                'Rapor retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve rapor by siswa', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve rapor', 500);
        }
    }

    public function detail(int $id): JsonResponse
    {
        try {
            $detail = $this->raporService->getRaporDetail($id);

            return $this->successResponse($detail, 'Rapor detail retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve rapor detail', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve rapor detail', 500);
        }
    }
}
