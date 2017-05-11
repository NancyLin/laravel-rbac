<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\User;

/**
* 验证参数通用类
*
* @package App\Http\Controller
*/
class CheckController extends Controller {

	/**
	* 构造函数
	*/
	public function __construct(){}

	/**
    * 验证角色标识唯一性
    *
    * @param Illuminate\Http\Request $request
    *
    * @return string {"fieldId", "true/false"}
    */
    public function getCheckRoleOnly(Request $request){

        return $this->checkDataOnly($request, new Role(), "name");

    }

    /**
    * 验证用户标识唯一性
    *
    * @param Illuminate\Http\Request $request
    *
    * @return string {"fieldId", "true/false"}
    */
    public function getCheckUserOnly(Request $request){

        return $this->checkDataOnly($request, new User(), "name");

    }

    /**
    * 验证数据是否唯一通用方法
    *
    * @param Illuminate\Http\Request $request
    * @param Model                   $model 对应的数据model
    * @param string                  $search_column
    *
    * @return string {"fieldId", "true/false"}
    */
    private function checkDataOnly(Request $request, $model, $search_column){

        $check_result[0] = $request->input("fieldId");

        $has_same = $model->where("name", $request->input("fieldValue"))->count();
        //已经存在的，验证不通过
        $check_result[1] = $has_same? false : true;

        return json_encode($check_result);
    }

}