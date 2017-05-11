# Laravel5.1基于Entrust扩展包实现的通用RBAC权限控制模块（迁移到其他项目中的方法）

注意，如果要迁移到RBAC迁移到自己的项目中，是基于你的项目已经有laravel开箱即用的用户模块了。

如果要把项目下到自己的电脑上测试，需要在.env配置好自己的数据库相关参数。

如果是linux系统，需要将storage和bootstrap/cache 目录更改为可读写可执行的权限。

### 要把RBAC模块迁移到自己的项目中方法如下：

**（1）安装配置Entrust扩展包**

具体的安装配置方法请看以下链接，这里不做说明

[https://github.com/Zizaco/entrust](https://github.com/Zizaco/entrust)

**（2）安装配置好后，根据我们自己的实际需求，更改相关的数据表字段。**

```
# 用户表增加是否超级管理员
ALTER TABLE `users` ADD COLUMN `is_super` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否超级管理员';

# 权限表增加父类菜单显示、
ALTER TABLE `permissions` ADD COLUMN `p_id` INT(10) NOT NULL DEFAULT 0  COMMENT '父类菜单ID' AFTER `id`;
# 权限表增加是否菜单显示
ALTER TABLE `permissions` ADD COLUMN `is_menu` TINYINT(1) NOT NULL DEFAULT 0  COMMENT '是否菜单显示' AFTER `description`;
# 权限表增加是否菜单显示
ALTER TABLE `permissions` ADD COLUMN `sort` TINYINT(4) NOT NULL DEFAULT 0  COMMENT '排序' AFTER `is_menu`;

# 权限表中display_name字段不允许为空
ALTER TABLE `permissions` Modify COLUMN `display_name` VARCHAR(255) NOT NULL DEFAULT ''  COMMENT '显示名称';
# 权限表中description字段不允许为空
ALTER TABLE `permissions` Modify COLUMN `description` VARCHAR(255) NOT NULL DEFAULT ''  COMMENT '描述';

```
**（3）更改配置文件**

- config/entrust.php，指定相应的 role 和 permission 的 model。

```
'role' => 'App\Models\Role',

'permission' => 'App\Models\Permission',
```
- .env， 更改CACHE_DRIVER

```
CACHE_DRIVER=array
```
**（4）注册权限控制中间件**

此份demo中，我们对于需要进行权限控制的控制器都采用中间件方式，需要在 app/Http/Kernel.php 的 $routeMiddleware 中注册中间件。

```
$routeMiddleware = [
 ....
  //权限中间件
  'permission' => \App\Http\Middleware\AuthPermission::class,
]
```
**（5）在 User 模型中引入EntrustUserTrait**

在此demo中，在 app\User.php 中增加以下代码：

```
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    ...
    use EntrustUserTrait;
    ...
}
```
**（6）注册相关模块的路由**

在app\Http\routes.php 中注册相关的路由

```
Route::group(['middleware' => ['auth']], function(){
	Route::get('home', 'HomeController@index');
	Route::controller('check', 'CheckController');
	Route::controller('load', 'LoadBaseDataController');

	Route::get('user', 'Rbac\UserController@index');
	Route::controller('user', 'Rbac\UserController');

	Route::get('role', 'Rbac\RoleController@index');
	Route::controller('role', 'Rbac\RoleController');

	Route::get('permission', 'Rbac\PermissionController@index');
	Route::controller('permission', 'Rbac\PermissionController');
});
```

**（7）将RBAC相关模块的代码文件或文件夹对应地拷贝到自己的项目中**

具体相关模块文件或文件夹如下：

- 拷贝 ==app\Http\Controllers\Rbac== 下的所有相关模块控制器。
- 拷贝 ==app\Http\Controllers\AdminController.php== 管理通用控制器，要走权限控制中间件的控制器，都可以继承该类。
- 拷贝 ==app\Http\Controllers\CheckController.php== 验证数据控制器。
- 拷贝 ==app\Http\Controllers\LoadBaseDataController.php== 加载基础数据控制器。
- 拷贝 ==app\Http\Middleware\AuthPermission.php== 权限控制中间件。
- 拷贝 ==app\Models\Role.php== 角色model。
- 拷贝 ==app\Models\Permission.php== 权限model。
- 拷贝 ==app\Presenters\RbacPresenter.php== Rbac视图逻辑处理类。
- 拷贝 ==app\Repositories\Repository.php== 数据model基础逻辑处理类。
- 拷贝 ==app\Repositories\UserRepository.php== 用户model基础逻辑处理类。
- 拷贝 ==app\Repositories\RoleRepository.php== 角色model基础逻辑处理类。
- 拷贝 ==app\Repositories\PermissionRepository.php== 权限model基础逻辑处理类。
- 拷贝 ==public\css== 下的所有文件和文件夹，前台使用的插件的一些css文件（有些直接使用插件的官网地址，如果加载太慢，可将其下载到本地项目）。
- 拷贝 ==public\js== 下的所有文件和文件夹，前台使用的插件的一些js文件（有些直接使用插件的官网地址，如果加载太慢，可将其下载到本地项目）以及相关模块的js文件。
- 拷贝 ==resources\views\rbac== 文件夹，里面包含相关模块的view模板文件。
- 拷贝 ==resources\views\errors== 文件夹，里面包含相关模块没有权限时跳转的view模板文件。
- 拷贝 ==resources\views\layouts== 文件夹，里面包含相关模块的通用模块的view模板文件。







