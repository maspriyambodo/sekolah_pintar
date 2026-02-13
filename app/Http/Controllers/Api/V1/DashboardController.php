<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Master\MstBkKategori;
use App\Models\Master\MstGuru;
use App\Models\Master\MstKelas;
use App\Models\Master\MstSiswa;
use App\Models\Transaction\TrxAbsensiSiswa;
use App\Models\Transaction\TrxBkKasus;
use App\Models\Transaction\TrxNilai;
use App\Models\Transaction\TrxPembayaranSpp;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get Summary Cards Data (Total KPIs)
     */
    public function summaryCards(Request $request): JsonResponse
    {
        try {
            $tahunAjaran = $request->input('tahun_ajaran');
            $kelasId = $request->input('mst_kelas_id');

            // Base query untuk siswa dengan filter
            $siswaQuery = MstSiswa::query()
                ->where('status', 'aktif');

            if ($tahunAjaran) {
                $siswaQuery->whereHas('kelas', function ($q) use ($tahunAjaran) {
                    $q->where('tahun_ajaran', $tahunAjaran);
                });
            }

            if ($kelasId) {
                $siswaQuery->where('mst_kelas_id', $kelasId);
            }

            $totalSiswaAktif = $siswaQuery->count();

            // Total Guru
            $totalGuru = MstGuru::count();

            // Total Tunggakan SPP Bulan Berjalan
            $currentMonth = now()->month;
            $currentYear = now()->year;

            $tunggakanQuery = TrxPembayaranSpp::query()
                ->where('bulan', $currentMonth)
                ->where('tahun', $currentYear)
                ->where('status', '!=', 'lunas');

            if ($kelasId) {
                $tunggakanQuery->whereHas('siswa', function ($q) use ($kelasId) {
                    $q->where('mst_kelas_id', $kelasId);
                });
            }

            if ($tahunAjaran) {
                $tunggakanQuery->whereHas('siswa.kelas', function ($q) use ($tahunAjaran) {
                    $q->where('tahun_ajaran', $tahunAjaran);
                });
            }

            $totalTunggakanSpp = $tunggakanQuery->sum('jumlah_bayar');
            $jumlahSiswaTunggakan = $tunggakanQuery->distinct('mst_siswa_id')->count('mst_siswa_id');

            // Jumlah Kasus BK yang masih 'proses'
            $bkQuery = TrxBkKasus::query()
                ->where('status', 'proses');

            if ($kelasId) {
                $bkQuery->whereHas('siswa', function ($q) use ($kelasId) {
                    $q->where('mst_kelas_id', $kelasId);
                });
            }

            if ($tahunAjaran) {
                $bkQuery->whereHas('siswa.kelas', function ($q) use ($tahunAjaran) {
                    $q->where('tahun_ajaran', $tahunAjaran);
                });
            }

            $kasusBkProses = $bkQuery->count();

            // Total Kelas (opsional untuk dashboard)
            $kelasQuery = MstKelas::query();
            if ($tahunAjaran) {
                $kelasQuery->where('tahun_ajaran', $tahunAjaran);
            }
            if ($kelasId) {
                $kelasQuery->where('id', $kelasId);
            }
            $totalKelas = $kelasQuery->count();

            return $this->successResponse([
                'total_siswa_aktif' => $totalSiswaAktif,
                'total_guru' => $totalGuru,
                'total_kelas' => $totalKelas,
                'total_tunggakan_spp' => [
                    'amount' => $totalTunggakanSpp,
                    'formatted' => 'Rp ' . number_format($totalTunggakanSpp, 0, ',', '.'),
                    'month' => $this->getNamaBulan($currentMonth),
                    'year' => $currentYear,
                    'jumlah_siswa' => $jumlahSiswaTunggakan,
                ],
                'kasus_bk_proses' => $kasusBkProses,
            ], 'Summary cards retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve summary cards: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get Financial Analytics Data
     */
    public function financialAnalytics(Request $request): JsonResponse
    {
        try {
            $tahunAjaran = $request->input('tahun_ajaran');
            $kelasId = $request->input('mst_kelas_id');

            // Tren Pendapatan SPP per bulan dalam 1 tahun terakhir
            $startDate = now()->subMonths(11)->startOfMonth();
            $endDate = now()->endOfMonth();

            $sppTrendQuery = TrxPembayaranSpp::query()
                ->select(
                    DB::raw('YEAR(tanggal_bayar) as year'),
                    DB::raw('MONTH(tanggal_bayar) as month'),
                    DB::raw('SUM(jumlah_bayar) as total_pendapatan'),
                    DB::raw('COUNT(*) as jumlah_transaksi')
                )
                ->where('status', 'lunas')
                ->whereBetween('tanggal_bayar', [$startDate, $endDate])
                ->groupBy(DB::raw('YEAR(tanggal_bayar)'), DB::raw('MONTH(tanggal_bayar)'))
                ->orderBy(DB::raw('YEAR(tanggal_bayar)'))
                ->orderBy(DB::raw('MONTH(tanggal_bayar)'));

            if ($kelasId) {
                $sppTrendQuery->whereHas('siswa', function ($q) use ($kelasId) {
                    $q->where('mst_kelas_id', $kelasId);
                });
            }

            if ($tahunAjaran) {
                $sppTrendQuery->whereHas('siswa.kelas', function ($q) use ($tahunAjaran) {
                    $q->where('tahun_ajaran', $tahunAjaran);
                });
            }

            $sppTrendData = $sppTrendQuery->get();

            // Format data untuk chart
            $months = [];
            $pendapatan = [];
            $transaksi = [];

            // Generate 12 bulan terakhir
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthKey = $date->format('Y-m');
                $months[] = $date->format('M Y');

                $data = $sppTrendData->first(function ($item) use ($date) {
                    return $item->year == $date->year && $item->month == $date->month;
                });

                $pendapatan[] = $data ? (float) $data->total_pendapatan : 0;
                $transaksi[] = $data ? (int) $data->jumlah_transaksi : 0;
            }

            // Persentase Pembayaran Lunas vs Belum Lunas (Data untuk Donut Chart)
            $currentYear = now()->year;

            $statusQuery = TrxPembayaranSpp::query()
                ->select('status', DB::raw('COUNT(*) as total'), DB::raw('SUM(jumlah_bayar) as amount'))
                ->where('tahun', $currentYear);

            if ($kelasId) {
                $statusQuery->whereHas('siswa', function ($q) use ($kelasId) {
                    $q->where('mst_kelas_id', $kelasId);
                });
            }

            if ($tahunAjaran) {
                $statusQuery->whereHas('siswa.kelas', function ($q) use ($tahunAjaran) {
                    $q->where('tahun_ajaran', $tahunAjaran);
                });
            }

            $statusData = $statusQuery->groupBy('status')->get();

            $lunasCount = $statusData->where('status', 'lunas')->first()?->total ?? 0;
            $belumLunasCount = $statusData->where('status', '!=', 'lunas')->sum('total');
            $totalCount = $lunasCount + $belumLunasCount;

            $donutData = [
                'labels' => ['Lunas', 'Belum Lunas'],
                'data' => [
                    $lunasCount,
                    $belumLunasCount,
                ],
                'percentages' => [
                    $totalCount > 0 ? round(($lunasCount / $totalCount) * 100, 2) : 0,
                    $totalCount > 0 ? round(($belumLunasCount / $totalCount) * 100, 2) : 0,
                ],
                'colors' => ['#10B981', '#EF4444'],
            ];

            // Ringkasan tahunan
            $yearlyQuery = TrxPembayaranSpp::query()
                ->where('tahun', $currentYear)
                ->where('status', 'lunas');

            if ($kelasId) {
                $yearlyQuery->whereHas('siswa', function ($q) use ($kelasId) {
                    $q->where('mst_kelas_id', $kelasId);
                });
            }

            if ($tahunAjaran) {
                $yearlyQuery->whereHas('siswa.kelas', function ($q) use ($tahunAjaran) {
                    $q->where('tahun_ajaran', $tahunAjaran);
                });
            }

            $totalPendapatanTahunIni = $yearlyQuery->sum('jumlah_bayar');

            return $this->successResponse([
                'spp_trend' => [
                    'labels' => $months,
                    'datasets' => [
                        [
                            'label' => 'Pendapatan SPP',
                            'data' => $pendapatan,
                            'formatted_data' => array_map(fn ($p) => 'Rp ' . number_format($p, 0, ',', '.'), $pendapatan),
                        ],
                        [
                            'label' => 'Jumlah Transaksi',
                            'data' => $transaksi,
                            'yAxisID' => 'y1',
                        ],
                    ],
                ],
                'payment_status_distribution' => $donutData,
                'yearly_summary' => [
                    'total_pendapatan' => $totalPendapatanTahunIni,
                    'formatted_total' => 'Rp ' . number_format($totalPendapatanTahunIni, 0, ',', '.'),
                    'year' => $currentYear,
                    'total_lunas' => $lunasCount,
                    'total_belum_lunas' => $belumLunasCount,
                ],
            ], 'Financial analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve financial analytics: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get Academic & Attendance Analytics
     */
    public function academicAttendanceAnalytics(Request $request): JsonResponse
    {
        try {
            $tahunAjaran = $request->input('tahun_ajaran');
            $kelasId = $request->input('mst_kelas_id');

            // Rata-rata tingkat kehadiran siswa harian dalam 7 hari terakhir
            $startDate = now()->subDays(6)->startOfDay();
            $endDate = now()->endOfDay();

            $absensiQuery = TrxAbsensiSiswa::query()
                ->select(
                    'tanggal',
                    'status',
                    DB::raw('COUNT(*) as total')
                )
                ->whereBetween('tanggal', [$startDate, $endDate]);

            if ($kelasId) {
                $absensiQuery->whereHas('siswa', function ($q) use ($kelasId) {
                    $q->where('mst_kelas_id', $kelasId);
                });
            }

            if ($tahunAjaran) {
                $absensiQuery->whereHas('siswa.kelas', function ($q) use ($tahunAjaran) {
                    $q->where('tahun_ajaran', $tahunAjaran);
                });
            }

            $absensiData = $absensiQuery
                ->groupBy('tanggal', 'status')
                ->orderBy('tanggal')
                ->get();

            // Format untuk chart kehadiran
            $dates = [];
            $hadirData = [];
            $izinData = [];
            $sakitData = [];
            $alphaData = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dates[] = $date->format('D, d M');

                $dayData = $absensiData->where('tanggal', $date->toDateString());

                $hadirData[] = $dayData->where('status', 'hadir')->first()?->total ?? 0;
                $izinData[] = $dayData->where('status', 'izin')->first()?->total ?? 0;
                $sakitData[] = $dayData->where('status', 'sakit')->first()?->total ?? 0;
                $alphaData[] = $dayData->where('status', 'alpha')->first()?->total ?? 0;
            }

            // Hitung rata-rata kehadiran 7 hari terakhir
            $totalHadir = array_sum($hadirData);
            $totalRecords = $totalHadir + array_sum($izinData) + array_sum($sakitData) + array_sum($alphaData);
            $rataRataKehadiran = $totalRecords > 0 ? round(($totalHadir / $totalRecords) * 100, 2) : 0;

            // Distribusi nilai ujian per kategori
            $nilaiQuery = TrxNilai::query()
                ->join('trx_ujian', 'trx_nilai.trx_ujian_id', '=', 'trx_ujian.id')
                ->select('trx_nilai.nilai');

            if ($kelasId) {
                $nilaiQuery->where('trx_ujian.mst_kelas_id', $kelasId);
            }

            if ($tahunAjaran) {
                $nilaiQuery->whereHas('ujian.kelas', function ($q) use ($tahunAjaran) {
                    $q->where('tahun_ajaran', $tahunAjaran);
                });
            }

            $nilaiData = $nilaiQuery->pluck('nilai')->map(fn ($n) => (float) $n);

            // Kategori nilai
            $kategoriNilai = [
                'sangat_baik' => ['min' => 90, 'max' => 100, 'label' => 'Sangat Baik (90-100)', 'count' => 0],
                'baik' => ['min' => 80, 'max' => 89, 'label' => 'Baik (80-89)', 'count' => 0],
                'cukup' => ['min' => 70, 'max' => 79, 'label' => 'Cukup (70-79)', 'count' => 0],
                'kurang' => ['min' => 60, 'max' => 69, 'label' => 'Kurang (60-69)', 'count' => 0],
                'sangat_kurang' => ['min' => 0, 'max' => 59, 'label' => 'Sangat Kurang (<60)', 'count' => 0],
            ];

            foreach ($nilaiData as $nilai) {
                if ($nilai >= 90) {
                    $kategoriNilai['sangat_baik']['count']++;
                } elseif ($nilai >= 80) {
                    $kategoriNilai['baik']['count']++;
                } elseif ($nilai >= 70) {
                    $kategoriNilai['cukup']['count']++;
                } elseif ($nilai >= 60) {
                    $kategoriNilai['kurang']['count']++;
                } else {
                    $kategoriNilai['sangat_kurang']['count']++;
                }
            }

            $totalNilai = count($nilaiData);
            $nilaiDistribution = [
                'labels' => array_column($kategoriNilai, 'label'),
                'data' => array_column($kategoriNilai, 'count'),
                'percentages' => array_map(
                    fn ($count) => $totalNilai > 0 ? round(($count / $totalNilai) * 100, 2) : 0,
                    array_column($kategoriNilai, 'count')
                ),
                'colors' => ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#6B7280'],
            ];

            // Rata-rata nilai keseluruhan
            $rataRataNilai = $nilaiData->avg() ?? 0;

            return $this->successResponse([
                'attendance_7_days' => [
                    'labels' => $dates,
                    'datasets' => [
                        ['label' => 'Hadir', 'data' => $hadirData, 'color' => '#10B981'],
                        ['label' => 'Izin', 'data' => $izinData, 'color' => '#3B82F6'],
                        ['label' => 'Sakit', 'data' => $sakitData, 'color' => '#F59E0B'],
                        ['label' => 'Alpha', 'data' => $alphaData, 'color' => '#EF4444'],
                    ],
                ],
                'attendance_summary' => [
                    'rata_rata_kehadiran' => $rataRataKehadiran,
                    'total_hadir_7_hari' => $totalHadir,
                    'total_records' => $totalRecords,
                ],
                'nilai_distribution' => $nilaiDistribution,
                'nilai_summary' => [
                    'rata_rata' => round($rataRataNilai, 2),
                    'total_ujian' => $totalNilai,
                    'nilai_tertinggi' => $nilaiData->max() ?? 0,
                    'nilai_terendah' => $nilaiData->min() ?? 0,
                ],
            ], 'Academic and attendance analytics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve academic analytics: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get Counseling (BK) Insights
     */
    public function counselingInsights(Request $request): JsonResponse
    {
        try {
            $tahunAjaran = $request->input('tahun_ajaran');
            $kelasId = $request->input('mst_kelas_id');

            // Top 5 kategori kasus yang paling sering muncul
            $topKategoriQuery = TrxBkKasus::query()
                ->select('mst_bk_kategori_id', DB::raw('COUNT(*) as total_kasus'))
                ->with('kategori:id,nama')
                ->groupBy('mst_bk_kategori_id')
                ->orderByDesc('total_kasus')
                ->limit(5);

            if ($kelasId) {
                $topKategoriQuery->whereHas('siswa', function ($q) use ($kelasId) {
                    $q->where('mst_kelas_id', $kelasId);
                });
            }

            if ($tahunAjaran) {
                $topKategoriQuery->whereHas('siswa.kelas', function ($q) use ($tahunAjaran) {
                    $q->where('tahun_ajaran', $tahunAjaran);
                });
            }

            $topKategori = $topKategoriQuery->get();

            $topKategoriData = [
                'labels' => $topKategori->map(fn ($item) => $item->kategori?->nama ?? 'Tidak Diketahui')->toArray(),
                'data' => $topKategori->map(fn ($item) => $item->total_kasus)->toArray(),
            ];

            // Status penyelesaian kasus (Ratio Selesai vs Total)
            $statusQuery = TrxBkKasus::query()
                ->select('status', DB::raw('COUNT(*) as total'));

            if ($kelasId) {
                $statusQuery->whereHas('siswa', function ($q) use ($kelasId) {
                    $q->where('mst_kelas_id', $kelasId);
                });
            }

            if ($tahunAjaran) {
                $statusQuery->whereHas('siswa.kelas', function ($q) use ($tahunAjaran) {
                    $q->where('tahun_ajaran', $tahunAjaran);
                });
            }

            $statusData = $statusQuery->groupBy('status')->get();

            $selesai = $statusData->where('status', 'selesai')->first()?->total ?? 0;
            $proses = $statusData->where('status', 'proses')->first()?->total ?? 0;
            $dibuka = $statusData->where('status', 'dibuka')->first()?->total ?? 0;
            $dirujuk = $statusData->where('status', 'dirujuk')->first()?->total ?? 0;
            $totalKasus = $selesai + $proses + $dibuka + $dirujuk;

            $statusDistribution = [
                'labels' => ['Selesai', 'Proses', 'Dibuka', 'Dirujuk'],
                'data' => [$selesai, $proses, $dibuka, $dirujuk],
                'percentages' => [
                    $totalKasus > 0 ? round(($selesai / $totalKasus) * 100, 2) : 0,
                    $totalKasus > 0 ? round(($proses / $totalKasus) * 100, 2) : 0,
                    $totalKasus > 0 ? round(($dibuka / $totalKasus) * 100, 2) : 0,
                    $totalKasus > 0 ? round(($dirujuk / $totalKasus) * 100, 2) : 0,
                ],
                'colors' => ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
            ];

            // Kasus per bulan dalam tahun ini
            $currentYear = now()->year;
            $monthlyQuery = TrxBkKasus::query()
                ->select(
                    DB::raw('MONTH(tanggal_mulai) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereYear('tanggal_mulai', $currentYear);

            if ($kelasId) {
                $monthlyQuery->whereHas('siswa', function ($q) use ($kelasId) {
                    $q->where('mst_kelas_id', $kelasId);
                });
            }

            if ($tahunAjaran) {
                $monthlyQuery->whereHas('siswa.kelas', function ($q) use ($tahunAjaran) {
                    $q->where('tahun_ajaran', $tahunAjaran);
                });
            }

            $monthlyData = $monthlyQuery
                ->groupBy(DB::raw('MONTH(tanggal_mulai)'))
                ->orderBy(DB::raw('MONTH(tanggal_mulai)'))
                ->get();

            $monthlyLabels = [];
            $monthlyCounts = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthlyLabels[] = $this->getNamaBulan($i);
                $monthlyCounts[] = $monthlyData->where('month', $i)->first()?->total ?? 0;
            }

            return $this->successResponse([
                'top_kategori_kasus' => $topKategoriData,
                'status_penyelesaian' => $statusDistribution,
                'kasus_per_bulan' => [
                    'labels' => $monthlyLabels,
                    'data' => $monthlyCounts,
                ],
                'ringkasan' => [
                    'total_kasus' => $totalKasus,
                    'kasus_selesai' => $selesai,
                    'kasus_proses' => $proses,
                    'kasus_dibuka' => $dibuka,
                    'kasus_dirujuk' => $dirujuk,
                    'persentase_penyelesaian' => $totalKasus > 0 ? round(($selesai / $totalKasus) * 100, 2) : 0,
                ],
            ], 'Counseling insights retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve counseling insights: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get Complete Dashboard Data (All in one)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Menggabungkan semua data dashboard
            $summaryCards = $this->summaryCards($request)->getData(true)['data'] ?? [];
            $financial = $this->financialAnalytics($request)->getData(true)['data'] ?? [];
            $academic = $this->academicAttendanceAnalytics($request)->getData(true)['data'] ?? [];
            $counseling = $this->counselingInsights($request)->getData(true)['data'] ?? [];

            return $this->successResponse([
                'summary_cards' => $summaryCards,
                'financial' => $financial,
                'academic_attendance' => $academic,
                'counseling' => $counseling,
                'filters_applied' => [
                    'tahun_ajaran' => $request->input('tahun_ajaran'),
                    'mst_kelas_id' => $request->input('mst_kelas_id'),
                ],
                'generated_at' => now()->toIso8601String(),
            ], 'Dashboard data retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve dashboard data: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Helper method untuk mendapatkan nama bulan
     */
    private function getNamaBulan(int $bulan): string
    {
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $namaBulan[$bulan] ?? '-';
    }
}
