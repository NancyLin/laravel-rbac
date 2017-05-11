<?php

namespace App\Http\Controllers;

/**
* 后台管理通用父类，所有需要走权限控制的都继承该类
*
* @package App\Http\Controller
*/
class AdminController extends Controller {

	/**
	* 构造函数，权限中间件控制
	*/
	public function __construct(){
		# TODO 权限中间件
		$this->middleware("permission");
	}

	/**
    * 分页参数过滤，多个模块通用
    *
    * @param Illuminate\Http\Request $request
    *
    * @return array
    */
    public function filterPageParams($request){
    	$params = array();
    	//第一条数据的起始位置，比如0代表第一条数据
    	$params['start'] = intval($request->input('start'));
    	//每页显示的条数
    	$params['length']= intval($request->input('length'));
    	//绘制计数器
    	$params['draw']  = intval($request->input('draw'));

    	//需要排序的列,只排一列
    	$order = $request->input('order')[0];
    	
    	$order_column = intval($order['column']);
    	//根据列的索引
    	$params['order'] = addslashes($request->input('columns')[$order_column]['data']);
    	//升降序
    	$params['dir'] = 'asc' == $order['dir']? 'asc' : 'desc';

    	return $params;
    }
}