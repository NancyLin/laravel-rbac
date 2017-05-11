<?php
namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\AdminController;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;

use App\Presenters\RbacPresenter;

/**
* 角色管理控制器
* @package App\Http\Controllers\Rbac
*/
class RoleController extends AdminController {

	protected $role;

	/**
	* Create a new controller instance.
	*
	* @param App\Repositories\RoleRepository  $role role model数据逻辑处理类
	*
	* @return void
	*/
	public function __construct(RoleRepository $role){

		parent::__construct();

		$this->role = $role;
  
	}

	/**
	* 角色管理页面
	*/
	public function index(){
		
		return view("rbac.role");
	}

	/**
	* 获取角色信息（datatable信息显示）
	*
	* @param Illuminate\Http\Request $request
	*
	* @return array
	*/
	public function getRoles(Request $request){

		$page_param = $this->filterPageParams($request);

		return $this->role->getRolesByPaging($page_param);
	}

	/**
	* 创建编辑角色
	*
	* @param Illuminate\Http\Request $request
	*
	* @return array {"status" => ,"info" =>}
	*/
	public function postCreateRole(Request $request){
		
		$data = json_decode($request->input("data"));

		if(empty($data) || empty($request->input('operate_type'))){
			return ["status" => false, "info" => "参数错误"];
		}

		$is_update = 'insert' == $request->input('operate_type')? false : true;

		return $this->role->editRole($data, $is_update);
	}

	/**
	* 角色权限设置
	*
	* @param Illuminate\Http\Request $request
	*
	* @return array ['status'=>true/false, 'info'=>""]
	*/
	public function postRolePermissionSet(Request $request){

		$permission_ids = json_decode($request->input('permission_ids'));

		$result = $this->role->saveRolePermissions(intval($request->input('role_id')), $permission_ids);

		return $result? ['status'=>true, 'info'=>"角色权限设置成功"] :
						['status'=>false, 'info'=>"角色权限设置失败"];
	}

	/**
	* 获取指定角色的权限列表
	*
	* @param Illuminate\Http\Request $request
	*
	* @return array
	*/
	public function getRolePermission(Request $request){

		return ["status" => true, 
				"role_permissions" => $this->role->getRolePermissions($request->input("role_id", 0))];
	}

	/**
	* 删除角色
	*
	* @param int $role_id 角色ID
	*/
	public function deleteDeleteRole($role_id){
		$role_id = intval($role_id);

		$result = $this->role->deleteRole($role_id);

		return $result? ["status" => true, "info" => "删除角色成功"] : ["status" => false, "info" => "删除角色失败"];
	}

}