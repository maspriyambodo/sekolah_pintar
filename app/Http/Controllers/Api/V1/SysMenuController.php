<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\System\SysMenu;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SysMenuController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        try {
            $query = SysMenu::with(['parent', 'permission']);

            // Filter by active status
            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            // Get only root menus (no parent)
            if ($request->boolean('root_only')) {
                $query->whereNull('parent_id');
            }

            // Get menu tree with children
            if ($request->boolean('with_children')) {
                $menus = $query->whereNull('parent_id')
                    ->with(['subMenus.subMenus'])
                    ->orderBy('urutan')
                    ->get();
                return $this->successResponse($menus, 'Menu tree retrieved successfully');
            }

            $perPage = (int) $request->input('per_page', 15);
            $paginator = $query->orderBy('urutan')->paginate($perPage);

            return $this->paginatedResponse($paginator, 'Menu retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve menu list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve menu list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $menu = SysMenu::with(['parent', 'permission', 'children'])->find($id);

            if (!$menu) {
                return $this->notFoundResponse('Menu not found');
            }

            return $this->successResponse($menu);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve menu', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve menu', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'parent_id' => 'nullable|exists:sys_menus,id',
                'sys_permission_id' => 'nullable|exists:sys_permissions,id',
                'nama_menu' => 'required|string|max:100',
                'url' => 'nullable|string|max:100',
                'icon' => 'nullable|string|max:50',
                'urutan' => 'nullable|integer',
                'is_active' => 'nullable|boolean',
            ]);

            $menu = SysMenu::create($validated);

            return $this->createdResponse($menu->load(['parent', 'permission']), 'Menu created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create menu', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create menu: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $menu = SysMenu::find($id);

            if (!$menu) {
                return $this->notFoundResponse('Menu not found');
            }

            $validated = $request->validate([
                'parent_id' => 'nullable|exists:sys_menus,id',
                'sys_permission_id' => 'nullable|exists:sys_permissions,id',
                'nama_menu' => 'sometimes|required|string|max:100',
                'url' => 'nullable|string|max:100',
                'icon' => 'nullable|string|max:50',
                'urutan' => 'nullable|integer',
                'is_active' => 'nullable|boolean',
            ]);

            // Prevent circular reference
            if (isset($validated['parent_id']) && $validated['parent_id'] == $id) {
                return $this->errorResponse('Menu cannot be its own parent', 422);
            }

            $menu->update($validated);

            return $this->successResponse($menu->fresh()->load(['parent', 'permission']), 'Menu updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update menu', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update menu: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $menu = SysMenu::find($id);

            if (!$menu) {
                return $this->notFoundResponse('Menu not found');
            }

            $menu->delete();

            return $this->successResponse(null, 'Menu deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete menu', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete menu', 500);
        }
    }

    public function getTree(): JsonResponse
    {
        try {
            $menus = SysMenu::whereNull('parent_id')
                ->where('is_active', true)
                ->with(['subMenus' => function ($query) {
                    $query->where('is_active', true)->orderBy('urutan');
                }, 'subMenus.subMenus' => function ($query) {
                    $query->where('is_active', true)->orderBy('urutan');
                }])
                ->orderBy('urutan')
                ->get();

            return $this->successResponse($menus, 'Menu tree retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve menu tree', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve menu tree', 500);
        }
    }
}