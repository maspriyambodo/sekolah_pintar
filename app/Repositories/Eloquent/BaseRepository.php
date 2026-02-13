<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\CursorPaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findBy(string $column, mixed $value): ?Model
    {
        return $this->model->where($column, $value)->first();
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $record = $this->find($id);
        if (!$record) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException(
                "Record with ID {$id} not found"
            );
        }
        $record->update($data);
        return $record->fresh();
    }

    public function delete(int $id): bool
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }
        return $record->delete();
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): CursorPaginator
    {
        return $this->model->cursorPaginate($perPage, $columns);
    }

    public function with(array $relations): static
    {
        $this->model = $this->model->with($relations);
        return $this;
    }

    public function select(array $columns): static
    {
        $this->model = $this->model->select($columns);
        return $this;
    }

    public function where(string $column, mixed $value, string $operator = '='): static
    {
        $this->model = $this->model->where($column, $operator, $value);
        return $this;
    }
}
