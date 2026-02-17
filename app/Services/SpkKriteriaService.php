<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Spk\SpkKriteria;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpkKriteriaService
{
    public function getAllKriteria(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = SpkKriteria::query();

        if (!empty($filters['tipe'])) {
            $query->where('tipe', $filters['tipe']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('kode_kriteria', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('nama_kriteria', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('kode_kriteria', 'asc')->cursorPaginate($perPage);
    }

    public function getKriteriaById(int $id): ?SpkKriteria
    {
        return SpkKriteria::find($id);
    }

    public function createKriteria(array $data): SpkKriteria
    {
        return DB::transaction(function () use ($data) {
            $kriteria = SpkKriteria::create([
                'kode_kriteria' => $data['kode_kriteria'],
                'nama_kriteria' => $data['nama_kriteria'],
                'bobot' => $data['bobot'],
                'tipe' => $data['tipe'] ?? 'benefit',
            ]);

            Log::info('SPK Kriteria created', ['kriteria_id' => $kriteria->id]);
            return $kriteria;
        });
    }

    public function updateKriteria(int $id, array $data): SpkKriteria
    {
        return DB::transaction(function () use ($id, $data) {
            $kriteria = SpkKriteria::findOrFail($id);

            $kriteria->update([
                'kode_kriteria' => $data['kode_kriteria'] ?? $kriteria->kode_kriteria,
                'nama_kriteria' => $data['nama_kriteria'] ?? $kriteria->nama_kriteria,
                'bobot' => $data['bobot'] ?? $kriteria->bobot,
                'tipe' => $data['tipe'] ?? $kriteria->tipe,
            ]);

            Log::info('SPK Kriteria updated', ['kriteria_id' => $id]);
            return $kriteria;
        });
    }

    public function deleteKriteria(int $id): bool
    {
        $kriteria = SpkKriteria::find($id);
        if (!$kriteria) {
            return false;
        }

        $result = $kriteria->delete();
        Log::info('SPK Kriteria deleted', ['kriteria_id' => $id]);
        return $result;
    }

    public function getTotalBobot(): float
    {
        return (float) SpkKriteria::sum('bobot');
    }
}
