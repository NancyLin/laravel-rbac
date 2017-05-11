//基础路由
var base_url = $("#base_url").val();
var role_table;

//role avalon 控制器
var role_avalon = avalon.define({
	$id: "role",
	id: "",
	name: "",
	display_name: "",
	description: ""
});

/**
* 角色新增按钮点击事件绑定
*/
$("#role-add").on("click", function(event){
	$("#edit-role-type").val("insert");
	$("#role-edit-modal-title").html("新增角色");
	openRole(0);

});

/**
* 开启权限设置模态框，根据角色的权限列表，选择相关的权限树checkbox
*/
function openPermission(role_id){
	$("#role-permission-modal").modal("show");
	$("#role-id").val(role_id);

	//获取对应的角色的权限,并显示在权限树中
	Rbac.ajax.request({
		type:'GET',  
		href: base_url + "/role/role-permission",
		data: {"role_id": role_id},
		isShowSuccessNotice: false,
		successFnc: function(return_data){
			
			//根据role的权限，选择相应的checkbox
			$.each(return_data.role_permissions, function(index, val) {
				 $("#permission-tree input:checkbox[value='"+val+"']").prop('checked', true);
				 //console.log($("#permission-tree input:checkbox[value='"+val+"']").val());
			});

		}, 
	});
};

/**
* 加载权限列表
*/
function loadPermissionTree(){
	Rbac.ajax.request({
		type:'GET',  
		href: base_url + "/load/permission-tree", 
		isShowSuccessNotice: false,
		successFnc: function(return_data){
			//显示权限树
			$("#permission-tree").append(return_data.permission_tree);

		}, 
	});
};

/**
* 权限树保存按钮事件绑定
*/
$("#permission-tree-save").on("click", function(event){

	var that = $(this);
	//获取选择的权限
	var permission_ids = $("#permission-tree input:checkbox:checked").map(function(){
		return this.value;
	}).get();

	that.attr("disabled", true);
	Rbac.ajax.request({
		type:'POST',
		data:{"permission_ids": JSON.stringify(permission_ids), "role_id": parseInt($("#role-id").val())},
		href: base_url + "/role/role-permission-set", 
		successFnc: function(return_data){
			//关闭模态权限框
			$("#role-permission-modal").modal("hide");

		}, 
	});
});

/**
* 开启角色编辑面板
*
* @param int role_id 角色ID
*/
function openRole(role_id){
	//如果role大于0，代表要更新权限数据
	if(role_id > 0){

		$("#edit-role-type").val("update");
		$("#role-edit-modal-title").html("编辑角色");

		Rbac.ajax.request({
			type:'GET',  
			href: base_url + "/load/role/"+role_id,
			isShowSuccessNotice: false,
			successFnc: function(return_data){
				$.each(return_data.permission, function(index, val) {
					role_avalon[index] = val;
				});
			}, 
		});

	}

	$("#role-edit-modal").modal("show");
}

/**
* 保存编辑(新增修改)角色数据
*/
function saveRole(){

	var dom_obj = $("#rote-edit-modal-form");

	var save_btn_dom = $("#save-role-edit-btn");
	save_btn_dom.attr('disabled', true);

	Rbac.ajax.request({
		type:'POST', 
		data: {data:JSON.stringify(role_avalon.$model), operate_type: $("#edit-role-type").val()}, 
		href: base_url + "/role/create-role", 
		successFnc: function(return_data){

			save_btn_dom.attr('disabled', false);

			$("#role-edit-modal").modal('hide');
			//重新刷新datatable数据
			role_table.ajax.reload(null, false);

		},
		errorFnc: function(){
			save_btn_dom.attr('disabled', false);
		}
	});
}

/*
* 编辑角色模态关闭事件绑定，关闭后，清空模态框中的数据
*/
$("#role-edit-modal").on("hidden.bs.modal",function(){

	$.map(role_avalon.$model,function(index, val){
		role_avalon[val] = "";
	});

	$("#save-role-edit-btn").attr('disabled', false);

});

/**
* 权限设置模态关闭事件绑定，关闭后，所有的checkbox选择都去掉
*/
$("#role-permission-modal").on("hidden.bs.modal", function(){
	//清除所有checkbox选择
	$("#permission-tree input:checkbox").removeAttr('checked');
	$("#permission-tree-save").attr('disabled', false);
});

/**
* 删除角色
* 
* @param int    role_id   角色ID
* @param int    role_name 角色标识
* @param object btn_dom
*/
function deleteRole(role_id, role_name, btn_dom){

	Rbac.ajax.delete({
        href: base_url + "/role/delete-role/" + role_id,
        confirmTitle: "注意：删除角色，相应的角色用户和权限也会一并删除，确认是否删除 " + role_name + " 角色？", 
        operateTitle: "删除角色",
        btn_dom: $(btn_dom),
        successFnc: function(){
        	$(btn_dom).attr('disabled', false);
        	//重新刷新datatable数据
			role_table.ajax.reload(null, false);
        }, 
        errorFnc: function(){
        	$(btn_dom).attr('disabled', false);
        }
    });
}

$(function(){
	
	var datatable_url = $("#role-table").data('href');
	//加载数据
	role_table = $("#role-table").DataTable($.extend({}, datatable_option, {
		"ajax": {
			"url": datatable_url,
			"data": {"test":"1"}
		},
		"columns": [
			{"title": "ID", "data": 'id'},
			{"title": "角色标识", "data": 'name'},
			{"title": "显示名称", "data": 'display_name'},
			{"title": "说明", "data": 'description'},
			{"title": "创建时间", "data": 'created_at'},
			{
				"title": "操作", 
				"render": function(data, type, full, meta){
					return "<button class='btn btn-warning btn-sm' onclick='openPermission(\""+full.id+"\");'>权限</button> " +
						   "<button class='btn btn-primary btn-sm' onclick='openRole(\""+full.id+"\")'>编辑</button> " + 
						   "<button class='btn btn-danger btn-sm' onclick='deleteRole(\""+full.id+"\", \""+full.name+"\", this)'>删除</button>";
				}
			}

		] 
	}));//DataTable

	//验证插件初始化
	validationInit($("#rote-edit-modal-form"), saveRole);

	//加载权限树
	loadPermissionTree();
});