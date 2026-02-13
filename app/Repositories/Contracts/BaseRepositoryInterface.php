<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\CursorPaginator;

interface BaseRepositoryInterface
{
    public function find(int $id): ?Model;

    public function findBy(string $column, mixed $value): ?Model;

    public function all(array $columns = ['*']): Collection;

    public function create(array $data): Model;

    public function update(int $id, array $data): Model;

    public function delete(int $id): bool;

    public function paginate(int $perPage = 15, array $columns = ['*']): CursorPaginator;

    public function with(array $relations): static;

    public function select(array $columns): static;

    public function where(string $column, mixed $value, string $operator = '='): static;
}
