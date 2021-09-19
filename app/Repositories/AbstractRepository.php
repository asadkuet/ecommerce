<?php

namespace App\Repositories;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

abstract class AbstractRepository implements RepositoryInterface
{
    /** @var string */
    private $modelClass;

    /** @var Model */
    protected $model;

    /** @var Guard */
    protected $auth;

    public function __construct(?string $modelClass = null)
    {
        $this->modelClass = $modelClass ?: self::guessModelClass();
        $this->model = app($this->modelClass);

        // This instantiation may fail during a console command if e.g. APP_KEY is empty,
        // rendering the whole installation failing.
        try {
            $this->auth = app(Guard::class);
        } catch (Throwable $e) {
        }
    }

    private static function guessModelClass(): string
    {
        return preg_replace('/(.+)\\\\Repositories\\\\(.+)Repository$/m', '$1\Models\\\$2', static::class);
    }

    public function getOneById($id): ?Model
    {
        return $this->model->find($id);
    }

    public function getByIds(array $ids): Collection
    {
        return $this->model->whereIn($this->model->getKeyName(), $ids)->get();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getFirstWhere(...$params): ?Model
    {
        return $this->model->where(...$params)->first();
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    public function create(...$params): ?Model
    {
        return $this->model->create(...$params);
    }
    
    public function update(array $params, $id): bool
    {
        $fillable = $this->model->getFillable();
        $data = collect($params)->only($fillable)->all();
        return $this->model->where('id', $id)->update($data);
    }

    public function delete($id): bool
    {
        return $this->model->find($id)->delete();
    }


}