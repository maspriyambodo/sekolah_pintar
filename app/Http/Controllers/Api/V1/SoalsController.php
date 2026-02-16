<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Master\MstSoal;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SoalsController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        try {
            $query = MstSoal::with(['mapel', 'opsi']);

            if ($request->has('mapel_id')) {
                $query->where('mst_mapel_id', $request->input('mapel_id'));
            }

            if ($request->has('tipe')) {
                $query->where('tipe', $request->input('tipe'));
            }

            if ($request->has('tingkat_kesulitan')) {
                $query->where('tingkat_kesulitan', $request->input('tingkat_kesulitan'));
            }

            $perPage = (int) $request->input('per_page', 15);
            $paginator = $query->paginate($perPage);

            return $this->paginatedResponse($paginator, 'Soal retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve soal list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve soal list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $soal = MstSoal::with(['mapel', 'opsi'])->find($id);

            if (!$soal) {
                return $this->notFoundResponse('Soal not found');
            }

            return $this->successResponse($soal);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve soal', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve soal', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'mst_mapel_id' => 'required|exists:mst_mapel,id',
                'pertanyaan' => 'required',
                'tipe' => 'nullable|in:pilihan_ganda,essay',
                'tingkat_kesulitan' => 'nullable|in:mudah,sedang,sulit',
                'media_path' => 'nullable|string',
                'opsi' => 'nullable|array',
            ]);

            $soal = MstSoal::create($validated);

            if (isset($validated['opsi']) && is_array($validated['opsi'])) {
                foreach ($validated['opsi'] as $index => $opsiData) {
                    $soal->opsi()->create([
                        'teks_opsi' => $opsiData['teks_opsi'],
                        'is_jawaban' => $opsiData['is_jawaban'] ?? false,
                        'urutan' => $opsiData['urutan'] ?? chr(65 + $index),
                    ]);
                }
            }

            return $this->createdResponse($soal->load(['mapel', 'opsi']), 'Soal created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create soal', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create soal: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $soal = MstSoal::find($id);

            if (!$soal) {
                return $this->notFoundResponse('Soal not found');
            }

            $validated = $request->validate([
                'mst_mapel_id' => 'sometimes|exists:mst_mapel,id',
                'pertanyaan' => 'sometimes',
                'tipe' => 'nullable|in:pilihan_ganda,essay',
                'tingkat_kesulitan' => 'nullable|in:mudah,sedang,sulit',
                'media_path' => 'nullable|string',
            ]);

            $soal->update($validated);

            return $this->successResponse($soal->load(['mapel', 'opsi']), 'Soal updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update soal', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update soal: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $soal = MstSoal::find($id);

            if (!$soal) {
                return $this->notFoundResponse('Soal not found');
            }

            $soal->delete();

            return $this->successResponse(null, 'Soal deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete soal', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete soal', 500);
        }
    }
}