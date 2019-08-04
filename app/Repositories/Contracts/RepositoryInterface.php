<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function all();
    public function first();
    public function find($id);
    public function findWhere($column, $value);
    public function findWhereFirst($column, $value);
    public function paginate($perPage = 10);
    public function create(array $properties);
    public function update($id, array $properties);
    public function fetchTrashed($column, $value);
    public function trashed();
    public function delete($id);
    public function permanentDelete($column, $value);
}