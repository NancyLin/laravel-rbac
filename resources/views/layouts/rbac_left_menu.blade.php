@inject('rbacPresenter', 'App\Presenters\RbacPresenter')

<div class="col-sm-3 col-lg-2">
	<ul class="nav nav-pills nav-stacked nav-email">
		<li class="{{ $rbacPresenter->activeMenuByRoute('user') }}">
			<a href="{{ url('user') }}">
				<span class="badge pull-right"></span>
				<i class="fa fa-user"></i> 用户管理
			</a>
		</li>
		<li class="{{ $rbacPresenter->activeMenuByRoute('role') }}">
			<a href="{{ url('role') }}">
				<span class="badge pull-right"></span>
				<i class="fa fa-users"></i> 角色管理
			</a>
		</li>
		<li class="{{ $rbacPresenter->activeMenuByRoute('permission') }}">
			<a href="{{ url('permission') }}">
				<span class="badge pull-right"></span>
				<i class="fa fa-key"></i> 权限管理
			</a>
		</li>
	</ul>
</div><!-- /.col-sm-3 -->