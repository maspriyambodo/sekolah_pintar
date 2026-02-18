<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\PpdbDokumenService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PpdbDokumenController extends Controller
{
    use ApiResponseTrait;

    private PpdbDokumenService $dokumenService;

    public function __construct(PpdbDokumenService $dokumenService)
    {
        $this->dokumenService = $dokumenService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'ppdb_pendaftar_id' => $request->input('ppdb_pendaftar_id'),
                'jenis_dokumen' => $request->input('jenis_dokumen'),
                'verifikasi_status' => $request->input('verifikasi_status'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->dokumenService->getAllDokumen($filters, $perPage);

            return $this->paginatedResponse($paginator, 'PPDB Dokumen retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ppdb dokumen list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ppdb dokumen list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $dokumen = $this->dokumenService->getDokumenById($id);

            if (!$dokumen) {
                return $this->notFoundResponse('PPDB Dokumen not found');
            }

            return $this->successResponse($dokumen);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ppdb dokumen', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ppdb dokumen', 500);
        }
    }

    public function byPendaftaran(int $pendaftaranId): JsonResponse
    {
        try {
            $dokumens = $this->dokumenService->getDokumenByPendaftaran($pendaftaranId);

            return $this->successResponse($dokumens, 'PPDB Dokumen retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ppdb dokumen by pendaftaran', ['pendaftaran_id' => $pendaftaranId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ppdb dokumen', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ppdb_pendaftar_id' => ['required', 'integer'],
                'jenis_dokumen' => ['required', 'string', 'max:50'],
                'file_path' => ['required', 'string', 'max:255'],
                'verifikasi_status' => ['nullable', 'boolean'],
                'catatan_admin' => ['nullable', 'string'],
            ]);

            $dokumen = $this->dokumenService->createDokumen($request->all());

            return $this->createdResponse($dokumen, 'PPDB Dokumen created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create ppdb dokumen', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create ppdb dokumen: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'jenis_dokumen' => ['sometimes', 'string', 'max:50'],
                'file_path' => ['sometimes', 'string', 'max:255'],
                'verifikasi_status' => ['nullable', 'boolean'],
                'catatan_admin' => ['nullable', 'string'],
            ]);

            $dokumen = $this->dokumenService->updateDokumen($id, $request->all());

            return $this->successResponse($dokumen, 'PPDB Dokumen updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('PPDB Dokumen not found');
        } catch (\Exception $e) {
            Log::error('Failed to update ppdb dokumen', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update ppdb dokumen: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->dokumenService->deleteDokumen($id);

            if (!$deleted) {
                return $this->notFoundResponse('PPDB Dokumen not found');
            }

            return $this->successResponse(null, 'PPDB Dokumen deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete ppdb dokumen', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete ppdb dokumen', 500);
        }
    }

    public function verify(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'catatan' => ['nullable', 'string'],
            ]);

            $dokumen = $this->dokumenService->verifyDokumen(
                $id,
                $request->input('catatan')
            );

            return $this->successResponse($dokumen, 'PPDB Dokumen verified successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('PPDB Dokumen not found');
        } catch (\Exception $e) {
            Log::error('Failed to verify ppdb dokumen', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to verify ppdb dokumen', 500);
        }
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'catatan' => ['required', 'string'],
            ]);

            $dokumen = $this->dokumenService->rejectDokumen(
                $id,
                $request->input('catatan')
            );

            return $this->successResponse($dokumen, 'PPDB Dokumen rejected successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('PPDB Dokumen not found');
        } catch (\Exception $e) {
            Log::error('Failed to reject ppdb dokumen', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to reject ppdb dokumen', 500);
        }
    }
}
