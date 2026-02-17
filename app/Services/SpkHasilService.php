<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Spk\SpkHasil;
use App\Models\Spk\SpkKriteria;
use App\Models\Spk\SpkPenilaian;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpkHasilService
{
    public function getAllHasil(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = SpkHasil::query()->with(['siswa']);

        if (!empty($filters['periode'])) {
            $query->where('periode', $filters['periode']);
        }

        return $query->orderBy('peringkat', 'asc')->cursorPaginate($perPage);
    }

    public function getHasilById(int $id): ?SpkHasil
    {
        return SpkHasil::with(['siswa'])->find($id);
    }

    public function getHasilByPeriode(string $periode): Collection
    {
        return SpkHasil::with(['siswa'])
            ->where('periode', $periode)
            ->orderBy('peringkat', 'asc')
            ->get();
    }

    public function getHasilBySiswa(int $siswaId): Collection
    {
        return SpkHasil::where('mst_siswa_id', $siswaId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function calculateHasil(string $periode, array $siswaIds): Collection
    {
        return DB::transaction(function () use ($periode, $siswaIds) {
            // Get all criteria
            $kriterias = SpkKriteria::all();
            if ($kriterias->isEmpty()) {
                throw new \Exception('Tidak ada kriteria yang ditemukan');
            }

            // Get min/max values for normalization
            $minMax = SpkPenilaian::whereIn('mst_siswa_id', $siswaIds)
                ->where('tahun_ajaran', $periode)
                ->select('spk_kriteria_id', DB::raw('MIN(nilai) as min_nilai'), DB::raw('MAX(nilai) as max_nilai'))
                ->groupBy('spk_kriteria_id')
                ->get()
                ->keyBy('spk_kriteria_id');

            // Calculate total score for each student
            $results = [];
            foreach ($siswaIds as $siswaId) {
                $totalSkor = 0;
                
                foreach ($kriterias as $kriteria) {
                    $penilaian = SpkPenilaian::where('mst_siswa_id', $siswaId)
                        ->where('spk_kriteria_id', $kriteria->id)
                        ->where('tahun_ajaran', $periode)
                        ->first();

                    $nilai = $penilaian ? (float) $penilaian->nilai : 0;
                    $bobot = (float) $kriteria->bobot;

                    // Normalization (Simple Weighted Sum Method)
                    $minMaxKriteria = $minMax->get($kriteria->id);
                    if ($minMaxKriteria && $minMaxKriteria->max_nilai != $minMaxKriteria->min_nilai) {
                        if ($kriteria->tipe === 'benefit') {
                            $normalized = ($nilai - $minMaxKriteria->min_nilai) / ($minMaxKriteria->max_nilai - $minMaxKriteria->min_nilai);
                        } else {
                            // Cost type - invert
                            $normalized = ($minMaxKriteria->max_nilai - $nilai) / ($minMaxKriteria->max_nilai - $minMaxKriteria->min_nilai);
                        }
                    } else {
                        $normalized = 1; // If all values are the same
                    }

                    $totalSkor += $normalized * $bobot;
                }

                $results[] = [
                    'mst_siswa_id' => $siswaId,
                    'total_skor' => $totalSkor,
                ];
            }

            // Sort by total score descending
            usort($results, function ($a, $b) {
                return $b['total_skor'] <=> $a['total_skor'];
            });

            // Delete old results for this period
            SpkHasil::where('periode', $periode)->delete();

            // Save new results
            $savedResults = [];
            $rank = 1;
            foreach ($results as $result) {
                $hasil = SpkHasil::create([
                    'mst_siswa_id' => $result['mst_siswa_id'],
                    'total_skor' => $result['total_skor'],
                    'peringkat' => $rank,
                    'periode' => $periode,
                ]);
                $savedResults[] = $hasil;
                $rank++;
            }

            Log::info('SPK Hasil calculated', ['periode' => $periode, 'count' => count($savedResults)]);
            return collect($savedResults);
        });
    }

    public function createOrUpdateHasil(array $data): SpkHasil
    {
        return DB::transaction(function () use ($data) {
            $existing = SpkHasil::where('mst_siswa_id', $data['mst_siswa_id'])
                ->where('periode', $data['periode'])
                ->first();

            if ($existing) {
                $existing->update([
                    'total_skor' => $data['total_skor'],
                    'peringkat' => $data['peringkat'],
                ]);
                Log::info('SPK Hasil updated', ['hasil_id' => $existing->id]);
                return $existing;
            }

            $hasil = SpkHasil::create([
                'mst_siswa_id' => $data['mst_siswa_id'],
                'total_skor' => $data['total_skor'],
                'peringkat' => $data['peringkat'],
                'periode' => $data['periode'],
            ]);

            Log::info('SPK Hasil created', ['hasil_id' => $hasil->id]);
            return $hasil;
        });
    }

    public function deleteHasil(int $id): bool
    {
        $hasil = SpkHasil::find($id);
        if (!$hasil) {
            return false;
        }

        $result = $hasil->delete();
        Log::info('SPK Hasil deleted', ['hasil_id' => $id]);
        return $result;
    }

    public function deleteHasilByPeriode(string $periode): int
    {
        $result = SpkHasil::where('periode', $periode)->delete();
        Log::info('SPK Hasil deleted by periode', ['periode' => $periode, 'count' => $result]);
        return $result;
    }
}
