<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\BkJenis\CreateBkJenisRequest;
use App\Http\Requests\Api\V1\BkJenis\UpdateBkJenisRequest;
use App\Http\Resources\Api\V1\BkJenisResource;
use App\Services\BkJenisService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BkJenisController extends Controller
{
    use ApiResponseTrait;

    private BkJenisService $bkJenisService;

    public function __construct(BkJenisService $bkJenisService)
    {
        $this->bkJenisService = $bkJenisService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->bkJenisService->getAllBkJenis($filters, $perPage);

            return $this->paginatedResponse($paginator, 'BK Jenis retrieved successfully', BkJenisResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve BK jenis list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve BK jenis list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $bkJenis = $this->bkJenisService->getBkJenisById($id);

            if (!$bkJenis) {
                return $this->notFoundResponse('BK Jenis not found');
            }

            return $this->successResponse(new BkJenisResource($bkJenis));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve BK jenis', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve BK jenis', 500);
        }
    }

    public function store(CreateBkJenisRequest $request): JsonResponse
    {
        try {
            $bkJenis = $this->bkJenisService->createBkJenis($request->validated());

            return $this->createdResponse(
                new BkJenisResource($bkJenis),
                'BK Jenis created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create BK jenis', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create BK jenis: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateBkJenisRequest $request, int $id): JsonResponse
    {
        try {
            $bkJenis = $this->bkJenisService->updateBkJenis($id, $request->validated());

            return $this->successResponse(
                new BkJenisResource($bkJenis),
                'BK Jenis updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('BK Jenis not found');
        } catch (\Exception $e) {
            Log::error('Failed to update BK jenis', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update BK jenis: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->bkJenisService->deleteBkJenis($id);

            if (!$deleted) {
                return $this->notFoundResponse('BK Jenis not found');
            }

            return $this->successResponse(null, 'BK Jenis deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete BK jenis', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete BK jenis', 500);
        }
    }
}
