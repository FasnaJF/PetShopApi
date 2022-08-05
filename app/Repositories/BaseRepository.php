<?php


namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes)
    {
        $created = $this->model->create($attributes);

        return $this->getById($created->id);
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getAll($sortBy = null)
    {
        if (isset($sortBy) && !empty($sortBy)) {
            return $this->model->all()->sortBy($sortBy);
        }
        return $this->model->all();
    }

    public function getCountAll()
    {
        return $this->model->count();
    }

    public function updateById($id, array $params)
    {
        $this->model->find($id)->update($params);

        return $this->getById($id);
    }

    public function deleteById($id)
    {
        return $this->model->find($id)->delete();
    }

    public function getByUUID($uuid)
    {
        return $this->model->where('uuid',$uuid)->first();
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function withTrashed(): BaseRepository
    {
        $this->model = $this->model->withTrashed();
        return $this;
    }

    public function getAllWithQueryParams($request)
    {
        $limit = $request->input('limit')? $request->input('limit'):null;
        $sortBy = $request->input('sortBy')? $request->input('sortBy'): 'id' ;
        $desc = ($request->input('desc') == 'true') ? 'DESC' : 'ASC';
        $sortBy = [$sortBy, $desc];

        return $this->model
            ->when($sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy[0],$sortBy[1]);
            })
            ->paginate($limit);
    }

}
