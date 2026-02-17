<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Spk\SpkPenilaianResource;
use App\Services\SpkPenilaianService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SpkPenilaianController extends Controller
{
    use ApiResponseTrait;

    private SpkPenilaianService $spkPenilaianService;

    public function __construct(SpkPenilaianService $spkPenilaianService)
    {
        $this->spkPenilaianService = $spkPenilaianService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_siswa_id' => $request->input('mst_siswa_id'),
                'spk_kriteria_id' => $request->input('spk_kriteria_id'),
                'tahun_ajaran' => $request->input('tahun_ajaran'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->spkPenilaianService->getAllPenilaian($filters, $perPage);

            return $this->paginatedResponse($paginator, 'SPK Penilaian retrieved successfully', SpkPenilaianResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve SPK penilaian list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve SPK penilaian list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $penilaian = $this->spkPenilaianService->getPenilaianById($id);

            if (!$penilaian) {
                return $this->notFoundResponse('SPK Penilaian not found');
            }

            return $this->successResponse(new SpkPenilaianResource($penilaian));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve SPK penilaian', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve SPK penilaian', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'mst_siswa_id' => 'required|integer|exists:mst_siswa,id',
                'spk_kriteria_id' => 'required|integer|exists:spk_kriteria,id',
                'nilai' => 'required|numeric',
                'tahun_ajaran' => 'nullable|string|max:9',
            ]);

            $penilaian = $this->spkPenilaianService->createPenilaian($request->all());

            return $this->createdResponse(
                new SpkPenilaianResource($penilaian),
                'SPK Penilaian created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create SPK penilaian', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create SPK penilaian: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'nilai' => 'sometimes|numeric',
                'tahun_ajaran' => 'nullable|string|max:9',
            ]);

            $penilaian = $this->spkPenilaianService->updatePenilaian($id, $request->all());

            return $this->successResponse(
                new SpkPenilaianResource($penilaian),
                'SPK Penilaian updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('SPK Penilaian not found');
        } catch (\Exception $e) {
            Log::error('Failed to update SPK penilaian', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update SPK penilaian: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->spkPenilaianService->deletePenilaian($id);

            if (!$deleted) {
                return $this->notFoundResponse('SPK Penilaian not found');
            }

            return $this->successResponse(null, 'SPK Penilaian deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete SPK penilaian', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete SPK penilaian', 500);
        }
    }

    public function bySiswa(int $siswaId, Request $request): JsonResponse
    {
        try {
            $tahunAjaran = $request->input('tahun_ajaran');
            $penilaian = $this->spkPenilaianService->getPenilaianBySiswa($siswaId, $tahunAjaran);

            return $this->successResponse(
                SpkPenilaianResource::collection($penilaian),
                'SPK Penilaian retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve SPK penilaian by siswa', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve SPK penilaian', 500);
        }
    }

    public function byKriteria(int $kriteriaId, Request $request): JsonResponse
    {
        try {
            $tahunAjaran = $request->input('tahun_ajaran');
            $penilaian = $this->spkPenilaianService->getPenilaianByKriteria($kriteriaId, $tahunAjaran);

            return $this->successResponse(
                SpkPenilaianResource::collection($penilaian),
                'SPK Penilaian retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve SPK penilaian by kriteria', ['kriteria_id' => $kriteriaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve SPK penilaian', 500);
        }
    }
}
