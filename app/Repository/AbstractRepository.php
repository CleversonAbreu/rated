<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository{

    private $model;


    public function __construct(Model $model){
        $this->model   = $model;
    }

    public function selectConditions($conditions){
        
        $arrayConditions = explode(';',$conditions);

        foreach ($arrayConditions as $condition) {
            $cond = explode(':',$condition);
            $this->model = $this->model->where($cond[0],$cond[1],$cond[2]);
        }
    }

    public function selectFilter($filters){
   
        $this->model = $this->model->selectRaw($filters);
    }

    public function getResult(){
        return $this->model;
    }
}