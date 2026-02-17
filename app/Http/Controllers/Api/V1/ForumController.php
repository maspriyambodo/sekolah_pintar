<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Forum\CreateForumRequest;
use App\Http\Requests\Api\V1\Forum\UpdateForumRequest;
use App\Http\Resources\Api\V1\ForumResource;
use App\Services\ForumService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ForumController extends Controller
{
    use ApiResponseTrait;

    private ForumService $forumService;

    public function __construct(ForumService $forumService)
    {
        $this->forumService = $forumService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_guru_mapel_id' => $request->input('mst_guru_mapel_id'),
                'sys_user_id' => $request->input('sys_user_id'),
                'parent_id' => $request->input('parent_id'),
                'topik_only' => $request->boolean('topik_only'),
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $perPage = (int) $request->input('per_page', 15);

            $paginator = $this->forumService->getAllForum($filters, $perPage);

            return $this->paginatedResponse($paginator, 'Forum retrieved successfully', ForumResource::class);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve forum list', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve forum list', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $forum = $this->forumService->getForumById($id);

            if (!$forum) {
                return $this->notFoundResponse('Forum not found');
            }

            return $this->successResponse(new ForumResource($forum));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve forum', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve forum', 500);
        }
    }

    public function store(CreateForumRequest $request): JsonResponse
    {
        try {
            $forum = $this->forumService->createForum($request->validated());

            return $this->createdResponse(
                new ForumResource($forum),
                'Forum created successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to create forum', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create forum: ' . $e->getMessage(), 500);
        }
    }

    public function update(UpdateForumRequest $request, int $id): JsonResponse
    {
        try {
            $forum = $this->forumService->updateForum($id, $request->validated());

            return $this->successResponse(
                new ForumResource($forum),
                'Forum updated successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Forum not found');
        } catch (\Exception $e) {
            Log::error('Failed to update forum', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update forum: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->forumService->deleteForum($id);

            if (!$deleted) {
                return $this->notFoundResponse('Forum not found');
            }

            return $this->successResponse(null, 'Forum deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete forum', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete forum', 500);
        }
    }

    public function topics(int $guruMapelId, Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->input('search'),
            ];

            $filters = array_filter($filters);
            $forum = $this->forumService->getTopicsByGuruMapel($guruMapelId, $filters);

            return $this->successResponse(
                ForumResource::collection($forum),
                'Forum topics retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve forum topics', ['guru_mapel_id' => $guruMapelId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve forum topics', 500);
        }
    }

    public function replies(int $parentId): JsonResponse
    {
        try {
            $forum = $this->forumService->getReplies($parentId);

            return $this->successResponse(
                ForumResource::collection($forum),
                'Forum replies retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve forum replies', ['parent_id' => $parentId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve forum replies', 500);
        }
    }

    public function byUser(int $userId, Request $request): JsonResponse
    {
        try {
            $filters = [
                'mst_guru_mapel_id' => $request->input('mst_guru_mapel_id'),
            ];

            $filters = array_filter($filters);
            $forum = $this->forumService->getForumByUser($userId, $filters);

            return $this->successResponse(
                ForumResource::collection($forum),
                'Forum retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve forum by user', ['user_id' => $userId, 'error' => $e->getMessage()]);
            return $this->errorResponse('Failed to retrieve forum', 500);
        }
    }
}
