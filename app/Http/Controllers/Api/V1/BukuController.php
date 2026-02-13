<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Buku\CreateBukuRequest;
use App\Http\Requests\Api\V1\Buku\UpdateBukuRequest;
use App\Http\Resources\Api\V1\BukuResource;
use App\Services\BukuService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BukuController extends Controller
{
    use ApiResponseTrait;

    private BukuService $bukuService;

    public function __construct(BukuService $bukuService)
    {
        $this->bukuService = $bukuService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->input('search'),
                'penulis' => $request->input('penulis'),
                'penerbit' => $request->input('penerbit'),
                'tahun' => $request->input('tahun'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->bukuService->getAllBuku($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Buku retrieved successfully', BukuResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve buku list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve buku list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $buku = $this->bukuService->getBukuById($id);

            if (!$buku) {
                return $this->notFoundResponse('Buku not found');
            }

            return $this->successResponse(new BukuResource($buku));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve buku', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve buku', 500);
        }
    }

    public function store(CreateBukuRequest $request): JsonResponse
    {
        try {
            $buku = $this->bukuService->createBuku($request->validated());

            return $this->createdResponse(
                new BukuResource($buku),
                'Buku created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create buku', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create buku: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateBukuRequest $request, int $id): JsonResponse
    {
        try {
            $buku = $this->bukuService->updateBuku($id, $request->validated());

            return $this->successResponse(
                new BukuResource($buku),
                'Buku updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Buku not found');
        } catch (\Exception $e) {
            Log::error('Failed to update buku', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update buku: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->bukuService->deleteBuku($id);

            if (!$deleted) {
                return $this->notFoundResponse('Buku not found');
            }

            return $this->successResponse(null, 'Buku deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete buku', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete buku', 500);
        }
    }

    public function available(): JsonResponse
    {
        try {
            $buku = $this->bukuService->getAvailableBuku();

            return $this->successResponse(
                BukuResource::collection($buku),
                'Available buku retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve available buku', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve available buku', 500);
        }
    }

    public function peminjaman(int $id): JsonResponse
    {
        try {
            $peminjaman = $this->bukuService->getPeminjamanByBuku($id);

            return $this->successResponse($peminjaman, 'Peminjaman retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve peminjaman', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve peminjaman', 500);
        }
    }
}
