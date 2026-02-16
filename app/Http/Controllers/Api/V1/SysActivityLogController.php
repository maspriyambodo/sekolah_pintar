<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SysActivityLogResource;
use App\Models\System\SysActivityLog;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SysActivityLogController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->input('per_page', 15);
            $filters = [
                'search' => $request->input('search'),
                'sys_user_id' => $request->input('sys_user_id'),
                'action' => $request->input('action'),
                'module' => $request->input('module'),
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
            ];

            $filters = array_filter($filters);

            $query = SysActivityLog::with('user')->orderBy('created_at', 'desc');

            if (!empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('description', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('module', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('action', 'like', '%' . $filters['search'] . '%');
                });
            }

            if (!empty($filters['sys_user_id'])) {
                $query->where('sys_user_id', $filters['sys_user_id']);
            }

            if (!empty($filters['action'])) {
                $query->where('action', $filters['action']);
            }

            if (!empty($filters['module'])) {
                $query->where('module', $filters['module']);
            }

            if (!empty($filters['date_from'])) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            }

            $paginator = $query->paginate($perPage);

            return $this->paginatedResponse($paginator, 'Activity logs retrieved successfully', SysActivityLogResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve activity logs', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve activity logs', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $activityLog = SysActivityLog::with('user')->find($id);

            if (!$activityLog) {
                return $this->notFoundResponse('Activity log not found');
            }

            return $this->successResponse(new SysActivityLogResource($activityLog));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve activity log', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve activity log', 500);
        }
    }

    public function byUser(int $userId, Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->input('per_page', 15);

            $paginator = SysActivityLog::with('user')
                ->where('sys_user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return $this->paginatedResponse($paginator, 'User activity logs retrieved successfully', SysActivityLogResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user activity logs', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve user activity logs', 500);
        }
    }

    public function byModule(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->input('per_page', 15);
            $module = $request->input('module');

            if (!$module) {
                return $this->errorResponse('Module parameter is required', 400);
            }

            $paginator = SysActivityLog::with('user')
                ->where('module', $module)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return $this->paginatedResponse($paginator, 'Module activity logs retrieved successfully', SysActivityLogResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve module activity logs', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve module activity logs', 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $activityLog = SysActivityLog::find($id);

            if (!$activityLog) {
                return $this->notFoundResponse('Activity log not found');
            }

            $activityLog->delete();

            return $this->successResponse(null, 'Activity log deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete activity log', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete activity log', 500);
        }
    }

    public function clearOld(Request $request): JsonResponse
    {
        try {
            $days = (int) $request->input('days', 30);

            if ($days < 1) {
                return $this->errorResponse('Days parameter must be at least 1', 400);
            }

            $deleted = SysActivityLog::where('created_at', '<', now()->subDays($days))->delete();

            return $this->successResponse(['deleted_count' => $deleted], 'Old activity logs cleared successfully');
        } catch (\Exception $e) {
            Log::error('Failed to clear old activity logs', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to clear old activity logs', 500);
        }
    }

    public function statistics(): JsonResponse
    {
        try {
            $totalLogs = SysActivityLog::count();
            $todayLogs = SysActivityLog::whereDate('created_at', today())->count();
            $thisWeekLogs = SysActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $thisMonthLogs = SysActivityLog::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();

            $topModules = SysActivityLog::select('module')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('module')
                ->orderByDesc('count')
                ->limit(5)
                ->get();

            $topActions = SysActivityLog::select('action')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('action')
                ->orderByDesc('count')
                ->limit(5)
                ->get();

            return $this->successResponse([
                'total_logs' => $totalLogs,
                'today_logs' => $todayLogs,
                'this_week_logs' => $thisWeekLogs,
                'this_month_logs' => $thisMonthLogs,
                'top_modules' => $topModules,
                'top_actions' => $topActions,
            ], 'Activity log statistics retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve activity log statistics', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve activity log statistics', 500);
        }
    }
}
