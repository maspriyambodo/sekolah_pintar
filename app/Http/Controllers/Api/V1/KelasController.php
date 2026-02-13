<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Kelas\CreateKelasRequest;
use App\Http\Requests\Api\V1\Kelas\UpdateKelasRequest;
use App\Http\Resources\Api\V1\KelasResource;
use App\Services\KelasService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KelasController extends Controller
{
    use ApiResponseTrait;

    private KelasService $kelasService;

    public function __construct(KelasService $kelasService)
    {
        $this->kelasService = $kelasService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'tingkat' => $request->input('tingkat'),
                'tahun_ajaran' => $request->input('tahun_ajaran'),
                'wali_guru_id' => $request->input('wali_guru_id'),
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->kelasService->getAllKelas($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Kelas retrieved successfully', KelasResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve kelas list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve kelas list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $kelas = $this->kelasService->getKelasById($id);

            if (!$kelas) {
                return $this->notFoundResponse('Kelas not found');
            }

            return $this->successResponse(new KelasResource($kelas));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve kelas', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve kelas', 500);
        }
    }

    public function store(CreateKelasRequest $request): JsonResponse
    {
        try {
            $kelas = $this->kelasService->createKelas($request->validated());

            return $this->createdResponse(
                new KelasResource($kelas),
                'Kelas created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create kelas', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create kelas: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateKelasRequest $request, int $id): JsonResponse
    {
        try {
            $kelas = $this->kelasService->updateKelas($id, $request->validated());

            return $this->successResponse(
                new KelasResource($kelas),
                'Kelas updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Kelas not found');
        } catch (\Exception $e) {
            Log::error('Failed to update kelas', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update kelas: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->kelasService->deleteKelas($id);

            if (!$deleted) {
                return $this->notFoundResponse('Kelas not found');
            }

            return $this->successResponse(null, 'Kelas deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete kelas', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete kelas', 500);
        }
    }

    public function siswa(int $id): JsonResponse
    {
        try {
            $siswa = $this->kelasService->getSiswaByKelas($id);

            return $this->successResponse($siswa, 'Siswa retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve siswa by kelas', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve siswa', 500);
        }
    }

    public function byTingkat(int $tingkat): JsonResponse
    {
        try {
            $kelas = $this->kelasService->getKelasByTingkat($tingkat);

            return $this->successResponse(
                KelasResource::collection($kelas),
                'Kelas retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve kelas by tingkat', ['tingkat' => $tingkat, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve kelas', 500);
        }
    }
}
