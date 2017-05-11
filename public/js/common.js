var datatable_option = {
	"autoWidth": true,//自动宽度
	"deferRender": true,//延迟加载
	"info": true,//信息显示字段
	"lengthChange": true,//更改表的分页显示长度
	"ordering": true,//控制排序
	"paging": true,//显示分页
	"processing": true,//处理显示信息
	"scrollX": "100%",
	"scrollY": "100%",
	"searching": false,//是否可查询
	"serverSide": true,//服务器端处理
};

/**
* 验证插件初始化
* @param dom dom_obj
* @param function callback_fnc 验证成功提交后的回调函数
*/
function validationInit(dom_obj, callback_fnc){
	$(dom_obj).validationEngine('attach',{
		onValidationComplete: function(form, status){
			console.log(status);
			if(status == true){
				callback_fnc();
			}
		}
	});
}