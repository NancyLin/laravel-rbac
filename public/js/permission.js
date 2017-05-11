//基础路由
var base_url = $("#base_url").val();
var permission_table;
var load_permission_select = false;


//permission avalon 控制器
var permission_avalon = avalon.define({
	$id: "permission",
	id: "",
	p_id: 0,
	name: "",
	display_name: "",
	description: "",
	is_menu: 0,
	sort: 0
});

/**
* 添加权限事件新增绑定
*/
$("#permission-add").on("click", function(event){

	$("#edit-permission-type").val("insert");
	$("#permission-edit-modal-title").html("新增权限");
	openPermission(0);
	
});

/**
* 开启编辑权限的modal
* @param int permission_id 权限ID
*/
function openPermission(permission_id){
	if(!load_permission_select){
		Rbac.ajax.request({
			type:'GET',  
			href: base_url + "/load/permission-options",
			isShowSuccessNotice: false,
			async:false,//同步
			successFnc: function(return_data){
				$("#permission-parent-select").append(return_data.permission_options)

				load_permission_select = true;
			}, 
		});
	}

	//如果permission_id大于0，代表要更新权限数据
	if(permission_id > 0){

		$("#edit-permission-type").val("update");
		$("#permission-edit-modal-title").html("编辑权限");

		Rbac.ajax.request({
			type:'GET',  
			href: base_url + "/load/permission/"+permission_id,
			isShowSuccessNotice: false,
			successFnc: function(return_data){
				$.each(return_data.permission, function(index, val) {
					permission_avalon[index] = val;
				});
			}, 
		});

	}

	$("#permission-edit-modal").modal("show");
}

/**
* 加载权限树列表
*/
function loadPermissionTreeTable(){
	//console.log('加载');
	Rbac.ajax.request({
		type:'GET',  
		href: base_url + "/permission/permission-tree-table", 
		isShowSuccessNotice: false,
		successFnc: function(return_data){
			//console.log('加载2');
			//显示权限树
			$("#permission-table > tbody").html(return_data.permission_tree_table);

		}, 
	});
}

/**
* 保存权限数据
*/
function savePermission(){
	var dom_obj = $("#permission-edit-modal-form");

	var save_btn_dom = $("#save-permission-edit-btn");
	save_btn_dom.attr('disabled', true);

	Rbac.ajax.request({
		type:'POST', 
		data: {data:JSON.stringify(permission_avalon.$model), operate_type: $("#edit-permission-type").val()}, 
		href: base_url + "/permission/edit-permission", 
		successFnc: function(){

			save_btn_dom.attr('disabled', false);

			$("#permission-edit-modal").modal('hide');
			//重新刷新datatable数据
			loadPermissionTreeTable();
			//清掉modal输入框数据
			$("input[ms-duplex]", dom_obj).val('');
		},
		errorFnc: function(){
			save_btn_dom.attr('disabled', false);
		}
	});
}

/*
* 编辑权限模态关闭事件绑定，关闭后，清空模态框中的数据
*/
$("#permission-edit-modal").on("hidden.bs.modal",function(){

	$.map(permission_avalon.$model,function(index, val){
		permission_avalon[val] = "";
	});
	permission_avalon.p_id = 0;
	permission_avalon.is_menu = 0;
	permission_avalon.sort = 0;

	$("#save-permission-edit-btn").attr('disabled', false);

});

/**
* 删除权限
* 
* @param int    role_id   角色ID
* @param int    role_name 角色标识
* @param object btn_dom
*/
function deletePermission(permission_id, permission_name, btn_dom){

	Rbac.ajax.delete({
        href: base_url + "/permission/delete-permission/" + permission_id,
        confirmTitle: "注意：删除权限，相应的权限角色映射也会一并删除，确认是否删除 " + permission_name + " 角色？", 
        operateTitle: "删除权限",
        btn_dom: $(btn_dom),
        successFnc: function(){
        	$(btn_dom).attr('disabled', false);
        	//加载页面权限树表
			loadPermissionTreeTable();
        }, 
        errorFnc: function(){
        	$(btn_dom).attr('disabled', false);
        }
    });
}

$(function(){
	//加载页面权限数表
	loadPermissionTreeTable();

	//验证插件初始化
	validationInit($("#permission-edit-modal-form"), savePermission);

});