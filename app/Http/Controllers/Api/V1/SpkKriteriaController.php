<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Spk\CreateKriteriaRequest;
use App\Http\Requests\Api\V1\Spk\UpdateKriteriaRequest;
use App\Http\Resources\Api\V1\Spk\SpkKriteriaResource;
use App\Services\SpkKriteriaService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SpkKriteriaController extends Controller
{
    use ApiResponseTrait;

    private SpkKriteriaService $spkKriteriaService;

    public function __construct(SpkKriteriaService $spkKriteriaService)
    {
        $this->spkKriteriaService = $spkKriteriaService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'tipe' => $request->input('tipe'),
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->spkKriteriaService->getAllKriteria($filters, $perPage);

            return $this->paginatedResponse($paginator, 'SPK Kriteria retrieved successfully', SpkKriteriaResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve SPK kriteria list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve SPK kriteria list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $kriteria = $this->spkKriteriaService->getKriteriaById($id);

            if (!$kriteria) {
                return $this->notFoundResponse('SPK Kriteria not found');
            }

            return $this->successResponse(new SpkKriteriaResource($kriteria));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve SPK kriteria', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve SPK kriteria', 500);
        }
    }

    public function store(CreateKriteriaRequest $request): JsonResponse
    {
        try {
            $kriteria = $this->spkKriteriaService->createKriteria($request->validated());

            return $this->createdResponse(
                new SpkKriteriaResource($kriteria),
                'SPK Kriteria created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create SPK kriteria', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create SPK kriteria: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateKriteriaRequest $request, int $id): JsonResponse
    {
        try {
            $kriteria = $this->spkKriteriaService->updateKriteria($id, $request->validated());

            return $this->successResponse(
                new SpkKriteriaResource($kriteria),
                'SPK Kriteria updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('SPK Kriteria not found');
        } catch (\Exception $e) {
            Log::error('Failed to update SPK kriteria', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update SPK kriteria: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->spkKriteriaService->deleteKriteria($id);

            if (!$deleted) {
                return $this->notFoundResponse('SPK Kriteria not found');
            }

            return $this->successResponse(null, 'SPK Kriteria deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete SPK kriteria', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete SPK kriteria', 500);
        }
    }

    public function totalBobot(): JsonResponse
    {
        try {
            $totalBobot = $this->spkKriteriaService->getTotalBobot();

            return $this->successResponse([
                'total_bobot' => $totalBobot,
                'is_valid' => $totalBobot == 100 || $totalBobot == 1,
                'message' => $totalBobot == 100 || $totalBobot == 1 
                    ? 'Total bobot valid (100%)' 
                    : 'Total bobot tidak sama dengan 100%',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get total bobot', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to get total bobot', 500);
        }
    }
}
