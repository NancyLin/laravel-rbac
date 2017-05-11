<?php
namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\AdminController;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

use App\Presenters\RbacPresenter;
use App\Models\Role;
/**
* 用户管理控制器
* @package App\Http\Controllers\Rbac
*/
class UserController extends AdminController {

	protected $user;

	/**
	* Create a new controller instance.
	* 
	* @param App\Repositories\UserRepository $user 用户model数据逻辑类
	*
	* @return void
	*/
	public function __construct(UserRepository $user){

		parent::__construct();
		
		$this->user = $user;

	}

	/**
	* 用户管理页面
	*/
	public function index(){
		return view("rbac.user");
	}

	/**
	* 获取用户信息（datatable显示数据）
	*
	* @param Illuminate\Http\Request $request
	*
	* @return array
	*/
	public function getUsers(Request $request){

		$page_param = $this->filterPageParams($request);

		$users = $this->user->getUsersByPaging($page_param);

		//获取user的角色列表
		foreach ($users['data'] as $key => $user) {

			$users['data'][$key]['roles'] = $user->roles()->get(['id', 'display_name']);
		}

		return $users;
	}

	/**
	* 编辑用户信息
	*
	* @param Illuminate\Http\Request $request
	*
	* @return array
	*/
	public function postEditUser(Request $request){
		$data = json_decode($request->input("data"));

		if(empty($data)){
			return ["status" => false, "info" => "参数错误"];
		}

		return $this->user->editUser($data, true);
	}

}