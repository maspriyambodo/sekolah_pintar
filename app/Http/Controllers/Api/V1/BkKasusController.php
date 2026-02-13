<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\BkKasus\CreateBkKasusRequest;
use App\Http\Requests\Api\V1\BkKasus\UpdateBkKasusRequest;
use App\Http\Resources\Api\V1\BkKasusResource;
use App\Services\BkKasusService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BkKasusController extends Controller
{
    use ApiResponseTrait;

    private BkKasusService $bkKasusService;

    public function __construct(BkKasusService $bkKasusService)
    {
        $this->bkKasusService = $bkKasusService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'siswa_id' => $request->input('siswa_id'),
                'guru_id' => $request->input('guru_id'),
                'jenis_id' => $request->input('jenis_id'),
                'status' => $request->input('status'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->bkKasusService->getAllKasus($filters, $perPage);

            return $this->paginatedResponse($paginator, 'BK Kasus retrieved successfully', BkKasusResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve BK kasus list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve BK kasus list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $kasus = $this->bkKasusService->getKasusById($id);

            if (!$kasus) {
                return $this->notFoundResponse('Kasus not found');
            }

            return $this->successResponse(new BkKasusResource($kasus));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve kasus', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve kasus', 500);
        }
    }

    public function store(CreateBkKasusRequest $request): JsonResponse
    {
        try {
            $kasus = $this->bkKasusService->createKasus($request->validated());

            return $this->createdResponse(
                new BkKasusResource($kasus),
                'Kasus created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create kasus', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create kasus: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateBkKasusRequest $request, int $id): JsonResponse
    {
        try {
            $kasus = $this->bkKasusService->updateKasus($id, $request->validated());

            return $this->successResponse(
                new BkKasusResource($kasus),
                'Kasus updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Kasus not found');
        } catch (\Exception $e) {
            Log::error('Failed to update kasus', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update kasus: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->bkKasusService->deleteKasus($id);

            if (!$deleted) {
                return $this->notFoundResponse('Kasus not found');
            }

            return $this->successResponse(null, 'Kasus deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete kasus', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete kasus', 500);
        }
    }

    public function bySiswa(int $siswaId): JsonResponse
    {
        try {
            $kasus = $this->bkKasusService->getKasusBySiswa($siswaId);

            return $this->successResponse(
                BkKasusResource::collection($kasus),
                'Kasus retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve kasus by siswa', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve kasus', 500);
        }
    }
}
