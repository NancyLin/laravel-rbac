<?php

namespace App\Http\Middleware;

use Closure;
use Zizaco\Entrust\EntrustFacade as Entrust;
use Route, URL, Auth;

use App\Models\Permission;
use App\Repositories\PermissionRepository;

/**
* 验证权限中间件
* @package App\Http\Middleware
*/
class AuthPermission {

	/**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request $request
    * @param  \Closure $next
    * @param  string|null $guard
    *
    * @return mixed
    */
    public function handle($request, Closure $next){

    	//超级管理员，不进行权限判断
    	if(Auth::user()->is_super){
    		return $next($request);
    	}

    	$previous_url      = URL::previous();

        //当前路由
        $current_route = explode("\\", Route::getCurrentRoute()->getActionName());
        //当前路由使用的controller和method
        $route_name = end($current_route);
       
    	//没有对应的权限
    	if(!Auth::user()->can($route_name)){
            //判断是否存在该权限，没有则加入
            $permisson_repository = new PermissionRepository(new Permission());
            $permisson_repository->createPermissionIfNoExist($route_name, ["name"=>$route_name]);

    		//通过ajax传送数据，需要返回前台json
    		if($request->ajax()){
    			return response()->json([
    				'status' => false,
    				'code'	 => 403,
    				'info'	 => '你没有权限执行此操作'
    			]);
    		}else{
                
    			//返回没有权限页面
    			return view("errors.403", compact('previous_url','route_name'));
    		}
    	}

    	return $next($request);
    }
}