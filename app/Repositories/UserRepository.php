<?php
namespace App\Repositories;

use App\Repositories\Repository;
use App\User;

/**
* class UserRepository user数据逻辑类
* @package namespace App\Service
*/
class UserRepository extends Repository{
	
	public function __construct(User $user){
		parent::__construct($user);
	}
	/**
	* 分页获取用户信息
	*
	* @param array $page_params 分页参数{"draw","order","dir","start","length"}
	*
	* @return array 
	*/
	public function getUsersByPaging($page_params){

		return $this->getDatasByPaging($page_params, ['id', 'name', 'email', 'created_at', 'is_super']);
		
	}

	/**
	* 编辑用户信息
	*
	* @param array $data       插入的数据
	* @param bool  $is_update  是否更改操作
	*
	* @return bool
	*/
	public function editUser($data, $is_update = false){

		$user_model = $this->model;

		//查询是否有重复的用户名
		$has_same_user = $is_update? 
						 $user_model->where('id', '<>', $data->id)->where('name', $data->name)->count() :
						 $user_model->where('name', $data->name)->count();

        if($has_same_user) {
            return ['status' => false, 'info' => '用户名已被使用'];
        }

        $operate_type = "新增";
		//更新操作
		if($is_update){

			if(empty($data->id)){
				return ['status' => false, 'info' => '参数错误'];
			}
			$operate_type = "编辑";
			$user_model = $user_model->find(intval($data->id));
		}

		$user_model->name     = $data->name;
		$user_model->is_super = $data->is_super == 1? 1 : 0;

        $result = $user_model->save();
        if(!$result) {
            return ['status' => false, 'info' => '用户更新失败'];
        }

        //清除用户对应的角色
        $user_model->roles()->detach();

        if(!empty($data->roles)) {
            $user_model->attachRoles($data->roles);
        }
        return ['status' => true, 'info' => $operate_type . "用户成功"];

	}

}