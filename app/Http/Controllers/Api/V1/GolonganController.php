<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Golongan\CreateGolonganRequest;
use App\Http\Requests\Api\V1\Golongan\UpdateGolonganRequest;
use App\Http\Resources\Api\V1\GolonganResource;
use App\Services\GolonganService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GolonganController extends Controller
{
    use ApiResponseTrait;

    private GolonganService $golonganService;

    public function __construct(GolonganService $golonganService)
    {
        $this->golonganService = $golonganService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->golonganService->getAllGolongan($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Golongan retrieved successfully', GolonganResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve Golongan list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve Golongan list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $golongan = $this->golonganService->getGolonganById($id);

            if (!$golongan) {
                return $this->notFoundResponse('Golongan not found');
            }

            return $this->successResponse(new GolonganResource($golongan));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve Golongan', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve Golongan', 500);
        }
    }

    public function store(CreateGolonganRequest $request): JsonResponse
    {
        try {
            $golongan = $this->golonganService->createGolongan($request->validated());

            return $this->createdResponse(
                new GolonganResource($golongan),
                'Golongan created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create Golongan', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create Golongan: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateGolonganRequest $request, int $id): JsonResponse
    {
        try {
            $golongan = $this->golonganService->updateGolongan($id, $request->validated());

            return $this->successResponse(
                new GolonganResource($golongan),
                'Golongan updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Golongan not found');
        } catch (\Exception $e) {
            Log::error('Failed to update Golongan', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update Golongan: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->golonganService->deleteGolongan($id);

            if (!$deleted) {
                return $this->notFoundResponse('Golongan not found');
            }

            return $this->successResponse(null, 'Golongan deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete Golongan', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete Golongan', 500);
        }
    }

    public function list(): JsonResponse
    {
        try {
            $golongan = $this->golonganService->getAllGolonganList();

            return $this->successResponse(
                GolonganResource::collection($golongan),
                'Golongan list retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve Golongan list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve Golongan list', 500);
        }
    }
}
