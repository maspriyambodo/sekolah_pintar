<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Master\MstOrganisasiJabatan;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrganisasiJabatanController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        try {
            $query = MstOrganisasiJabatan::query();

            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where('nama', 'like', "%{$search}%");
            }

            $perPage = (int) $request->input('per_page', 15);
            $paginator = $query->ordered()->paginate($perPage);

            return $this->paginatedResponse($paginator, 'Jabatan organisasi retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve jabatan organisasi list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve jabatan organisasi list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $jabatan = MstOrganisasiJabatan::with('anggota.siswa')->find($id);

            if (!$jabatan) {
                return $this->notFoundResponse('Jabatan organisasi not found');
            }

            return $this->successResponse($jabatan);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve jabatan organisasi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve jabatan organisasi', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'deskripsi' => 'nullable|string|max:255',
                'urutan' => 'nullable|integer|min:0',
            ]);

            $jabatan = MstOrganisasiJabatan::create($validated);

            return $this->createdResponse($jabatan, 'Jabatan organisasi created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create jabatan organisasi', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create jabatan organisasi: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nama' => 'sometimes|string|max:100',
                'deskripsi' => 'nullable|string|max:255',
                'urutan' => 'nullable|integer|min:0',
            ]);

            $jabatan = MstOrganisasiJabatan::findOrFail($id);
            $jabatan->update($validated);

            return $this->successResponse($jabatan, 'Jabatan organisasi updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Jabatan organisasi not found');
        } catch (\Exception $e) {
            Log::error('Failed to update jabatan organisasi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update jabatan organisasi: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $jabatan = MstOrganisasiJabatan::find($id);

            if (!$jabatan) {
                return $this->notFoundResponse('Jabatan organisasi not found');
            }

            // Check if jabatan is being used
            if ($jabatan->anggota()->count() > 0) {
                return $this->errorResponse('Cannot delete jabatan that is being used by anggota', 422);
            }

            $jabatan->delete();

            return $this->successResponse(null, 'Jabatan organisasi deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete jabatan organisasi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete jabatan organisasi', 500);
        }
    }

    public function all(): JsonResponse
    {
        try {
            $jabatan = MstOrganisasiJabatan::ordered()->get();

            return $this->successResponse($jabatan, 'All jabatan organisasi retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve all jabatan organisasi', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve jabatan organisasi', 500);
        }
    }
}
