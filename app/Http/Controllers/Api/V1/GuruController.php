<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Guru\CreateGuruRequest;
use App\Http\Requests\Api\V1\Guru\UpdateGuruRequest;
use App\Http\Resources\Api\V1\GuruResource;
use App\Services\GuruService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GuruController extends Controller
{
    use ApiResponseTrait;

    private GuruService $guruService;

    public function __construct(GuruService $guruService)
    {
        $this->guruService = $guruService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->guruService->getAllGuru($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Guru retrieved successfully', GuruResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve guru list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve guru list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $guru = $this->guruService->getGuruById($id);

            if (!$guru) {
                return $this->notFoundResponse('Guru not found');
            }

            return $this->successResponse(new GuruResource($guru));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve guru', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve guru', 500);
        }
    }

    public function store(CreateGuruRequest $request): JsonResponse
    {
        try {
            $guru = $this->guruService->createGuru($request->validated());

            return $this->createdResponse(
                new GuruResource($guru),
                'Guru created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create guru', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create guru: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateGuruRequest $request, int $id): JsonResponse
    {
        try {
            $guru = $this->guruService->updateGuru($id, $request->validated());

            return $this->successResponse(
                new GuruResource($guru),
                'Guru updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Guru not found');
        } catch (\Exception $e) {
            Log::error('Failed to update guru', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update guru: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->guruService->deleteGuru($id);

            if (!$deleted) {
                return $this->notFoundResponse('Guru not found');
            }

            return $this->successResponse(null, 'Guru deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete guru', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete guru', 500);
        }
    }

    public function byMapel(int $mapelId): JsonResponse
    {
        try {
            $guru = $this->guruService->getGuruByMapel($mapelId);

            return $this->successResponse(
                GuruResource::collection($guru),
                'Guru retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve guru by mapel', ['mapel_id' => $mapelId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve guru', 500);
        }
    }

    public function absensiSummary(int $id, Request $request): JsonResponse
    {
        try {
            $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', now()->format('Y-m-d'));

            $summary = $this->guruService->getAbsensiSummary($id, $startDate, $endDate);

            return $this->successResponse($summary, 'Absensi summary retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve absensi summary', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve absensi summary', 500);
        }
    }
}
