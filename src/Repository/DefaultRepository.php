<?php 
declare(strict_types=1);
namespace Financeiro\Repository;

class DefaultRepository implements RepositoryInterface
{
	/*
	** @var string
	*/
	private $modelClass;
	/*
	** @var Model
	*/
	private $model;

	public function __construct(string $modelClass)
	{
		$this->modelClass = $modelClass;
		$this->model 	  = new $modelClass;
	}

	public function all(): array
    {
        return $this->model->all()->toArray();
    }

	public function create(array $data)
	{
		$this->model->fill($data);
		$this->model->save();
		return $this->model;
	}

	public function update(int $id, array $data)
	{
		$model = $this->find($id);
		$model->fill($data);
		$model->save();
		return $model;
	}

	public function delete(int $id)
	{
		$model = $this->find($id);
		$model->delete();
	}

	public function find(int $id, bool $failIfNotExist = true)
	{
		return $failIfNotExist ? $this->model->findOrFail($id) : $this->model->find($id);
	}

	public function findByField($field, string $value)
	{
		return $this->model->where($field, $value)->get();	
	}

}