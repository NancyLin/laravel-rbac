<?php
namespace App\Presenters;

use Route;
use App\Models\Permission;
use App\Models\Role;

/**
 * Class RbacPresenter
 * 主要用于确认rbac布局菜单栏的active状态，如果页面布局不需要菜单的，可以删掉view模板文件相应的页面布局，删除该文件。
 *
 * @package namespace App\Presenters
 */
class RbacPresenter {

	/**
	* 设置rbac菜单中活动状态
	*
	* @param null $name
	*
	* @return string
	*/
	public function activeMenuByRoute($name = null){

		$currentRouteName = Route::currentRouteName();
		$routeSetions     = explode(".", $currentRouteName);

		if(isset($routeSetions[1]) && $routeSetions[1] === $name){
			return 'active';
		}

		return '';
	}

	/**
	* 回调权限列表无限级联树的html
	* 
	* @param int    $level           地址传参，树的层次，第一级为0
	* @param string $permission_html 地址传参，权限树的html
	* @param p_id   $p_id            权限父类ID
	* 
	* @return string 
	*/
	public static function permissionToTree(&$level, &$permission_html, $p_id = 0){

		$permissions = Permission::select(['id','name','display_name'])
					   ->where('p_id', $p_id)
					   ->orderBy('sort', 'asc')
					   ->orderBy('id', 'asc')
					   ->get();
					   
		foreach ($permissions as $permission) {
			$permission_html .= "<div class='tree-permission col-md-".(12-$level)." col-md-offset-{$level}'>".
                                    "<a href='javascript:;' class='display-sub-permission-toggle'>".
                                        "<span class='glyphicon glyphicon-minus'></span>".
                                    "</a>".
                                	"<input type='checkbox' name='permissions[]' value='{$permission['id']}' class='top-permission-checkbox'/>".
                                    "<label>&nbsp;&nbsp;{$permission['display_name']}&nbsp;&nbsp;({$permission['name']})</label>".
                                "</div>";

            $level++;

            RbacPresenter::permissionToTree($level, $permission_html, $permission['id']);
            //最底层遍历结束后，返回上一层
            $level--;
		}

		return $permission_html;

	}

	/**
	* 角色列表多选选项
	* @return string
	*/
	public static function roleMultipleSelect(){
		$roles = Role::select(['id', 'display_name'])->get();

		$select_option = "";

		foreach ($roles as $role) {
			$select_option += "<option value='{$role['id']}'>{$role['display_name']}</option>";
		}

		return $select_option;
	}

	/**
	* 回调权限列表无限级联树的html
	* 
	* @param int    $level           地址传参，树的层次，第一级为0
	* @param string $permission_html 地址传参，权限树的html
	* @param string $row_class       地址传参，行class
	* @param p_id   $p_id            权限父类ID
	* 
	* @return string 
	*/
	public static function permissionToTreeTable(&$level, &$permission_html, &$row_class, $p_id = 0){

		$permissions = Permission::select(['id','name','display_name', 'created_at','description','is_menu'])
					   ->where('p_id', $p_id)
					   ->orderBy('sort', 'asc')
					   ->orderBy('id', 'asc')
					   ->get();
					   
		foreach ($permissions as $permission) {
			$row_class = $row_class == 'odd'? 'even' : 'odd';

			$permission_html .= "<tr role='row' class='{$row_class}'>".
									"<td>{$permission['id']}</td>".
									"<td>".
										"<div class='col-md-".(12-$level)." col-md-offset-{$level}'>".
											"<a href='javascript:;' class='show-sub-permissions' data-id='{$permission['id']}'><span class='glyphicon glyphicon-chevron-right'></span></a>".
											"<label>&nbsp;&nbsp;{$permission['display_name']}</label>".
										"</div>".
									"</td>".
									"<td>{$permission['name']}</td>".
									"<td>{$permission['description']}</td>".
									"<td>".($permission['is_menu'] == 1? "<span class='label label-danger'>是</span>":"<span class='label label-default'>否</span>")."</td>".
									"<td>{$permission['created_at']}</td>".
									"<td>".
										"<button class='btn btn-info btn-sm' onclick='openPermission(\"{$permission['id']}\");'>编辑</button> ".
										"<button class='btn btn-danger btn-sm' onclick='deletePermission(\"{$permission['id']}\", \"{$permission['name']}\", this);'>删除</button>".
									"</td>".
								"</tr>";

            $level++;

            RbacPresenter::permissionToTreeTable($level, $permission_html, $row_class, $permission['id']);
            //最底层遍历结束后，返回上一层
            $level--;
		}

		return $permission_html;

	}

	/**
	* 回调权限列表下拉选择option树的html
	* 
	* @param int    $level           地址传参，树的层次，第一级为0
	* @param string $permission_html 地址传参，权限树的html
	* @param p_id   $p_id            权限父类ID
	* 
	* @return string 
	*/
	public static function permissionSelectOption(&$level, &$permission_html, $p_id = 0){

		$permissions = Permission::select(['id','name','display_name'])
					   ->where('p_id', $p_id)
					   ->orderBy('sort', 'asc')
					   ->orderBy('id', 'asc')
					   ->get();
					   
		foreach ($permissions as $permission) {
			$option = $level == 0? "" : str_repeat("&nbsp;", $level*2) . "|-- ";
			
			$permission_html .= "<option value='{$permission['id']}'>".$option.$permission['display_name']." ({$permission['name']})</option>";

            $level++;

            RbacPresenter::permissionSelectOption($level, $permission_html, $permission['id']);
            //最底层遍历结束后，返回上一层
            $level--;
		}

		return $permission_html;

	}
}
