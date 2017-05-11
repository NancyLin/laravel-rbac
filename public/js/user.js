//基础路由
var base_url   = $("#base_url").val();
var user_table;
var roles = {};
var load_roles = false;
//role avalon 控制器
var user_avalon = avalon.define({
	$id: "user",
	id: "",
	name: "",
	is_super: "0",
	roles: []
});


/**
* 打开用户编辑面板
* @param int user_id 用户id
*/
function openUser(user_id){
	//加载角色列表
	if(!load_roles){
		Rbac.ajax.request({
			type:'GET',  
			href: base_url + "/load/roles-list",
			isShowSuccessNotice: false,
			async:false,//同步
			successFnc: function(return_data){

				$.each(return_data.roles, function(index, val) {
					 $("#roles-select-modal").append("<option value='"+val.id+"'>"+val.display_name+"</option>");
					 roles[val.id] = val.display_name;
				});

				//初始化select2插件
				$('#roles-select-modal').select2({ placeholder: '选择角色...',allowClear:true });

				load_roles = true;
			}, 
		});
	}

	if(user_id > 0){
		$("#edit-user-type").val("update");
		$("#user-edit-modal-title").html("编辑角色");

		//加载用户信息
		Rbac.ajax.request({
			type:'GET',  
			href: base_url + "/load/user",
			data: {user_id: user_id},
			isShowSuccessNotice: false,
			successFnc: function(return_data){
				$.each(return_data.user, function(index, val) {
					user_avalon[index] = val;
				});
			    $("#roles-select-modal").select2('val', return_data.user.roles);
			    //user_avalon.roles = return_data.user.roles;
			    console.log($("#roles-select-modal").val());
			}, 
		});
	}
	
	$("#user-edit-modal").modal("show");

}

/**
* 保存用户信息
*/
function saveUserEdit(){
	var dom_obj = $("#user-edit-modal-form");
	user_avalon.roles = $("#roles-select-modal").val();
	
	var save_btn_dom = $("#save-user-edit-btn");
	save_btn_dom.attr('disabled', true);

	Rbac.ajax.request({
		type:'POST', 
		data: {data:JSON.stringify(user_avalon.$model)}, 
		href: base_url + "/user/edit-user", 
		successFnc: function(return_data){
			
			$("#user-edit-modal").modal('hide');

			save_btn_dom.attr('disabled', false);

			//重新刷新datatable数据
			user_table.ajax.reload(null, false);
			//清掉modal输入框数据
			$("input[ms-duplex],select[ms-duplex]", dom_obj).val('');
			$("#roles-select-modal").select2('val', '');
		},
		errorFnc: function(){
			save_btn_dom.attr('disabled', false);
		}
	});
}

$(function(){
	
	var datatable_url = $("#user-table").data('href');
	//加载数据
	user_table = $("#user-table").DataTable($.extend({}, datatable_option, {
		"ajax": {
			"url": datatable_url,
			"data": {"test":"1"}
		},
		"columns": [
			{"title": "ID", "data": 'id'},
			{"title": "用户名", "data": 'name'},
			{"title": "邮箱", "data": 'email'},
			{
				"title": "超级管理员", 
				"data": 'is_super',
				"render": function(data, type, full, meta){
					return data == 1? "<span class='label label-success'>是</span>" :
									  "<span class='label label-default'>否</span>";
				}
			},
			{
				"title": "所属角色",
				"data": 'roles',
				"render": function(data, type, full, meta){

					var roles_html = "";

					$.each(data, function(index, val) {
						roles_html += "<span class='label label-success'>" + val.display_name + "</span> ";
					});

					return roles_html;
				} 
			},
			{"title": "创建时间", "data": 'created_at'},
			{
				"title": "操作",
				"render": function(data, type, full, meta){
					return "<button class='btn btn-info btn-sm' data-id='"+full.id+"' onclick='openUser(\""+full.id+"\");'>编辑</button>";
				}
			},

		] 
	}));//DataTable

	//用户编辑验证插件初始化
	validationInit($("#user-edit-modal-form"), saveUserEdit);
});

