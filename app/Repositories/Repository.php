<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
* class Repository 通用的数据逻辑类
* @package namespace App\Repositories
*/
class Repository {
	
	public $model;

	public function __construct(Model $model){
		$this->model = $model;
	}
	
	/**
	* 分页获取信息
	*
	* @param array $page_params 分页参数{"draw","order","dir","start","length"}
	* @param array $columns 数据表列名
	*
	* @return array 
	*/
	public function getDatasByPaging($page_params, $colums){

		//统计值
		$datas['draw'] = $page_params['draw'];
		//计算总数
		$datas['recordsFiltered'] = $this->model->count();
		$datas['recordsTotal']    = $datas['recordsFiltered'];

		$datas['data'] = $this->model->select($colums)
						  ->orderBy($page_params['order'], $page_params['dir'])
						  ->skip($page_params['start'])
						  ->take($page_params['length'])
						  ->get();
		
	    return $datas;
	}

}