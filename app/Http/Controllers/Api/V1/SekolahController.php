<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Sekolah\CreateSekolahRequest;
use App\Http\Requests\Api\V1\Sekolah\UpdateSekolahRequest;
use App\Http\Resources\Api\V1\SekolahResource;
use App\Services\SekolahService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SekolahController extends Controller
{
    use ApiResponseTrait;

    private SekolahService $sekolahService;

    public function __construct(SekolahService $sekolahService)
    {
        $this->sekolahService = $sekolahService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->input('search'),
                'is_active' => $request->input('is_active'),
                'subscription_plan' => $request->input('subscription_plan'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->sekolahService->getAllSekolah($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Sekolah retrieved successfully', SekolahResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve sekolah list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve sekolah list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $sekolah = $this->sekolahService->getSekolahById($id);

            if (!$sekolah) {
                return $this->notFoundResponse('Sekolah not found');
            }

            return $this->successResponse(new SekolahResource($sekolah));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve sekolah', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve sekolah', 500);
        }
    }

    public function showByUuid(string $uuid): JsonResponse
    {
        try {
            $sekolah = $this->sekolahService->getSekolahByUuid($uuid);

            if (!$sekolah) {
                return $this->notFoundResponse('Sekolah not found');
            }

            return $this->successResponse(new SekolahResource($sekolah));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve sekolah', ['uuid' => $uuid, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve sekolah', 500);
        }
    }

    public function store(CreateSekolahRequest $request): JsonResponse
    {
        try {
            $sekolah = $this->sekolahService->createSekolah($request->validated());

            return $this->createdResponse(
                new SekolahResource($sekolah),
                'Sekolah created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create sekolah', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create sekolah: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateSekolahRequest $request, int $id): JsonResponse
    {
        try {
            $sekolah = $this->sekolahService->updateSekolah($id, $request->validated());

            return $this->successResponse(
                new SekolahResource($sekolah),
                'Sekolah updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Sekolah not found');
        } catch (\Exception $e) {
            Log::error('Failed to update sekolah', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update sekolah: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->sekolahService->deleteSekolah($id);

            if (!$deleted) {
                return $this->notFoundResponse('Sekolah not found');
            }

            return $this->successResponse(null, 'Sekolah deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete sekolah', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete sekolah', 500);
        }
    }

    public function settings(int $id): JsonResponse
    {
        try {
            $settings = $this->sekolahService->getSettingsBySekolah($id);

            return $this->successResponse($settings, 'Settings retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve settings', ['sekolah_id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve settings', 500);
        }
    }

    public function setSetting(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'key' => ['required', 'string', 'max:100'],
                'value' => ['nullable', 'string'],
            ]);

            $setting = $this->sekolahService->setSetting(
                $id,
                $request->input('key'),
                $request->input('value')
            );

            return $this->successResponse($setting, 'Setting updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to set setting', ['sekolah_id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to set setting', 500);
        }
    }

    public function deleteSetting(int $id, string $key): JsonResponse
    {
        try {
            $deleted = $this->sekolahService->deleteSetting($id, $key);

            if (!$deleted) {
                return $this->notFoundResponse('Setting not found');
            }

            return $this->successResponse(null, 'Setting deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete setting', ['sekolah_id' => $id, 'key' => $key, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete setting', 500);
        }
    }
}
