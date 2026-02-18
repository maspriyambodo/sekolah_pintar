<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\System\SysSekolahSettings;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SysSekolahSettingsController extends Controller
{
    use ApiResponseTrait;

    public function index(int $sekolahId): JsonResponse
    {
        try {
            $settings = SysSekolahSettings::where('mst_sekolah_id', $sekolahId)->get();

            return $this->successResponse($settings, 'Settings retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve settings', ['sekolah_id' => $sekolahId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve settings', 500);
        }
    }

    public function show(int $sekolahId, int $id): JsonResponse
    {
        try {
            $setting = SysSekolahSettings::where('mst_sekolah_id', $sekolahId)
                ->find($id);

            if (!$setting) {
                return $this->notFoundResponse('Setting not found');
            }

            return $this->successResponse($setting);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve setting', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve setting', 500);
        }
    }

    public function store(Request $request, int $sekolahId): JsonResponse
    {
        try {
            $request->validate([
                'key' => ['required', 'string', 'max:100'],
                'value' => ['nullable', 'string'],
            ]);

            $setting = SysSekolahSettings::create([
                'mst_sekolah_id' => $sekolahId,
                'key' => $request->input('key'),
                'value' => $request->input('value'),
            ]);

            Log::info('Setting created', ['sekolah_id' => $sekolahId, 'key' => $request->input('key')]);

            return $this->createdResponse($setting, 'Setting created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create setting', ['sekolah_id' => $sekolahId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create setting: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $sekolahId, int $id): JsonResponse
    {
        try {
            $setting = SysSekolahSettings::where('mst_sekolah_id', $sekolahId)
                ->find($id);

            if (!$setting) {
                return $this->notFoundResponse('Setting not found');
            }

            $setting->update([
                'value' => $request->input('value'),
            ]);

            Log::info('Setting updated', ['sekolah_id' => $sekolahId, 'id' => $id]);

            return $this->successResponse($setting, 'Setting updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update setting', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update setting: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $sekolahId, int $id): JsonResponse
    {
        try {
            $setting = SysSekolahSettings::where('mst_sekolah_id', $sekolahId)
                ->find($id);

            if (!$setting) {
                return $this->notFoundResponse('Setting not found');
            }

            $setting->delete();

            Log::info('Setting deleted', ['sekolah_id' => $sekolahId, 'id' => $id]);

            return $this->successResponse(null, 'Setting deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete setting', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete setting', 500);
        }
    }

    public function byKey(int $sekolahId, string $key): JsonResponse
    {
        try {
            $setting = SysSekolahSettings::where('mst_sekolah_id', $sekolahId)
                ->where('key', $key)
                ->first();

            if (!$setting) {
                return $this->notFoundResponse('Setting not found');
            }

            return $this->successResponse($setting);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve setting by key', ['sekolah_id' => $sekolahId, 'key' => $key, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve setting', 500);
        }
    }
}
