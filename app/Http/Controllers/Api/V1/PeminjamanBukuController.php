<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PeminjamanBuku\CreatePeminjamanBukuRequest;
use App\Http\Requests\Api\V1\PeminjamanBuku\UpdatePeminjamanBukuRequest;
use App\Http\Resources\Api\V1\PeminjamanBukuResource;
use App\Services\PeminjamanBukuService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PeminjamanBukuController extends Controller
{
    use ApiResponseTrait;

    private PeminjamanBukuService $peminjamanBukuService;

    public function __construct(PeminjamanBukuService $peminjamanBukuService)
    {
        $this->peminjamanBukuService = $peminjamanBukuService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'siswa_id' => $request->input('siswa_id'),
                'buku_id' => $request->input('buku_id'),
                'status' => $request->input('status'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->peminjamanBukuService->getAllPeminjaman($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Peminjaman buku retrieved successfully', PeminjamanBukuResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve peminjaman buku list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve peminjaman buku list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $peminjaman = $this->peminjamanBukuService->getPeminjamanById($id);

            if (!$peminjaman) {
                return $this->notFoundResponse('Peminjaman not found');
            }

            return $this->successResponse(new PeminjamanBukuResource($peminjaman));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve peminjaman', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve peminjaman', 500);
        }
    }

    public function store(CreatePeminjamanBukuRequest $request): JsonResponse
    {
        try {
            $peminjaman = $this->peminjamanBukuService->createPeminjaman($request->validated());

            return $this->createdResponse(
                new PeminjamanBukuResource($peminjaman),
                'Peminjaman created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create peminjaman', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create peminjaman: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdatePeminjamanBukuRequest $request, int $id): JsonResponse
    {
        try {
            $peminjaman = $this->peminjamanBukuService->updatePeminjaman($id, $request->validated());

            return $this->successResponse(
                new PeminjamanBukuResource($peminjaman),
                'Peminjaman updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Peminjaman not found');
        } catch (\Exception $e) {
            Log::error('Failed to update peminjaman', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update peminjaman: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->peminjamanBukuService->deletePeminjaman($id);

            if (!$deleted) {
                return $this->notFoundResponse('Peminjaman not found');
            }

            return $this->successResponse(null, 'Peminjaman deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete peminjaman', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete peminjaman', 500);
        }
    }

    public function pengembalian(int $id): JsonResponse
    {
        try {
            $peminjaman = $this->peminjamanBukuService->pengembalian($id);

            return $this->successResponse(
                new PeminjamanBukuResource($peminjaman),
                'Buku returned successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to return buku', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to return buku: ' . $e->getMessage(), 500);
        }
    }

    public function bySiswa(int $siswaId): JsonResponse
    {
        try {
            $peminjaman = $this->peminjamanBukuService->getPeminjamanBySiswa($siswaId);

            return $this->successResponse(
                PeminjamanBukuResource::collection($peminjaman),
                'Peminjaman retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve peminjaman by siswa', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve peminjaman', 500);
        }
    }

    public function overdue(): JsonResponse
    {
        try {
            $peminjaman = $this->peminjamanBukuService->getOverduePeminjaman();

            return $this->successResponse(
                PeminjamanBukuResource::collection($peminjaman),
                'Overdue peminjaman retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve overdue peminjaman', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve overdue peminjaman', 500);
        }
    }
}
