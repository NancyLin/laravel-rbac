<?php
namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\AdminController;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;

use App\Presenters\RbacPresenter;
/**
* 权限管理控制器
* @package App\Http\Controllers\Rbac
*/
class PermissionController extends AdminController {

	protected $permission;

	/**
	* Create a new controller instance.
	*
	* @param App\Repositories\PermissionRepository  $permission permission model数据逻辑处理类
	*
	* @return void
	*/
	public function __construct(PermissionRepository $permission){

		parent::__construct();

		$this->permission = $permission;
  
	}

	/**
	* 角色管理页面
	*/
	public function index(){
		return view("rbac.permission");
	}

	/**
	* 创建编辑权限
	*
	* @param Illuminate\Http\Request $request
	*
	* @return array {"status" => ,"info" =>}
	*/
	public function postEditPermission(Request $request){
		
		$data = json_decode($request->input("data"));

		if(empty($data) || empty($request->input('operate_type'))){
			return ["status" => false, "info" => "参数错误"];
		}

		$is_update = 'insert' == $request->input('operate_type')? false : true;

		return $this->permission->editPermission($data, $is_update);
	}

	/**
	* 获取权限树表
	*/
	public function getPermissionTreeTable(){

		return ["status" 	            => true,
				"permission_tree_table" => RbacPresenter::permissionToTreeTable($level, $permission_html, $row_class)];
	}

	/**
	* 删除权限
	*
	* @param int $role_id 角色ID
	*
	* @return array
	*/
	public function deleteDeletePermission($permission_id){
		$permission_id = intval($permission_id);

		$result = $this->permission->deletePermission($permission_id);

		return $result? ["status" => true, "info" => "删除权限成功"] : ["status" => false, "info" => "删除权限失败"];
	}

}