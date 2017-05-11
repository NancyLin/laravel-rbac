<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Presenters\RbacPresenter;

use App\Models\Permission;
use App\Models\Role;
use App\User;

/**
* 加载基础数据通用类，一般是新增编辑时加载的数据，不需要权限控制的基础数据
*
* @package App\Http\Controller
*/
class LoadBaseDataController extends Controller {

	/**
	* 构造函数
	*/
	public function __construct(){}

	/**
    * 权限列表数据（下拉选择选项数据）
    *
    * @return array
    */
    public function getPermissionOptions(){
        return ["status"             => true,
                "permission_options" => RbacPresenter::permissionSelectOption($level, $permission_html)];
    }

    /**
    * 获取指定权限ID的权限数据
    *
    * @param int $permission_id 权限ID
    *
    * @return array
    */
    public function getPermission($permission_id){

        //过滤数据
        $permission_id = intval($permission_id);

        $permission = Permission::select(['id','p_id','name','display_name','description','is_menu','sort'])
                      ->where('id', $permission_id)
                      ->first();

        return ["status" => true, "permission" => $permission];

    }

    /**
    * 获取指定角色ID的权限数据
    *
    * @param int $role_id 权限ID
    *
    * @return array
    */
    public function getRole($role_id){

        $role_id = intval($role_id);
        
        $permission = Role::select(['id', 'name','display_name','description'])
                      ->where('id', $role_id)
                      ->first();

        return ["status" => true, "permission" => $permission];

    }

    /**
    * 获取全部角色列表选择项
    */
    public function getRolesList(){
        return ["status" => true,
                "roles"  => Role::select(['id', 'display_name'])->get()];
    }

    /**
    * 获取单个用户信息
    *
    * @param Illuminate\Http\Request $request
    *
    * @return array
    */
    public function getUser(Request $request){

        $user = User::find(intval($request->input('user_id')));

        //用户角色
        $user_roles = $user->roles()->get(['id'])->toArray();

        $user['roles'] = array_column($user_roles, "id");

        return ["status" => true, "user" => $user];
    }


    /**
    * 角色页面中获取权限树
    */
    public function getPermissionTree(){

        return ["status"          => true,
                "permission_tree" => RbacPresenter::permissionToTree($level, $permission_html)];
    }
}