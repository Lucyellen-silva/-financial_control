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
        return $this->model->all()->whereNull('deleted_at')->toArray();
    }

	public function create(array $data)
	{
		$this->model->fill($data);
		$this->model->save();
		return $this->model;
	}

	public function update($id, array $data)
	{
        $model = $this->findInternal($id);
		$model->fill($data);
		$model->save();
		return $model;
	}

	public function delete($id)
	{
        $model = $this->findInternal($id);
        $model->deleted_at = date('Y-m-d H:i:s');
		$model->save();
	}

	protected function findInternal($id)
    {
        return is_array($id) ? $this->findOneBy($id) : $this->find($id);
    }

    /**
     * @param int $id
     * @param bool $failIfNotExist
     * @return mixed
     */
    public function find(int $id, bool $failIfNotExist = true)
	{
		return $failIfNotExist ? $this->model->findOrFail($id) : $this->model->find($id);
	}

	public function findByField($field, string $value)
	{
		return $this->model->where($field, $value)->whereNull('deleted_at')->get();
	}

	public function findOneBy(array $search)
    {
        $queryBuilder = $this->model;
        foreach ($search as $field => $value){
            $queryBuilder = $queryBuilder->where($field, $value)->whereNull('deleted_at');
        }

        return $queryBuilder->firstOrFail();
    }

    public function createPlots($data)
    {
        $plots = $data['plots'];
        $data['group_plots'] = bin2hex(random_bytes(16));

        for($i = 1; $i <= $plots; $i++){
            //Se a fatura vem para esse mes
            if($data['mes'] == 2){
                $data['date_launch'] = date("Y-m-d", strtotime("+1 month", strtotime($data['date_launch'])));
            }

            $this->model->create($data);
            $data['plots']      -= 1;
            $data['date_launch'] = date("Y-m-d", strtotime("+1 month", strtotime($data['date_launch'])));

        }

        return $this->model;
    }

    public function updatePlots($id, $data)
    {
        $model   = $this->findInternal($id);
        $allPays = $this->findByField('group_plots', $model->group_plots);
        $plots   = $data['plots'];

        if ($plots == $model->plots){
            foreach ($allPays as $pay){
                return $this->update($pay->id, $data);
            }
        }

        if( $plots <= $model->plots ) {
            $plots = $model->plots - $plots;
            for ($i = 1 ; $i <= $plots; $i++){
                $this->delete($allPays[$i]->id);
            }

            $plot    = $model->plots;
            $allPays = $this->findByField('group_plots', $model->group_plots);

            foreach ($allPays as $pay){
                $pay['plots'] = $plot;
                return $this->update($pay->id, $data);
                $plot -= 1;
            }

        } else {
            $plot  = $data['plots'];
            $plots = $plot-count($allPays);

            for($i = 1; $i <= $plots; $i++){
                $data['group_plots'] = $model->group_plots;
                $this->model->create($data);
            }

            $allPays   = $this->findByField('group_plots', $model->group_plots);
            $dateFirst = $allPays->first();
            $date      = $dateFirst->date_launch;

            foreach ($allPays as $pay){
                $data['date_launch'] = $date;
                $pay['plots']        = $plot;
                $pay->save();
                $plot -= 1;
                $date = date("Y-m-d", strtotime("+1 month", strtotime($data['date_launch'])));
            }
        }
        return $this->model;
    }

    public function deletePlots($id)
    {
        $model = $this->findInternal($id);

        $this->delete($id);

        $allPays = $this->findByField('group_plots', $model->group_plots);
        $plots   = count($allPays);

        foreach ($allPays as $pay)
        {
            $pay['plots'] = $plots;
            $pay->save();

            $plots -= 1;
        }

        return $this->model;
    }
}