<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TarifSpp\CreateTarifSppRequest;
use App\Http\Requests\Api\V1\TarifSpp\UpdateTarifSppRequest;
use App\Http\Resources\Api\V1\TarifSppResource;
use App\Services\TarifSppService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TarifSppController extends Controller
{
    use ApiResponseTrait;

    private TarifSppService $tarifSppService;

    public function __construct(TarifSppService $tarifSppService)
    {
        $this->tarifSppService = $tarifSppService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'kelas_id' => $request->input('kelas_id'),
                'tahun_ajaran' => $request->input('tahun_ajaran'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->tarifSppService->getAllTarifSpp($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Tarif SPP retrieved successfully', TarifSppResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tarif SPP list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve tarif SPP list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $tarifSpp = $this->tarifSppService->getTarifSppById($id);

            if (!$tarifSpp) {
                return $this->notFoundResponse('Tarif SPP not found');
            }

            return $this->successResponse(new TarifSppResource($tarifSpp));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tarif SPP', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve tarif SPP', 500);
        }
    }

    public function store(CreateTarifSppRequest $request): JsonResponse
    {
        try {
            $tarifSpp = $this->tarifSppService->createTarifSpp($request->validated());

            return $this->createdResponse(
                new TarifSppResource($tarifSpp),
                'Tarif SPP created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create tarif SPP', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create tarif SPP: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateTarifSppRequest $request, int $id): JsonResponse
    {
        try {
            $tarifSpp = $this->tarifSppService->updateTarifSpp($id, $request->validated());

            return $this->successResponse(
                new TarifSppResource($tarifSpp),
                'Tarif SPP updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Tarif SPP not found');
        } catch (\Exception $e) {
            Log::error('Failed to update tarif SPP', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update tarif SPP: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->tarifSppService->deleteTarifSpp($id);

            if (!$deleted) {
                return $this->notFoundResponse('Tarif SPP not found');
            }

            return $this->successResponse(null, 'Tarif SPP deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete tarif SPP', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete tarif SPP', 500);
        }
    }

    public function byKelas(int $kelasId, Request $request): JsonResponse
    {
        try {
            $tahunAjaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
            $tarifSpp = $this->tarifSppService->getTarifSppByKelas($kelasId, $tahunAjaran);

            if (!$tarifSpp) {
                return $this->notFoundResponse('Tarif SPP not found for this class and academic year');
            }

            return $this->successResponse(
                new TarifSppResource($tarifSpp),
                'Tarif SPP retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tarif SPP by kelas', ['kelas_id' => $kelasId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve tarif SPP', 500);
        }
    }
}
