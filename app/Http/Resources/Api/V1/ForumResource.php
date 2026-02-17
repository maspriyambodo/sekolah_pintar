<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mst_guru_mapel_id' => $this->mst_guru_mapel_id,
            'sys_user_id' => $this->sys_user_id,
            'parent_id' => $this->parent_id,
            'judul' => $this->judul,
            'pesan' => $this->pesan,
            'file_lampiran' => $this->file_lampiran,
            'is_topik' => is_null($this->parent_id),
            'guru_mapel' => $this->whenLoaded('guruMapel', fn () => [
                'id' => $this->guruMapel->id,
                'guru' => $this->guruMapel->guru ? [
                    'id' => $this->guruMapel->guru->id,
                    'nama' => $this->guruMapel->guru->nama,
                ] : null,
                'mapel' => $this->guruMapel->mapel ? [
                    'id' => $this->guruMapel->mapel->id,
                    'kode' => $this->guruMapel->mapel->kode_mapel,
                    'nama' => $this->guruMapel->mapel->nama_mapel,
                ] : null,
            ]),
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]),
            'parent' => $this->whenLoaded('parent', fn () => [
                'id' => $this->parent->id,
                'judul' => $this->parent->judul,
                'user' => $this->parent->user ? [
                    'id' => $this->parent->user->id,
                    'name' => $this->parent->user->name,
                ] : null,
            ]),
            'replies' => $this->whenLoaded('replies', fn () => $this->replies->map(fn ($reply) => [
                'id' => $reply->id,
                'pesan' => $reply->pesan,
                'file_lampiran' => $reply->file_lampiran,
                'user' => $reply->user ? [
                    'id' => $reply->user->id,
                    'name' => $reply->user->name,
                ] : null,
                'created_at' => $reply->created_at?->toIso8601String(),
            ])),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
