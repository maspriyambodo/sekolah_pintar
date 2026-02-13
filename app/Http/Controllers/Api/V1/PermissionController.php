<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Permission\CreatePermissionRequest;
use App\Http\Requests\Api\V1\Permission\UpdatePermissionRequest;
use App\Http\Resources\Api\V1\PermissionResource;
use App\Services\PermissionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    use ApiResponseTrait;

    private PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->permissionService->getAllPermissions($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Permissions retrieved successfully', PermissionResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve permissions list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve permissions list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $permission = $this->permissionService->getPermissionById($id);

            if (!$permission) {
                return $this->notFoundResponse('Permission not found');
            }

            return $this->successResponse(new PermissionResource($permission));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve permission', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve permission', 500);
        }
    }

    public function store(CreatePermissionRequest $request): JsonResponse
    {
        try {
            $permission = $this->permissionService->createPermission($request->validated());

            return $this->createdResponse(
                new PermissionResource($permission),
                'Permission created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create permission', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create permission: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdatePermissionRequest $request, int $id): JsonResponse
    {
        try {
            $permission = $this->permissionService->updatePermission($id, $request->validated());

            return $this->successResponse(
                new PermissionResource($permission),
                'Permission updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Permission not found');
        } catch (\Exception $e) {
            Log::error('Failed to update permission', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update permission: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->permissionService->deletePermission($id);

            if (!$deleted) {
                return $this->notFoundResponse('Permission not found');
            }

            return $this->successResponse(null, 'Permission deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete permission', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete permission', 500);
        }
    }
}
