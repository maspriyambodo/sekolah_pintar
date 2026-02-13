<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\CreateUserRequest;
use App\Http\Requests\Api\V1\User\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\UserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use ApiResponseTrait;

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'role' => $request->input('role'),
                'is_active' => $request->input('is_active'),
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->userService->getAllUsers($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Users retrieved successfully', UserResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve users list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve users list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->userService->getUserById($id);

            if (!$user) {
                return $this->notFoundResponse('User not found');
            }

            return $this->successResponse(new UserResource($user));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve user', 500);
        }
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->validated());

            return $this->createdResponse(
                new UserResource($user),
                'User created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create user', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create user: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $user = $this->userService->updateUser($id, $request->validated());

            return $this->successResponse(
                new UserResource($user),
                'User updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('User not found');
        } catch (\Exception $e) {
            Log::error('Failed to update user', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update user: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->userService->deleteUser($id);

            if (!$deleted) {
                return $this->notFoundResponse('User not found');
            }

            return $this->successResponse(null, 'User deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete user', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete user', 500);
        }
    }

    public function toggleActive(int $id): JsonResponse
    {
        try {
            $user = $this->userService->toggleActive($id);

            return $this->successResponse(
                new UserResource($user),
                'User status updated successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to toggle user status', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update user status', 500);
        }
    }

    public function assignRoles(Request $request, int $id): JsonResponse
    {
        try {
            $roles = $request->input('roles', []);
            $user = $this->userService->assignRoles($id, $roles);

            return $this->successResponse(
                new UserResource($user),
                'Roles assigned successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to assign roles', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to assign roles', 500);
        }
    }
}
