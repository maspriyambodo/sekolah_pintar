<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Spk\SpkHasilResource;
use App\Services\SpkHasilService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SpkHasilController extends Controller
{
    use ApiResponseTrait;

    private SpkHasilService $spkHasilService;

    public function __construct(SpkHasilService $spkHasilService)
    {
        $this->spkHasilService = $spkHasilService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'periode' => $request->input('periode'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->spkHasilService->getAllHasil($filters, $perPage);

            return $this->paginatedResponse($paginator, 'SPK Hasil retrieved successfully', SpkHasilResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve SPK hasil list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve SPK hasil list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $hasil = $this->spkHasilService->getHasilById($id);

            if (!$hasil) {
                return $this->notFoundResponse('SPK Hasil not found');
            }

            return $this->successResponse(new SpkHasilResource($hasil));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve SPK hasil', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve SPK hasil', 500);
        }
    }

    public function byPeriode(string $periode): JsonResponse
    {
        try {
            $hasil = $this->spkHasilService->getHasilByPeriode($periode);

            return $this->successResponse(
                SpkHasilResource::collection($hasil),
                'SPK Hasil retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve SPK hasil by periode', ['periode' => $periode, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve SPK hasil', 500);
        }
    }

    public function bySiswa(int $siswaId): JsonResponse
    {
        try {
            $hasil = $this->spkHasilService->getHasilBySiswa($siswaId);

            return $this->successResponse(
                SpkHasilResource::collection($hasil),
                'SPK Hasil retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve SPK hasil by siswa', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve SPK hasil', 500);
        }
    }

    public function calculate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'periode' => 'required|string|max:50',
                'siswa_ids' => 'required|array|min:1',
                'siswa_ids.*' => 'integer|exists:mst_siswa,id',
            ]);

            $hasil = $this->spkHasilService->calculateHasil(
                $request->input('periode'),
                $request->input('siswa_ids')
            );

            return $this->successResponse(
                SpkHasilResource::collection($hasil),
                'SPK Hasil calculated successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to calculate SPK hasil', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to calculate SPK hasil: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->spkHasilService->deleteHasil($id);

            if (!$deleted) {
                return $this->notFoundResponse('SPK Hasil not found');
            }

            return $this->successResponse(null, 'SPK Hasil deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete SPK hasil', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete SPK hasil', 500);
        }
    }
}
