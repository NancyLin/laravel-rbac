@extends('layouts.app')

@section('content')
	<div class="contentpanel panel-email">
		<div class="row">
			@include('layouts.rbac_left_menu')

			<div class="col-sm-9 col-lg-10">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="pull-right">
							<div class="btn-group mr10">
								<button id="role-add" class="btn btn-primary tooltips" data-toggle="tooltip" data-original-title="新增">
									<i class="glyphicon glyphicon-plus"></i> 
								</button>
							</div><!--/.btn-group .mr10-->
						</div><!--/.pull-right-->
						<div class="table-responsive col-md-12">
							<table id="role-table" class="display" cellspacing="0" width="100%" data-href="{{ url('role/roles') }}">
								<thead>
								</thead>
							</table><!--/.table .mb30-->
						</div><!--/.table-responsive /.col-md-12-->
					</div><!-- /.panel-body -->
				</div><!--/.panel-->
			</div><!-- /.col-sm-9 -->
		</div><!-- /.row -->
	</div><!-- /.contentpanel -->

	<!--新增修改角色模态框-->
	<div class="modal fade" id="role-edit-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" ms-controller="role">
				<div class="modal-header">
					<button class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="role-edit-modal-title">编辑角色</h4>
				</div><!--/.modal-header-->
				<form id="rote-edit-modal-form">
					<div class="modal-body">
						<div class="form-horizontal">

							<div class="form-group">
								<label class="col-sm-3 control-label">角色标识 <span class="asterisk">*</span></label>
								<div class="col-sm-6">
									<input type="text" ms-duplex="@name" class="form-control validate[required] validate[custom[onlyLetterNumber]]">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">显示名称</label>
								<div class="col-sm-6">
									<input type="text" ms-duplex="@display_name" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">说明</label>
								<div class="col-sm-6">
									<input type="text" ms-duplex="@description" class="form-control">
								</div>
							</div>
							<input type="hidden" ms-duplex="@id" vaule="" />
							<input type="hidden" id="edit-role-type" vaule="" />
						</div><!--/.form-horizontal-->	
					</div><!--/.modal-body-->
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
						<button type="submit" class="btn btn-primary" id="save-role-edit-btn">保存</button>
					</div><!--/.modal-footer-->
				</form>
			</div><!--/.modal-content-->
		</div><!--/.modal-dialog-->
	</div><!--/.modal fade-->
	<!--/新增修改角色模态框-->
	
	<!--设置用户权限模态框-->
	<div class="modal fade" id="role-permission-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">设置用户权限</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					<div id="permission-tree" class="panel-body panel-body-nopadding">
					</div>
					<input type="hidden" id="role-id" value="" />
				</div><!--/.modal-body-->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
					<button type="button" id="permission-tree-save" class="btn btn-primary">保存</button>
				</div><!--/.modal-footer-->
			</div><!--/.modal-content-->
		</div><!--/.modal-dialog-->	
	</div><!--/.modal-->
	<!--/设置用户权限模态框-->
	
	<!--基础路由-->
	<input type="hidden" id="base_url" value="{{ url() }}" />

@endsection

@section('javascript')
	@parent
	<script src="{{ asset('js/rbac.js') }}"></script>
	<script src="{{ asset('js/role.js?v=3') }}"></script>
	
@endsection