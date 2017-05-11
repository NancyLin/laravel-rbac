<?php
namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\Permission;

/**
* class RoleRepository role数据逻辑类
* @package namespace App\Service
*/
class PermissionRepository extends Repository{
	
	public function __construct(Permission $permission){
		parent::__construct($permission);
	}
	
	/**
	* 通过name查找权限表，如果不存在则插入
	*
	* @param string $name 权限标识
	* @param array  $data
	*
	* @return bool
	*/
	public function createPermissionIfNoExist($name, $data){

		$permission = $this->model->where("name", $name)->first(['id']);

		//存在，不做任何操作
		if(!empty($permission)){
			return true;
		}

		//插入
		foreach ($data as $key => $value) {
			$this->model->$key = $value;
		}

		return $this->model->save();
	}

	/**
	* 分页获取权限信息信息
	*
	* @param array $page_params 分页参数{"draw","order","dir","start","length"}
	*
	* @return array 
	*/
	public function getPermissionsByPaging($page_params){

		return $this->getDatasByPaging($page_params, 
			   ['id','p_id','name','display_name','description','is_menu','sort','created_at']);
	}

	/**
	* 编辑角色
	*
	* @param array $data 插入的数据
	* @param bool  $new  是否更改操作
	*
	* @return bool
	*/
	public function editPermission($data, $is_update = true){

		$permission_model = $this->model;

		//查询是否有重复的用户名
		$has_same_permission = $is_update? 
							   $permission_model->where('id', '<>', $data->id)->where('name', $data->name)->count() :
							   $permission_model->where('name', $data->name)->count();

        if($has_same_permission) {
            return ['status' => false, 'info' => '权限标识已被使用'];
        }

        $operate_type = "新增";
		//更新操作
		if($is_update){

			if(empty($data->id)){
				return false;
			}

			//父类ID不能与自身ID一致
			if(intval($data->p_id) == intval($data->id)){
				return ['status' => false, 'info' => '父类权限组不能与自己一致'];
			}

			$operate_type = "编辑";
			$permission_model = $permission_model->find($data->id);

		}

		$permission_model->p_id         = intval($data->p_id);
		$permission_model->name 		= $data->name;
		$permission_model->display_name = htmlspecialchars($data->display_name);
		$permission_model->description  = htmlspecialchars($data->description);
		$permission_model->is_menu      = intval($data->is_menu);
		$permission_model->sort         = intval($data->sort);


		$result = $permission_model->save();

		return $result? ['status'=>true, 'info'=>$operate_type."权限成功"] : ['status'=>true, 'info'=>$operate_type."权限失败"];

	}

	 /**
    * 删除权限，相应的权限对应的角色映射关系，也会跟着删除
    * 
    * @param int $permission_id 权限ID
    *
    * @return bool 
    */
    public function deletePermission($permission_id){

    	$permission = $this->model->find($permission_id);
        if(!$permission) {
            return false;
        }

        //查询是否是其他权限的父类权限组
        $sub_permission = $this->model->where("p_id", $permission_id)->first();
        if(!empty($sub_permission)){
        	return false;
        }

        //角色对应的用户权限一并删除
        $permission->roles()->detach();

        return $permission->delete();
    }

}