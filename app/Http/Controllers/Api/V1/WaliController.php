<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Wali\CreateWaliRequest;
use App\Http\Requests\Api\V1\Wali\UpdateWaliRequest;
use App\Http\Resources\Api\V1\WaliResource;
use App\Services\WaliService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WaliController extends Controller
{
    use ApiResponseTrait;

    private WaliService $waliService;

    public function __construct(WaliService $waliService)
    {
        $this->waliService = $waliService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->waliService->getAllWali($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Wali retrieved successfully', WaliResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve wali list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve wali list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $wali = $this->waliService->getWaliById($id);

            if (!$wali) {
                return $this->notFoundResponse('Wali not found');
            }

            return $this->successResponse(new WaliResource($wali));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve wali', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve wali', 500);
        }
    }

    public function store(CreateWaliRequest $request): JsonResponse
    {
        try {
            $wali = $this->waliService->createWali($request->validated());

            return $this->createdResponse(
                new WaliResource($wali),
                'Wali created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create wali', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create wali: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateWaliRequest $request, int $id): JsonResponse
    {
        try {
            $wali = $this->waliService->updateWali($id, $request->validated());

            return $this->successResponse(
                new WaliResource($wali),
                'Wali updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Wali not found');
        } catch (\Exception $e) {
            Log::error('Failed to update wali', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update wali: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->waliService->deleteWali($id);

            if (!$deleted) {
                return $this->notFoundResponse('Wali not found');
            }

            return $this->successResponse(null, 'Wali deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete wali', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete wali', 500);
        }
    }

    public function siswa(int $id): JsonResponse
    {
        try {
            $siswa = $this->waliService->getSiswaByWali($id);

            return $this->successResponse($siswa, 'Siswa retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve siswa by wali', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve siswa', 500);
        }
    }
}
