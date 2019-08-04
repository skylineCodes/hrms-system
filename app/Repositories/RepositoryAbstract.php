<?php

namespace App\Repositories;

use App\Repositories\Criteria\CriteriaInterface;
use App\Repositories\Exceptions\NoEntityDefined;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class RepositoryAbstract implements RepositoryInterface, CriteriaInterface
{
    protected $entity;

    public function __construct()
    {
        $this->entity = $this->resolveEntity();
    }

    /**
     * Fetch all entity/model resource from the database
     * 
     * @return collection
     */
    public function all()
    {
        return $this->entity->get();
    }

    /**
     * Fetch all entity/model resource from the database
     * 
     * @return collection
     */
    public function first()
    {
        return $this->entity->first();
    }
  
    /**
     * Fetch a single entity/model resource by its id
     * 
     * @param int
     * @return $id
     */
    public function find($id)
    {
        $model = $this->entity->find($id);

        if (!$model) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->entity->getModel())
            );
        }

        return $model;
    }

    /**
     * Fetch entity/model resource from the database by the query
     * 
     * @param string
     * @return collection
     */
    public function findWhere($column, $value)
    {
        return $this->entity->where($column, $value)->get();
    }

    /**
     * Fetch the first entity/model resource from the database by the query
     * 
     * @param string
     * @return collection
     */
    public function findWhereFirst($column, $value)
    {
        $model = $this->entity->where($column, $value)->first();

        if (!$model) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->entity->getModel())
            );
        }

        return $model;
    }

    /**
     * Fetch entity/model resource from database based by page limits
     * 
     * @param int
     * @return collection
     */
    public function paginate($perPage = 10)
    {
        return $this->entity->paginate($perPage);
    }

    /**
     * Create entity/model resource to the database
     * 
     * @param array
     * @return collection
     */
    public function create(array $properties)
    {
        return $this->entity->create($properties);
    }

    /**
     * Create or Update entity/model resource to the database
     * 
     * @param array
     * @return collection
     */
    public function updateOrCreate(array $properties)
    {
        return $this->entity->updateOrCreate($properties);
    }

    /**
     *  Update entity/model resource in the database by the id
     * 
     * @param array
     * @return boolean
     */
    public function update($id, array $properties)
    {
        return $this->find($id)->update($properties);
    }

    /**
     * Remove entity/model resource from the database
     * 
     * @param int
     * @return boolean
     */
    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    /**
     * Fetch entity/model resource from the database by the query
     * 
     * @param string
     * @return collection
     */
    public function findWhereDelete($column, $value)
    {
        return $this->entity->where($column, $value)->delete();
    }

    /**
     * Restore temporary deleted entity/model resource from the database
     * 
     * @param string
     * @return collection
     */
    public function fetchTrashed($column, $value)
    {
        return $this->entity->withTrashed()->where($column, $value)->first()->restore();
    }

    /**
     * Fetch Trashed entity/model from the database
     * 
     * @param null
     * @return collection
     */
    public function trashed()
    {
        return $this->entity->onlyTrashed()->get();
    }

    /**
     * Force delete temporarily deleted entity/model from the database
     * 
     * @param int
     * @return boolean
     */
    public function permanentDelete($column, $value)
    {
        return $this->entity->withTrashed()->where($column, $value)->first()->forceDelete();
    }

    public function resolveEntity()
    {
        if(!method_exists($this, 'entity'))
        {
            throw new NoEntityDefined();
        }

        return app()->make($this->entity());
    }

    /**
     * Criteria implementation
     * 
     * @param array $criteria
     */
    public function withCriteria(...$criteria)
    {
        $criteria = array_flatten($criteria);

        foreach ($criteria as $criterion) {
            $this->entity = $criterion->apply($this->entity);
        }

        return $this;
    }
}