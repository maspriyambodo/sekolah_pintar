<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PembayaranSpp\CreatePembayaranSppRequest;
use App\Http\Requests\Api\V1\PembayaranSpp\UpdatePembayaranSppRequest;
use App\Http\Resources\Api\V1\PembayaranSppResource;
use App\Services\PembayaranSppService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PembayaranSppController extends Controller
{
    use ApiResponseTrait;

    private PembayaranSppService $pembayaranSppService;

    public function __construct(PembayaranSppService $pembayaranSppService)
    {
        $this->pembayaranSppService = $pembayaranSppService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'siswa_id' => $request->input('siswa_id'),
                'status' => $request->input('status'),
                'tahun' => $request->input('tahun'),
                'bulan' => $request->input('bulan'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->pembayaranSppService->getAllPembayaranSpp($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Pembayaran SPP retrieved successfully', PembayaranSppResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve pembayaran SPP list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve pembayaran SPP list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $pembayaran = $this->pembayaranSppService->getPembayaranSppById($id);

            if (!$pembayaran) {
                return $this->notFoundResponse('Pembayaran SPP not found');
            }

            return $this->successResponse(new PembayaranSppResource($pembayaran));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve pembayaran SPP', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve pembayaran SPP', 500);
        }
    }

    public function store(CreatePembayaranSppRequest $request): JsonResponse
    {
        try {
            $petugasId = $request->user()?->id ?? 1;
            $pembayaran = $this->pembayaranSppService->createPembayaranSpp($request->validated(), $petugasId);

            return $this->createdResponse(
                new PembayaranSppResource($pembayaran),
                'Pembayaran SPP created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create pembayaran SPP', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create pembayaran SPP: ' . $e->getMessage(), 400);
        }
    }

    public function update(UpdatePembayaranSppRequest $request, int $id): JsonResponse
    {
        try {
            $pembayaran = $this->pembayaranSppService->updatePembayaranSpp($id, $request->validated());

            return $this->successResponse(
                new PembayaranSppResource($pembayaran),
                'Pembayaran SPP updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Pembayaran SPP not found');
        } catch (\Exception $e) {
            Log::error('Failed to update pembayaran SPP', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update pembayaran SPP: ' . $e->getMessage(), 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->pembayaranSppService->deletePembayaranSpp($id);

            if (!$deleted) {
                return $this->notFoundResponse('Pembayaran SPP not found');
            }

            return $this->successResponse(null, 'Pembayaran SPP deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete pembayaran SPP', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete pembayaran SPP: ' . $e->getMessage(), 400);
        }
    }

    public function bySiswa(int $siswaId): JsonResponse
    {
        try {
            $pembayaran = $this->pembayaranSppService->getPembayaranBySiswa($siswaId);

            return $this->successResponse(
                PembayaranSppResource::collection($pembayaran),
                'Pembayaran SPP retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve pembayaran by siswa', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve pembayaran SPP', 500);
        }
    }

    public function statusSiswa(int $siswaId, Request $request): JsonResponse
    {
        try {
            $tahunAjaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
            $status = $this->pembayaranSppService->getStatusPembayaranSiswa($siswaId, $tahunAjaran);

            return $this->successResponse($status, 'Status pembayaran retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve status pembayaran', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve status pembayaran', 500);
        }
    }

    public function bayar(CreatePembayaranSppRequest $request): JsonResponse
    {
        try {
            $petugasId = $request->user()?->id ?? 1;
            $pembayaran = $this->pembayaranSppService->bayarSpp($request->validated(), $petugasId);

            return $this->createdResponse(
                new PembayaranSppResource($pembayaran),
                'Pembayaran SPP berhasil dicatat'
            );
        } catch (\Exception $e) {
            Log::error('Failed to bayar SPP', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to bayar SPP: ' . $e->getMessage(), 400);
        }
    }
}
