<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaction\TrxUjianUser;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UjianUserController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        try {
            $query = TrxUjianUser::with(['ujian', 'siswa', 'jawaban']);

            if ($request->has('trx_ujian_id')) {
                $query->where('trx_ujian_id', $request->input('trx_ujian_id'));
            }

            if ($request->has('mst_siswa_id')) {
                $query->where('mst_siswa_id', $request->input('mst_siswa_id'));
            }

            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            $perPage = (int) $request->input('per_page', 15);
            $paginator = $query->paginate($perPage);

            return $this->paginatedResponse($paginator, 'Ujian user retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ujian user list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ujian user list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $ujianUser = TrxUjianUser::with(['ujian', 'siswa', 'jawaban.soal', 'jawaban.opsi'])->find($id);

            if (!$ujianUser) {
                return $this->notFoundResponse('Ujian user not found');
            }

            return $this->successResponse($ujianUser);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ujian user', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve ujian user', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'trx_ujian_id' => 'required|exists:trx_ujian,id',
                'mst_siswa_id' => 'required|exists:mst_siswa,id',
            ]);

            $existing = TrxUjianUser::where('trx_ujian_id', $validated['trx_ujian_id'])
                ->where('mst_siswa_id', $validated['mst_siswa_id'])
                ->first();

            if ($existing) {
                return $this->errorResponse('Siswa already registered for this ujian', 422);
            }

            $ujianUser = TrxUjianUser::create([
                ...$validated,
                'status' => TrxUjianUser::STATUS_BELUM_MULAI,
            ]);

            return $this->createdResponse($ujianUser->load(['ujian', 'siswa']), 'Ujian user created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create ujian user', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create ujian user: ' . $e->getMessage(), 500);
        }
    }

    public function mulaiUjian(int $id): JsonResponse
    {
        try {
            $ujianUser = TrxUjianUser::find($id);

            if (!$ujianUser) {
                return $this->notFoundResponse('Ujian user not found');
            }

            if ($ujianUser->status !== TrxUjianUser::STATUS_BELUM_MULAI) {
                return $this->errorResponse('Ujian already started or finished', 422);
            }

            $ujianUser->update([
                'waktu_mulai' => now(),
                'status' => TrxUjianUser::STATUS_MENGERJAKAN,
            ]);

            return $this->successResponse($ujianUser, 'Ujian started successfully');
        } catch (\Exception $e) {
            Log::error('Failed to start ujian', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to start ujian', 500);
        }
    }

    public function selesaikanUjian(int $id): JsonResponse
    {
        try {
            $ujianUser = TrxUjianUser::with('jawaban')->find($id);

            if (!$ujianUser) {
                return $this->notFoundResponse('Ujian user not found');
            }

            if ($ujianUser->status !== TrxUjianUser::STATUS_MENGERJAKAN) {
                return $this->errorResponse('Ujian not in progress', 422);
            }

            // Calculate score
            $benar = 0;
            $salah = 0;

            foreach ($ujianUser->jawaban as $jawaban) {
                if ($jawaban->is_benar) {
                    $benar++;
                } else {
                    $salah++;
                }
            }

            $totalSoal = $ujianUser->jawaban->count();
            $nilaiAkhir = $totalSoal > 0 ? (($benar / $totalSoal) * 100) : 0;

            $ujianUser->update([
                'waktu_selesai' => now(),
                'status' => TrxUjianUser::STATUS_SELESAI,
                'total_benar' => $benar,
                'total_salah' => $salah,
                'nilai_akhir' => round($nilaiAkhir, 2),
            ]);

            return $this->successResponse($ujianUser, 'Ujian completed successfully');
        } catch (\Exception $e) {
            Log::error('Failed to complete ujian', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to complete ujian', 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $ujianUser = TrxUjianUser::find($id);

            if (!$ujianUser) {
                return $this->notFoundResponse('Ujian user not found');
            }

            $ujianUser->delete();

            return $this->successResponse(null, 'Ujian user deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete ujian user', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete ujian user', 500);
        }
    }
}