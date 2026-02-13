<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Mapel\CreateMapelRequest;
use App\Http\Requests\Api\V1\Mapel\UpdateMapelRequest;
use App\Http\Resources\Api\V1\MapelResource;
use App\Services\MapelService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MapelController extends Controller
{
    use ApiResponseTrait;

    private MapelService $mapelService;

    public function __construct(MapelService $mapelService)
    {
        $this->mapelService = $mapelService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->mapelService->getAllMapel($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Mapel retrieved successfully', MapelResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve mapel list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve mapel list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $mapel = $this->mapelService->getMapelById($id);

            if (!$mapel) {
                return $this->notFoundResponse('Mapel not found');
            }

            return $this->successResponse(new MapelResource($mapel));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve mapel', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve mapel', 500);
        }
    }

    public function store(CreateMapelRequest $request): JsonResponse
    {
        try {
            $mapel = $this->mapelService->createMapel($request->validated());

            return $this->createdResponse(
                new MapelResource($mapel),
                'Mapel created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create mapel', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create mapel: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateMapelRequest $request, int $id): JsonResponse
    {
        try {
            $mapel = $this->mapelService->updateMapel($id, $request->validated());

            return $this->successResponse(
                new MapelResource($mapel),
                'Mapel updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Mapel not found');
        } catch (\Exception $e) {
            Log::error('Failed to update mapel', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update mapel: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->mapelService->deleteMapel($id);

            if (!$deleted) {
                return $this->notFoundResponse('Mapel not found');
            }

            return $this->successResponse(null, 'Mapel deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete mapel', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete mapel', 500);
        }
    }

    public function gurus(int $id): JsonResponse
    {
        try {
            $gurus = $this->mapelService->getGurusByMapel($id);

            return $this->successResponse($gurus, 'Gurus retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve gurus by mapel', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve gurus', 500);
        }
    }
}
