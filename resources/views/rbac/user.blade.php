@extends('layouts.app')

@section('content')
	<div class="contentpanel panel-email">
		<div class="row">
			@include('layouts.rbac_left_menu')

			<div class="col-sm-9 col-lg-10">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="table-responsive col-md-12">
							<table id="user-table" class="display" cellspacing="0" width="100%" data-href="{{ url('user/users') }}">
								<thead>
								</thead>
							</table><!--/.table .mb30-->
						</div><!--/.table-responsive /.col-md-12-->
					</div><!-- /.panel-body -->
				</div><!--/.panel-->
			</div><!-- /.col-sm-9 -->
		</div><!-- /.row -->
	</div><!-- /.contentpanel -->
	
	<!--新增修改用户模态框-->
	<div class="modal fade" id="user-edit-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dailog modal-lg">
			<div class="modal-content" ms-controller="user">
				<div class="modal-header">
					<button class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="user-edit-modal-title"></h4>
				</div><!--/.modal-header-->
				<form id="user-edit-modal-form">
					<div class="modal-body">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-3 control-label">用户名 <span class="asterisk">*</span></label>
								<div class="col-sm-6">
									<input type="text" ms-duplex="@name" data-id="name" class="form-control validate[required]">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">超级管理员</label>
								<div class="col-sm-6">
									<select ms-duplex="@is_super" data-id="is_super" class="form-control">
										<option value="0">否</option>
										<option value="1">是</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">所属角色</label>
								<div class="col-sm-6">
									<select id="roles-select-modal" ms-duplex="@roles" class="form-control" multiple="multiple" size='1'>
									</select>
								</div>
							</div>
							<input type="hidden" ms-duplex="@id" value=""/>
							<input type="hidden" id="edit-user-type" vaule="" />
						</div><!--/.form-horizontal-->	
					</div><!--/.modal-body-->
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
						<button type="submit" class="btn btn-primary" id="save-user-edit-btn">保存</button>
					</div><!--/.modal-footer-->
				</form>
			</div><!--/.modal-content-->
		</div><!--/.modal-dailog-->
	</div><!--/.modal fade-->
	<!--/新增修改角色模态框-->

	<!--基础路由-->
	<input type="hidden" id="base_url" value="{{ url() }}" />
@endsection

@section('javascript')
	@parent
	<script src="{{ asset('js/rbac.js') }}"></script>
	<script src="{{ asset('js/user.js?v=1') }}"></script>
	
@endsection