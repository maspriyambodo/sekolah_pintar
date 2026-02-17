<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Materi\CreateMateriRequest;
use App\Http\Requests\Api\V1\Materi\UpdateMateriRequest;
use App\Http\Resources\Api\V1\MateriResource;
use App\Services\MateriService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MateriController extends Controller
{
    use ApiResponseTrait;

    private MateriService $materiService;

    public function __construct(MateriService $materiService)
    {
        $this->materiService = $materiService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_guru_mapel_id' => $request->input('mst_guru_mapel_id'),
                'status' => $request->input('status'),
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->materiService->getAllMateri($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Materi retrieved successfully', MateriResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve materi list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve materi list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $materi = $this->materiService->getMateriById($id);

            if (!$materi) {
                return $this->notFoundResponse('Materi not found');
            }

            return $this->successResponse(new MateriResource($materi));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve materi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve materi', 500);
        }
    }

    public function store(CreateMateriRequest $request): JsonResponse
    {
        try {
            $materi = $this->materiService->createMateri($request->validated());

            return $this->createdResponse(
                new MateriResource($materi),
                'Materi created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create materi', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create materi: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateMateriRequest $request, int $id): JsonResponse
    {
        try {
            $materi = $this->materiService->updateMateri($id, $request->validated());

            return $this->successResponse(
                new MateriResource($materi),
                'Materi updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Materi not found');
        } catch (\Exception $e) {
            Log::error('Failed to update materi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update materi: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->materiService->deleteMateri($id);

            if (!$deleted) {
                return $this->notFoundResponse('Materi not found');
            }

            return $this->successResponse(null, 'Materi deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete materi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete materi', 500);
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
            $materi = $this->materiService->getMateriByGuruMapel($guruMapelId, $filters);

            return $this->successResponse(
                MateriResource::collection($materi),
                'Materi retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve materi by guru mapel', ['guru_mapel_id' => $guruMapelId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve materi', 500);
        }
    }
}
