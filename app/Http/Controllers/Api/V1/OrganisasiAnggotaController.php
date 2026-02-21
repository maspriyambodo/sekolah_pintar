<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaction\TrxOrganisasiAnggota;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrganisasiAnggotaController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        try {
            $query = TrxOrganisasiAnggota::with(['organisasi', 'siswa', 'jabatan']);

            if ($request->has('organisasi_id')) {
                $query->where('organisasi_id', $request->input('organisasi_id'));
            }

            if ($request->has('siswa_id')) {
                $query->where('siswa_id', $request->input('siswa_id'));
            }

            if ($request->has('jabatan_id')) {
                $query->where('jabatan_id', $request->input('jabatan_id'));
            }

            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            $perPage = (int) $request->input('per_page', 15);
            $paginator = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return $this->paginatedResponse($paginator, 'Organisasi anggota retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve organisasi anggota list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve organisasi anggota list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $anggota = TrxOrganisasiAnggota::with(['organisasi', 'siswa', 'jabatan'])->find($id);

            if (!$anggota) {
                return $this->notFoundResponse('Organisasi anggota not found');
            }

            return $this->successResponse($anggota);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve organisasi anggota', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve organisasi anggota', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'organisasi_id' => 'required|integer|exists:mst_organisasi,id',
                'siswa_id' => 'required|integer|exists:mst_siswa,id',
                'jabatan_id' => 'required|integer|exists:mst_organisasi_jabatan,id',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
                'status' => 'nullable|in:aktif,nonaktif',
            ]);

            // Check if siswa is already in organisasi
            $exists = TrxOrganisasiAnggota::where('organisasi_id', $validated['organisasi_id'])
                ->where('siswa_id', $validated['siswa_id'])
                ->exists();

            if ($exists) {
                return $this->errorResponse('Siswa is already a member of this organisasi', 422);
            }

            $anggota = TrxOrganisasiAnggota::create($validated);

            return $this->createdResponse($anggota, 'Organisasi anggota created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create organisasi anggota', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create organisasi anggota: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'jabatan_id' => 'sometimes|integer|exists:mst_organisasi_jabatan,id',
                'tanggal_mulai' => 'sometimes|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
                'status' => 'nullable|in:aktif,nonaktif',
            ]);

            $anggota = TrxOrganisasiAnggota::findOrFail($id);
            $anggota->update($validated);

            return $this->successResponse($anggota, 'Organisasi anggota updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Organisasi anggota not found');
        } catch (\Exception $e) {
            Log::error('Failed to update organisasi anggota', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update organisasi anggota: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $anggota = TrxOrganisasiAnggota::find($id);

            if (!$anggota) {
                return $this->notFoundResponse('Organisasi anggota not found');
            }

            $anggota->delete();

            return $this->successResponse(null, 'Organisasi anggota deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete organisasi anggota', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete organisasi anggota', 500);
        }
    }

    public function byOrganisasi(int $organisasiId): JsonResponse
    {
        try {
            $anggota = TrxOrganisasiAnggota::with(['siswa', 'jabatan'])
                ->where('organisasi_id', $organisasiId)
                ->get();

            return $this->successResponse($anggota, 'Organisasi anggota by organisasi retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve organisasi anggota by organisasi', ['organisasi_id' => $organisasiId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve organisasi anggota', 500);
        }
    }

    public function bySiswa(int $siswaId): JsonResponse
    {
        try {
            $anggota = TrxOrganisasiAnggota::with(['organisasi', 'jabatan'])
                ->where('siswa_id', $siswaId)
                ->get();

            return $this->successResponse($anggota, 'Organisasi anggota by siswa retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve organisasi anggota by siswa', ['siswa_id' => $siswaId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve organisasi anggota', 500);
        }
    }

    public function aktif(): JsonResponse
    {
        try {
            $anggota = TrxOrganisasiAnggota::with(['organisasi', 'siswa', 'jabatan'])
                ->where('status', 'aktif')
                ->get();

            return $this->successResponse($anggota, 'Active organisasi anggota retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve active organisasi anggota', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve organisasi anggota', 500);
        }
    }
}
