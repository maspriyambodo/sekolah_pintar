<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\FileUploadController;
use App\Http\Controllers\Api\V1\SiswaController;
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

        // Siswa routes - Admin & Guru only
        Route::middleware([RoleMiddleware::class . ':admin,guru'])->prefix('siswa')->group(function () {
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

        // Admin only routes
        Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
            // User management
            // Route::apiResource('users', UserController::class);

            // Role management
            // Route::apiResource('roles', RoleController::class);

            // Permission management
            // Route::apiResource('permissions', PermissionController::class);
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
