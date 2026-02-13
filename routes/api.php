<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AbsensiGuruController;
use App\Http\Controllers\Api\V1\AbsensiSiswaController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BkJenisController;
use App\Http\Controllers\Api\V1\BkKasusController;
use App\Http\Controllers\Api\V1\BukuController;
use App\Http\Controllers\Api\V1\FileUploadController;
use App\Http\Controllers\Api\V1\GuruController;
use App\Http\Controllers\Api\V1\KelasController;
use App\Http\Controllers\Api\V1\MapelController;
use App\Http\Controllers\Api\V1\NilaiController;
use App\Http\Controllers\Api\V1\PeminjamanBukuController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\RankingController;
use App\Http\Controllers\Api\V1\RaporController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\SiswaController;
use App\Http\Controllers\Api\V1\UjianController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\WaliController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API Version 1
Route::prefix('v1')->group(function () {

    // Public routes
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('api.v1.auth.login');
        Route::post('register', [AuthController::class, 'register'])->name('api.v1.auth.register');
        Route::post('refresh', [AuthController::class, 'refresh'])->name('api.v1.auth.refresh');
    });

    // Protected routes
    Route::middleware(['auth:api'])->group(function () {

        // Auth routes
        Route::prefix('auth')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('api.v1.auth.logout');
            Route::get('me', [AuthController::class, 'me'])->name('api.v1.auth.me');
        });

        // File Upload routes
        Route::prefix('files')->group(function () {
            Route::post('upload', [FileUploadController::class, 'upload'])->name('api.v1.files.upload');
            Route::post('presigned-url', [FileUploadController::class, 'getPresignedUrl'])->name('api.v1.files.presigned-url');
            Route::delete('delete', [FileUploadController::class, 'delete'])->name('api.v1.files.delete');
        });

        // Master Data Routes - Admin & Guru access
        Route::middleware([RoleMiddleware::class . ':admin,guru'])->group(function () {
            // Kelas routes
            Route::prefix('kelas')->group(function () {
                Route::get('/', [KelasController::class, 'index'])->name('api.v1.kelas.index');
                Route::post('/', [KelasController::class, 'store'])->name('api.v1.kelas.store');
                Route::get('/{id}', [KelasController::class, 'show'])->name('api.v1.kelas.show');
                Route::put('/{id}', [KelasController::class, 'update'])->name('api.v1.kelas.update');
                Route::delete('/{id}', [KelasController::class, 'destroy'])->name('api.v1.kelas.destroy');
                Route::get('/{id}/siswa', [KelasController::class, 'siswa'])->name('api.v1.kelas.siswa');
                Route::get('/tingkat/{tingkat}', [KelasController::class, 'byTingkat'])->name('api.v1.kelas.by-tingkat');
            });

            // Mapel routes
            Route::prefix('mapel')->group(function () {
                Route::get('/', [MapelController::class, 'index'])->name('api.v1.mapel.index');
                Route::post('/', [MapelController::class, 'store'])->name('api.v1.mapel.store');
                Route::get('/{id}', [MapelController::class, 'show'])->name('api.v1.mapel.show');
                Route::put('/{id}', [MapelController::class, 'update'])->name('api.v1.mapel.update');
                Route::delete('/{id}', [MapelController::class, 'destroy'])->name('api.v1.mapel.destroy');
                Route::get('/{id}/gurus', [MapelController::class, 'gurus'])->name('api.v1.mapel.gurus');
            });

            // Guru routes
            Route::prefix('guru')->group(function () {
                Route::get('/', [GuruController::class, 'index'])->name('api.v1.guru.index');
                Route::post('/', [GuruController::class, 'store'])->name('api.v1.guru.store');
                Route::get('/{id}', [GuruController::class, 'show'])->name('api.v1.guru.show');
                Route::put('/{id}', [GuruController::class, 'update'])->name('api.v1.guru.update');
                Route::delete('/{id}', [GuruController::class, 'destroy'])->name('api.v1.guru.destroy');
                Route::get('/mapel/{mapelId}', [GuruController::class, 'byMapel'])->name('api.v1.guru.by-mapel');
                Route::get('/{id}/absensi-summary', [GuruController::class, 'absensiSummary'])->name('api.v1.guru.absensi-summary');
            });

            // Siswa routes
            Route::prefix('siswa')->group(function () {
                Route::get('/', [SiswaController::class, 'index'])->name('api.v1.siswa.index');
                Route::post('/', [SiswaController::class, 'store'])->name('api.v1.siswa.store');
                Route::get('/kelas/{kelasId}', [SiswaController::class, 'byKelas'])->name('api.v1.siswa.by-kelas');
                Route::get('/{id}', [SiswaController::class, 'show'])->name('api.v1.siswa.show');
                Route::put('/{id}', [SiswaController::class, 'update'])->name('api.v1.siswa.update');
                Route::delete('/{id}', [SiswaController::class, 'destroy'])->name('api.v1.siswa.destroy');
                Route::get('/{id}/absensi-summary', [SiswaController::class, 'absensiSummary'])->name('api.v1.siswa.absensi-summary');
                Route::post('/{id}/naik-kelas', [SiswaController::class, 'naikKelas'])->name('api.v1.siswa.naik-kelas');
                Route::post('/{id}/lulus', [SiswaController::class, 'lulus'])->name('api.v1.siswa.lulus');
            });

            // Wali routes
            Route::prefix('wali')->group(function () {
                Route::get('/', [WaliController::class, 'index'])->name('api.v1.wali.index');
                Route::post('/', [WaliController::class, 'store'])->name('api.v1.wali.store');
                Route::get('/{id}', [WaliController::class, 'show'])->name('api.v1.wali.show');
                Route::put('/{id}', [WaliController::class, 'update'])->name('api.v1.wali.update');
                Route::delete('/{id}', [WaliController::class, 'destroy'])->name('api.v1.wali.destroy');
                Route::get('/{id}/siswa', [WaliController::class, 'siswa'])->name('api.v1.wali.siswa');
            });
        });

        // Transaction Routes - All authenticated users
        // Absensi Guru
        Route::prefix('absensi-guru')->group(function () {
            Route::get('/', [AbsensiGuruController::class, 'index'])->name('api.v1.absensi-guru.index');
            Route::post('/', [AbsensiGuruController::class, 'store'])->name('api.v1.absensi-guru.store');
            Route::get('/{id}', [AbsensiGuruController::class, 'show'])->name('api.v1.absensi-guru.show');
            Route::put('/{id}', [AbsensiGuruController::class, 'update'])->name('api.v1.absensi-guru.update');
            Route::delete('/{id}', [AbsensiGuruController::class, 'destroy'])->name('api.v1.absensi-guru.destroy');
            Route::get('/guru/{guruId}', [AbsensiGuruController::class, 'byGuru'])->name('api.v1.absensi-guru.by-guru');
            Route::get('/guru/{guruId}/summary', [AbsensiGuruController::class, 'summary'])->name('api.v1.absensi-guru.summary');
        });

        // Absensi Siswa
        Route::prefix('absensi-siswa')->group(function () {
            Route::get('/', [AbsensiSiswaController::class, 'index'])->name('api.v1.absensi-siswa.index');
            Route::post('/', [AbsensiSiswaController::class, 'store'])->name('api.v1.absensi-siswa.store');
            Route::get('/{id}', [AbsensiSiswaController::class, 'show'])->name('api.v1.absensi-siswa.show');
            Route::put('/{id}', [AbsensiSiswaController::class, 'update'])->name('api.v1.absensi-siswa.update');
            Route::delete('/{id}', [AbsensiSiswaController::class, 'destroy'])->name('api.v1.absensi-siswa.destroy');
            Route::get('/siswa/{siswaId}', [AbsensiSiswaController::class, 'bySiswa'])->name('api.v1.absensi-siswa.by-siswa');
            Route::post('/date-range', [AbsensiSiswaController::class, 'byDateRange'])->name('api.v1.absensi-siswa.by-date-range');
            Route::get('/siswa/{siswaId}/summary', [AbsensiSiswaController::class, 'summary'])->name('api.v1.absensi-siswa.summary');
        });

        // Bimbingan Konseling Routes
        Route::prefix('bk')->group(function () {
            // BK Jenis
            Route::prefix('jenis')->group(function () {
                Route::get('/', [BkJenisController::class, 'index'])->name('api.v1.bk-jenis.index');
                Route::post('/', [BkJenisController::class, 'store'])->name('api.v1.bk-jenis.store');
                Route::get('/{id}', [BkJenisController::class, 'show'])->name('api.v1.bk-jenis.show');
                Route::put('/{id}', [BkJenisController::class, 'update'])->name('api.v1.bk-jenis.update');
                Route::delete('/{id}', [BkJenisController::class, 'destroy'])->name('api.v1.bk-jenis.destroy');
            });

            // BK Kasus
            Route::prefix('kasus')->group(function () {
                Route::get('/', [BkKasusController::class, 'index'])->name('api.v1.bk-kasus.index');
                Route::post('/', [BkKasusController::class, 'store'])->name('api.v1.bk-kasus.store');
                Route::get('/{id}', [BkKasusController::class, 'show'])->name('api.v1.bk-kasus.show');
                Route::put('/{id}', [BkKasusController::class, 'update'])->name('api.v1.bk-kasus.update');
                Route::delete('/{id}', [BkKasusController::class, 'destroy'])->name('api.v1.bk-kasus.destroy');
                Route::get('/siswa/{siswaId}', [BkKasusController::class, 'bySiswa'])->name('api.v1.bk-kasus.by-siswa');
            });
        });

        // Perpustakaan Routes
        Route::prefix('perpustakaan')->group(function () {
            // Buku
            Route::prefix('buku')->group(function () {
                Route::get('/', [BukuController::class, 'index'])->name('api.v1.buku.index');
                Route::post('/', [BukuController::class, 'store'])->name('api.v1.buku.store');
                Route::get('/available', [BukuController::class, 'available'])->name('api.v1.buku.available');
                Route::get('/{id}', [BukuController::class, 'show'])->name('api.v1.buku.show');
                Route::put('/{id}', [BukuController::class, 'update'])->name('api.v1.buku.update');
                Route::delete('/{id}', [BukuController::class, 'destroy'])->name('api.v1.buku.destroy');
                Route::get('/{id}/peminjaman', [BukuController::class, 'peminjaman'])->name('api.v1.buku.peminjaman');
            });

            // Peminjaman
            Route::prefix('peminjaman')->group(function () {
                Route::get('/', [PeminjamanBukuController::class, 'index'])->name('api.v1.peminjaman.index');
                Route::post('/', [PeminjamanBukuController::class, 'store'])->name('api.v1.peminjaman.store');
                Route::get('/overdue', [PeminjamanBukuController::class, 'overdue'])->name('api.v1.peminjaman.overdue');
                Route::get('/{id}', [PeminjamanBukuController::class, 'show'])->name('api.v1.peminjaman.show');
                Route::put('/{id}', [PeminjamanBukuController::class, 'update'])->name('api.v1.peminjaman.update');
                Route::delete('/{id}', [PeminjamanBukuController::class, 'destroy'])->name('api.v1.peminjaman.destroy');
                Route::post('/{id}/pengembalian', [PeminjamanBukuController::class, 'pengembalian'])->name('api.v1.peminjaman.pengembalian');
                Route::get('/siswa/{siswaId}', [PeminjamanBukuController::class, 'bySiswa'])->name('api.v1.peminjaman.by-siswa');
            });
        });

        // Akademik Routes
        Route::prefix('akademik')->group(function () {
            // Ujian
            Route::prefix('ujian')->group(function () {
                Route::get('/', [UjianController::class, 'index'])->name('api.v1.ujian.index');
                Route::post('/', [UjianController::class, 'store'])->name('api.v1.ujian.store');
                Route::get('/{id}', [UjianController::class, 'show'])->name('api.v1.ujian.show');
                Route::put('/{id}', [UjianController::class, 'update'])->name('api.v1.ujian.update');
                Route::delete('/{id}', [UjianController::class, 'destroy'])->name('api.v1.ujian.destroy');
                Route::get('/{id}/nilai', [UjianController::class, 'nilai'])->name('api.v1.ujian.nilai');
                Route::get('/kelas/{kelasId}', [UjianController::class, 'byKelas'])->name('api.v1.ujian.by-kelas');
            });

            // Nilai
            Route::prefix('nilai')->group(function () {
                Route::get('/', [NilaiController::class, 'index'])->name('api.v1.nilai.index');
                Route::post('/', [NilaiController::class, 'store'])->name('api.v1.nilai.store');
                Route::get('/{id}', [NilaiController::class, 'show'])->name('api.v1.nilai.show');
                Route::put('/{id}', [NilaiController::class, 'update'])->name('api.v1.nilai.update');
                Route::delete('/{id}', [NilaiController::class, 'destroy'])->name('api.v1.nilai.destroy');
                Route::get('/siswa/{siswaId}', [NilaiController::class, 'bySiswa'])->name('api.v1.nilai.by-siswa');
                Route::get('/ujian/{ujianId}', [NilaiController::class, 'byUjian'])->name('api.v1.nilai.by-ujian');
                Route::get('/siswa/{siswaId}/rata-rata', [NilaiController::class, 'rataRata'])->name('api.v1.nilai.rata-rata');
            });

            // Ranking
            Route::prefix('ranking')->group(function () {
                Route::get('/', [RankingController::class, 'index'])->name('api.v1.ranking.index');
                Route::post('/', [RankingController::class, 'store'])->name('api.v1.ranking.store');
                Route::post('/generate', [RankingController::class, 'generate'])->name('api.v1.ranking.generate');
                Route::get('/{id}', [RankingController::class, 'show'])->name('api.v1.ranking.show');
                Route::put('/{id}', [RankingController::class, 'update'])->name('api.v1.ranking.update');
                Route::delete('/{id}', [RankingController::class, 'destroy'])->name('api.v1.ranking.destroy');
                Route::get('/kelas/{kelasId}', [RankingController::class, 'byKelas'])->name('api.v1.ranking.by-kelas');
            });

            // Rapor
            Route::prefix('rapor')->group(function () {
                Route::get('/', [RaporController::class, 'index'])->name('api.v1.rapor.index');
                Route::post('/', [RaporController::class, 'store'])->name('api.v1.rapor.store');
                Route::get('/{id}', [RaporController::class, 'show'])->name('api.v1.rapor.show');
                Route::put('/{id}', [RaporController::class, 'update'])->name('api.v1.rapor.update');
                Route::delete('/{id}', [RaporController::class, 'destroy'])->name('api.v1.rapor.destroy');
                Route::get('/siswa/{siswaId}', [RaporController::class, 'bySiswa'])->name('api.v1.rapor.by-siswa');
                Route::get('/{id}/detail', [RaporController::class, 'detail'])->name('api.v1.rapor.detail');
            });
        });

        // Admin only routes
        Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
            // User management
            Route::prefix('users')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('api.v1.admin.users.index');
                Route::post('/', [UserController::class, 'store'])->name('api.v1.admin.users.store');
                Route::get('/{id}', [UserController::class, 'show'])->name('api.v1.admin.users.show');
                Route::put('/{id}', [UserController::class, 'update'])->name('api.v1.admin.users.update');
                Route::delete('/{id}', [UserController::class, 'destroy'])->name('api.v1.admin.users.destroy');
                Route::post('/{id}/toggle-active', [UserController::class, 'toggleActive'])->name('api.v1.admin.users.toggle-active');
                Route::post('/{id}/assign-roles', [UserController::class, 'assignRoles'])->name('api.v1.admin.users.assign-roles');
            });

            // Role management
            Route::prefix('roles')->group(function () {
                Route::get('/', [RoleController::class, 'index'])->name('api.v1.admin.roles.index');
                Route::post('/', [RoleController::class, 'store'])->name('api.v1.admin.roles.store');
                Route::get('/{id}', [RoleController::class, 'show'])->name('api.v1.admin.roles.show');
                Route::put('/{id}', [RoleController::class, 'update'])->name('api.v1.admin.roles.update');
                Route::delete('/{id}', [RoleController::class, 'destroy'])->name('api.v1.admin.roles.destroy');
                Route::get('/{id}/permissions', [RoleController::class, 'permissions'])->name('api.v1.admin.roles.permissions');
                Route::post('/{id}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('api.v1.admin.roles.assign-permissions');
            });

            // Permission management
            Route::prefix('permissions')->group(function () {
                Route::get('/', [PermissionController::class, 'index'])->name('api.v1.admin.permissions.index');
                Route::post('/', [PermissionController::class, 'store'])->name('api.v1.admin.permissions.store');
                Route::get('/{id}', [PermissionController::class, 'show'])->name('api.v1.admin.permissions.show');
                Route::put('/{id}', [PermissionController::class, 'update'])->name('api.v1.admin.permissions.update');
                Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('api.v1.admin.permissions.destroy');
            });
        });
    });
});

// Health check
Route::get('health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'timestamp' => now()->toIso8601String(),
        'version' => '1.0.0',
    ]);
})->name('api.health');
