<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Role\CreateRoleRequest;
use App\Http\Requests\Api\V1\Role\UpdateRoleRequest;
use App\Http\Resources\Api\V1\RoleResource;
use App\Services\RoleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    use ApiResponseTrait;

    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->roleService->getAllRoles($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Roles retrieved successfully', RoleResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve roles list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve roles list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $role = $this->roleService->getRoleById($id);

            if (!$role) {
                return $this->notFoundResponse('Role not found');
            }

            return $this->successResponse(new RoleResource($role));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve role', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve role', 500);
        }
    }

    public function store(CreateRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleService->createRole($request->validated());

            return $this->createdResponse(
                new RoleResource($role),
                'Role created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create role', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create role: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        try {
            $role = $this->roleService->updateRole($id, $request->validated());

            return $this->successResponse(
                new RoleResource($role),
                'Role updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Role not found');
        } catch (\Exception $e) {
            Log::error('Failed to update role', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update role: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->roleService->deleteRole($id);

            if (!$deleted) {
                return $this->notFoundResponse('Role not found');
            }

            return $this->successResponse(null, 'Role deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete role', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete role', 500);
        }
    }

    public function permissions(int $id): JsonResponse
    {
        try {
            $permissions = $this->roleService->getRolePermissions($id);

            return $this->successResponse($permissions, 'Role permissions retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve role permissions', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve role permissions', 500);
        }
    }

    public function assignPermissions(Request $request, int $id): JsonResponse
    {
        try {
            $permissions = $request->input('permissions', []);
            $role = $this->roleService->assignPermissions($id, $permissions);

            return $this->successResponse(
                new RoleResource($role),
                'Permissions assigned successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to assign permissions', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to assign permissions', 500);
        }
    }
}
