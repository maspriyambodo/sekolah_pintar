<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AbsensiGuru\CreateAbsensiGuruRequest;
use App\Http\Requests\Api\V1\AbsensiGuru\UpdateAbsensiGuruRequest;
use App\Http\Resources\Api\V1\AbsensiGuruResource;
use App\Services\AbsensiGuruService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AbsensiGuruController extends Controller
{
    use ApiResponseTrait;

    private AbsensiGuruService $absensiGuruService;

    public function __construct(AbsensiGuruService $absensiGuruService)
    {
        $this->absensiGuruService = $absensiGuruService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'guru_id' => $request->input('guru_id'),
                'status' => $request->input('status'),
                'tanggal_from' => $request->input('tanggal_from'),
                'tanggal_to' => $request->input('tanggal_to'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->absensiGuruService->getAllAbsensi($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Absensi guru retrieved successfully', AbsensiGuruResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve absensi guru list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve absensi guru list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $absensi = $this->absensiGuruService->getAbsensiById($id);

            if (!$absensi) {
                return $this->notFoundResponse('Absensi not found');
            }

            return $this->successResponse(new AbsensiGuruResource($absensi));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve absensi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve absensi', 500);
        }
    }

    public function store(CreateAbsensiGuruRequest $request): JsonResponse
    {
        try {
            $absensi = $this->absensiGuruService->createAbsensi($request->validated());

            return $this->createdResponse(
                new AbsensiGuruResource($absensi),
                'Absensi created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create absensi', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create absensi: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateAbsensiGuruRequest $request, int $id): JsonResponse
    {
        try {
            $absensi = $this->absensiGuruService->updateAbsensi($id, $request->validated());

            return $this->successResponse(
                new AbsensiGuruResource($absensi),
                'Absensi updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Absensi not found');
        } catch (\Exception $e) {
            Log::error('Failed to update absensi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update absensi: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->absensiGuruService->deleteAbsensi($id);

            if (!$deleted) {
                return $this->notFoundResponse('Absensi not found');
            }

            return $this->successResponse(null, 'Absensi deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete absensi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete absensi', 500);
        }
    }

    public function byGuru(int $guruId): JsonResponse
    {
        try {
            $absensi = $this->absensiGuruService->getAbsensiByGuru($guruId);

            return $this->successResponse(
                AbsensiGuruResource::collection($absensi),
                'Absensi retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve absensi by guru', ['guru_id' => $guruId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve absensi', 500);
        }
    }

    public function summary(int $guruId): JsonResponse
    {
        try {
            $summary = $this->absensiGuruService->getAbsensiSummary($guruId);

            return $this->successResponse($summary, 'Absensi summary retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve absensi summary', ['guru_id' => $guruId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve absensi summary', 500);
        }
    }
}
