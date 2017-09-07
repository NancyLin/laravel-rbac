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
								<button id="permission-add" class="btn btn-primary tooltips" data-toggle="tooltip" data-original-title="新增">
									<i class="glyphicon glyphicon-plus"></i> 
								</button>
							</div><!--/.btn-group .mr10-->
						</div><!--/.pull-right-->
						<div class="table-responsive col-md-12">
							<table id="permission-table" class="display dataTable no-footer" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>显示名称</th>
										<th>权限标识</th>
										<th>说明</th>
										<th>是否菜单</th>
										<th>创建时间</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table><!--/.table .mb30-->
						</div><!--/.table-responsive /.col-md-12-->
					</div><!-- /.panel-body -->
				</div><!--/.panel-->
			</div><!-- /.col-sm-9 -->
		</div><!-- /.row -->
	</div><!-- /.contentpanel -->

	<!--新增修改角色模态框-->
	<div class="modal fade" id="permission-edit-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" ms-controller="permission">
				<div class="modal-header">
					<button class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="permission-edit-modal-title"></h4>
				</div><!--/.modal-header-->
				<form id="permission-edit-modal-form">
					<div class="modal-body">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-3 control-label">父类权限组 <span class="asterisk">*</span></label>
								<div class="col-sm-6">
									<select id="permission-parent-select" ms-duplex="@p_id" class="form-control validate[custom[integer]]">
										<option value="0">顶级权限</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">权限标识 <span class="asterisk">*</span></label>
								<div class="col-sm-6">
									<input type="text" ms-duplex="@name" class="form-control validate[required]">
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
							<div class="form-group">
								<label class="col-sm-3 control-label">是否菜单</label>
								<div class="col-sm-6">
									<select ms-duplex="@is_menu" class="form-control">
										<option value='0'>否</option>
										<option value='1'>是</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">排序</label>
								<div class="col-sm-6">
									<input type="text" ms-duplex="@sort" class="form-control validate[custom[integer]]">
								</div>
							</div>
							<input type="hidden" ms-duplex="@id" vaule="" />
							<input type="hidden" id="edit-permission-type" vaule="" />
						</div><!--/.form-horizontal-->	
					</div><!--/.modal-body-->
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
						<button type="submit" class="btn btn-primary" id="save-permission-edit-btn">保存</button>
					</div><!--/.modal-footer-->
				</form>
			</div><!--/.modal-content-->
		</div><!--/.modal-dialog-->
	</div><!--/.modal fade-->
	<!--/新增修改角色模态框-->
	
	<!--基础路由-->
	<input type="hidden" id="base_url" value="{{ url() }}" />

@endsection

@section('javascript')
	@parent
	<script src="{{ asset('js/rbac.js') }}"></script>
	<script src="{{ asset('js/permission.js?v=4') }}"></script>
	
@endsection