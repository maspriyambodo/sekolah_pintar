<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction\TrxForum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ForumService
{
    public function getAllForum(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxForum::query()->with(['guruMapel.guru', 'guruMapel.mapel', 'user', 'parent']);

        if (!empty($filters['mst_guru_mapel_id'])) {
            $query->where('mst_guru_mapel_id', $filters['mst_guru_mapel_id']);
        }

        if (!empty($filters['sys_user_id'])) {
            $query->where('sys_user_id', $filters['sys_user_id']);
        }

        if (!empty($filters['parent_id'])) {
            $query->where('parent_id', $filters['parent_id']);
        }

        // Get only topics (no parent)
        if (isset($filters['topik_only']) && $filters['topik_only']) {
            $query->whereNull('parent_id');
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('judul', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('pesan', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->cursorPaginate($perPage);
    }

    public function getForumById(int $id): ?TrxForum
    {
        return TrxForum::with(['guruMapel.guru', 'guruMapel.mapel', 'user', 'parent', 'replies.user'])->find($id);
    }

    public function getTopicsByGuruMapel(int $guruMapelId, array $filters = []): Collection
    {
        $query = TrxForum::query()->with(['user', 'replies.user'])
            ->where('mst_guru_mapel_id', $guruMapelId)
            ->whereNull('parent_id');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('judul', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('pesan', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getReplies(int $parentId): Collection
    {
        return TrxForum::with(['user'])
            ->where('parent_id', $parentId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getForumByUser(int $userId, array $filters = []): Collection
    {
        $query = TrxForum::query()->with(['guruMapel.guru', 'guruMapel.mapel', 'parent'])
            ->where('sys_user_id', $userId);

        if (!empty($filters['mst_guru_mapel_id'])) {
            $query->where('mst_guru_mapel_id', $filters['mst_guru_mapel_id']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function createForum(array $data): TrxForum
    {
        return DB::transaction(function () use ($data) {
            $forum = TrxForum::create([
                'mst_guru_mapel_id' => $data['mst_guru_mapel_id'],
                'sys_user_id' => $data['sys_user_id'],
                'parent_id' => $data['parent_id'] ?? null,
                'judul' => $data['judul'] ?? null,
                'pesan' => $data['pesan'],
                'file_lampiran' => $data['file_lampiran'] ?? null,
            ]);

            Log::info('Forum created', ['forum_id' => $forum->id]);
            return $forum;
        });
    }

    public function updateForum(int $id, array $data): TrxForum
    {
        return DB::transaction(function () use ($id, $data) {
            $forum = TrxForum::findOrFail($id);

            $forum->update([
                'judul' => $data['judul'] ?? $forum->judul,
                'pesan' => $data['pesan'] ?? $forum->pesan,
                'file_lampiran' => $data['file_lampiran'] ?? $forum->file_lampiran,
            ]);

            Log::info('Forum updated', ['forum_id' => $id]);
            return $forum;
        });
    }

    public function deleteForum(int $id): bool
    {
        $forum = TrxForum::find($id);
        if (!$forum) {
            return false;
        }

        // Delete all replies if this is a topic
        if (is_null($forum->parent_id)) {
            TrxForum::where('parent_id', $id)->delete();
        }

        $result = $forum->delete();
        Log::info('Forum deleted', ['forum_id' => $id]);
        return $result;
    }
}
