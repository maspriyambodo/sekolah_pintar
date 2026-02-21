<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Master\MstOrganisasi;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrganisasiController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        try {
            $query = MstOrganisasi::with('pembina');

            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->has('pembina_guru_id')) {
                $query->where('pembina_guru_id', $request->input('pembina_guru_id'));
            }

            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                });
            }

            $perPage = (int) $request->input('per_page', 15);
            $paginator = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return $this->paginatedResponse($paginator, 'Organisasi retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve organisasi list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve organisasi list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $organisasi = MstOrganisasi::with(['pembina', 'anggota.jabatan', 'anggota.siswa'])->find($id);

            if (!$organisasi) {
                return $this->notFoundResponse('Organisasi not found');
            }

            return $this->successResponse($organisasi);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve organisasi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve organisasi', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'kode' => 'required|string|max:20|unique:mst_organisasi,kode',
                'nama' => 'required|string|max:100',
                'deskripsi' => 'nullable|string',
                'pembina_guru_id' => 'nullable|integer|exists:mst_guru,id',
                'periode_mulai' => 'required|integer|min:1900|max:2100',
                'periode_selesai' => 'nullable|integer|min:1900|max:2100|gte:periode_mulai',
                'status' => 'nullable|in:aktif,nonaktif',
            ]);

            $organisasi = MstOrganisasi::create($validated);

            return $this->createdResponse($organisasi, 'Organisasi created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create organisasi', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create organisasi: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'kode' => 'sometimes|string|max:20|unique:mst_organisasi,kode,' . $id,
                'nama' => 'sometimes|string|max:100',
                'deskripsi' => 'nullable|string',
                'pembina_guru_id' => 'nullable|integer|exists:mst_guru,id',
                'periode_mulai' => 'sometimes|integer|min:1900|max:2100',
                'periode_selesai' => 'nullable|integer|min:1900|max:2100|gte:periode_mulai',
                'status' => 'nullable|in:aktif,nonaktif',
            ]);

            $organisasi = MstOrganisasi::findOrFail($id);
            $organisasi->update($validated);

            return $this->successResponse($organisasi, 'Organisasi updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Organisasi not found');
        } catch (\Exception $e) {
            Log::error('Failed to update organisasi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update organisasi: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $organisasi = MstOrganisasi::find($id);

            if (!$organisasi) {
                return $this->notFoundResponse('Organisasi not found');
            }

            $organisasi->delete();

            return $this->successResponse(null, 'Organisasi deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete organisasi', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete organisasi', 500);
        }
    }

    public function byPembina(int $pembinaGuruId): JsonResponse
    {
        try {
            $organisasi = MstOrganisasi::where('pembina_guru_id', $pembinaGuruId)->get();

            return $this->successResponse($organisasi, 'Organisasi by pembina retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve organisasi by pembina', ['pembina_guru_id' => $pembinaGuruId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve organisasi', 500);
        }
    }

    public function aktif(): JsonResponse
    {
        try {
            $organisasi = MstOrganisasi::where('status', 'aktif')->get();

            return $this->successResponse($organisasi, 'Active organisasi retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve active organisasi', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve active organisasi', 500);
        }
    }

    public function statistik(int $id): JsonResponse
    {
        try {
            $organisasi = MstOrganisasi::withCount(['anggota', 'anggotaAktif'])->find($id);

            if (!$organisasi) {
                return $this->notFoundResponse('Organisasi not found');
            }

            $statistik = [
                'organisasi' => $organisasi,
                'total_anggota' => $organisasi->anggota_count,
                'anggota_aktif' => $organisasi->anggota_aktif_count,
            ];

            return $this->successResponse($statistik, 'Statistics retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve statistics', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve statistics', 500);
        }
    }
}
