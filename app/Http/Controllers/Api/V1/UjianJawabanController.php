<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaction\TrxUjianJawaban;
use App\Models\Transaction\TrxUjianUser;
use App\Models\Master\MstSoalOpsi;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UjianJawabanController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        try {
            $query = TrxUjianJawaban::with(['ujianUser', 'soal', 'opsi']);

            if ($request->has('trx_ujian_user_id')) {
                $query->where('trx_ujian_user_id', $request->input('trx_ujian_user_id'));
            }

            if ($request->has('mst_soal_id')) {
                $query->where('mst_soal_id', $request->input('mst_soal_id'));
            }

            $perPage = (int) $request->input('per_page', 15);
            $paginator = $query->paginate($perPage);

            return $this->paginatedResponse($paginator, 'Jawaban retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve jawaban list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve jawaban list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $jawaban = TrxUjianJawaban::with(['ujianUser', 'soal', 'opsi'])->find($id);

            if (!$jawaban) {
                return $this->notFoundResponse('Jawaban not found');
            }

            return $this->successResponse($jawaban);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve jawaban', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve jawaban', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'trx_ujian_user_id' => 'required|exists:trx_ujian_user,id',
                'mst_soal_id' => 'required|exists:mst_soal,id',
                'mst_soal_opsi_id' => 'nullable|exists:mst_soal_opsi,id',
                'jawaban_teks' => 'nullable',
                'ragu_ragu' => 'nullable|boolean',
            ]);

            // Check if ujian is in progress
            $ujianUser = TrxUjianUser::find($validated['trx_ujian_user_id']);
            if (!$ujianUser || $ujianUser->status !== TrxUjianUser::STATUS_MENGERJAKAN) {
                return $this->errorResponse('Ujian is not in progress', 422);
            }

            // Check if answer already exists for this question
            $existing = TrxUjianJawaban::where('trx_ujian_user_id', $validated['trx_ujian_user_id'])
                ->where('mst_soal_id', $validated['mst_soal_id'])
                ->first();

            if ($existing) {
                // Update existing answer
                $existing->update($validated);
                $jawaban = $existing->fresh();
            } else {
                // Create new answer
                $jawaban = TrxUjianJawaban::create($validated);
            }

            // Check if answer is correct for multiple choice
            if (isset($validated['mst_soal_opsi_id'])) {
                $opsi = MstSoalOpsi::find($validated['mst_soal_opsi_id']);
                if ($opsi && $opsi->is_jawaban) {
                    $jawaban->update(['is_benar' => true]);
                }
            }

            return $this->createdResponse($jawaban->load(['soal', 'opsi']), 'Jawaban saved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to save jawaban', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to save jawaban: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $jawaban = TrxUjianJawaban::find($id);

            if (!$jawaban) {
                return $this->notFoundResponse('Jawaban not found');
            }

            $validated = $request->validate([
                'mst_soal_opsi_id' => 'nullable|exists:mst_soal_opsi,id',
                'jawaban_teks' => 'nullable',
                'is_benar' => 'nullable|boolean',
                'ragu_ragu' => 'nullable|boolean',
            ]);

            $jawaban->update($validated);

            return $this->successResponse($jawaban, 'Jawaban updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update jawaban', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update jawaban: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $jawaban = TrxUjianJawaban::find($id);

            if (!$jawaban) {
                return $this->notFoundResponse('Jawaban not found');
            }

            $jawaban->delete();

            return $this->successResponse(null, 'Jawaban deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete jawaban', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete jawaban', 500);
        }
    }
}