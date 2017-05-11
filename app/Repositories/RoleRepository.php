<?php
namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\Role;

/**
* class RoleRepository role数据逻辑类
* @package namespace App\Service
*/
class RoleRepository extends Repository{
	
	public function __construct(Role $role){
		parent::__construct($role);
	}
	
	/**
	* 分页获取角色信息信息
	*
	* @param array $page_params 分页参数{"draw","order","dir","start","length"}
	*
	* @return array 
	*/
	public function getRolesByPaging($page_params){

		return $this->getDatasByPaging($page_params, ['id', 'name', 'display_name', 'description', 'created_at']);
	}

	/**
	* 编辑角色
	*
	* @param array $data       插入的数据
	* @param bool  $is_update  是否更改操作
	*
	* @return bool
	*/
	public function editRole($data, $is_update = false){

		$role_model = $this->model;

		//查询是否有重复的角色名
		$has_same_role = $is_update? 
						 $role_model->where('id', '<>', $data->id)->where('name', $data->name)->count() :
						 $role_model->where('name', $data->name)->count();
		
        if($has_same_role) {
            return ['status' => false, 'info' => '角色名已被使用'];
        }

        $operate_type = "新增";
		//更新操作
		if($is_update){

			if(empty($data->id)){
				return ['status' => false, 'info' => '参数错误'];
			}

			$operate_type = "编辑";
			$role_model = $role_model->find($data->id);

		}

		$role_model->name 		  = $data->name;
		$role_model->display_name = htmlspecialchars($data->display_name);
		$role_model->description  = htmlspecialchars($data->description);


		$result = $role_model->save();

		return $result? ['status'=>true, 'info'=>$operate_type."角色成功"] : ['status'=>true, 'info'=>$operate_type."角色失败"];

	}

	/**
	* 保存角色权限设置
	*
	* @param int   $role_id        角色id
	* @param array $permission_ids 权限id列表
	*/
	public function saveRolePermissions($role_id, $permission_ids = []){

		$role = $this->model->find($role_id);

		return $role->perms()->sync($permission_ids);
	}

	/**
     * 获取角色的权限ID列表
     *
     * @param int    $role_id 角色ID
     *
     * @return array
     */
    public function getRolePermissions($role_id){

        $permissions = $this->model->find($role_id)->perms()->get(["id"])->toArray();

        return array_column($permissions, "id");

    }

    /**
    * 删除角色，相应的角色对应的用户映射关系，角色对应的权限映射关系 也会跟着删除
    * 
    * @param int $role_id 角色ID
    *
    * @return bool 
    */
    public function deleteRole($role_id){

    	$role = $this->model->find($role_id);
        if(!$role) {
            return false;
        }

        //角色对应的用户权限一并删除
        $role->users()->detach();

        return $role->delete();
    }

}