<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Ranking\CreateRankingRequest;
use App\Http\Requests\Api\V1\Ranking\UpdateRankingRequest;
use App\Http\Resources\Api\V1\RankingResource;
use App\Services\RankingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RankingController extends Controller
{
    use ApiResponseTrait;

    private RankingService $rankingService;

    public function __construct(RankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'kelas_id' => $request->input('kelas_id'),
                'semester' => $request->input('semester'),
                'tahun_ajaran' => $request->input('tahun_ajaran'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->rankingService->getAllRanking($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Ranking retrieved successfully', RankingResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ranking list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ranking list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $ranking = $this->rankingService->getRankingById($id);

            if (!$ranking) {
                return $this->notFoundResponse('Ranking not found');
            }

            return $this->successResponse(new RankingResource($ranking));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ranking', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ranking', 500);
        }
    }

    public function store(CreateRankingRequest $request): JsonResponse
    {
        try {
            $ranking = $this->rankingService->createRanking($request->validated());

            return $this->createdResponse(
                new RankingResource($ranking),
                'Ranking created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create ranking', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create ranking: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateRankingRequest $request, int $id): JsonResponse
    {
        try {
            $ranking = $this->rankingService->updateRanking($id, $request->validated());

            return $this->successResponse(
                new RankingResource($ranking),
                'Ranking updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Ranking not found');
        } catch (\Exception $e) {
            Log::error('Failed to update ranking', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update ranking: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->rankingService->deleteRanking($id);

            if (!$deleted) {
                return $this->notFoundResponse('Ranking not found');
            }

            return $this->successResponse(null, 'Ranking deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete ranking', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete ranking', 500);
        }
    }

    public function byKelas(int $kelasId): JsonResponse
    {
        try {
            $ranking = $this->rankingService->getRankingByKelas($kelasId);

            return $this->successResponse(
                RankingResource::collection($ranking),
                'Ranking retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ranking by kelas', ['kelas_id' => $kelasId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ranking', 500);
        }
    }

    public function generate(Request $request): JsonResponse
    {
        try {
            $kelasId = $request->input('kelas_id');
            $semester = $request->input('semester');
            $tahunAjaran = $request->input('tahun_ajaran');

            $ranking = $this->rankingService->generateRanking($kelasId, $semester, $tahunAjaran);

            return $this->successResponse(
                RankingResource::collection($ranking),
                'Ranking generated successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to generate ranking', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to generate ranking: ' . $e->getMessage(), 500);
        }
    }
}
