var Rbac = window.Rbac || {};

/**
* 常用AJAX
* @module Rbac.common
*/
(function(Rbac){

	Rbac.ajax = {
		/**
		* ajax 请求
		* @param params {type:'POST', data:请求参数, href:ajax请求url, dataType:'JSON',async:是否异步, operateTitle:操作提示, isShowSuccessNotice:是否显示操作成功提示, isShowFailNotice:是否显示操作失败提示,successFnc:成功回调, errorFnc:失败回调}
		* 
		*/
		request: function(params){
			var params 	  = params || {},
				_type 	  = params.type || 'POST',
				_dataType = params.dataType || 'JSON',
				_data 	  = params.data || {},
				_async    = undefined === params.async? true : params.async,
				_successFnc = params.successFnc || function(){},
				_operateTitle 		 = params.operateTitle || '操作',
				_errorFnc 			 = params.errorFnc || function(){},
				_isShowSuccessNotice = undefined === params.isShowSuccessNotice? true : params.isShowSuccessNotice,
				_isShowFailNotice 	 = undefined === params.isShowFailNotice? true : params.isShowFailNotice;

				//增加token
				if(undefined === _data._token) _data['_token'] = $("meta[name='csrf-token']").attr('content');

				$.ajax({
					url: params.href,
					type: _type,
					data: _data,
					dataType: _dataType,
					async: _async
				}).done(function (data) {
					if(data.status == true){

						if(_isShowSuccessNotice) $.growl.notice({title:_operateTitle, message: data.info});
						_successFnc(data)

						return true;	
					}
					//操作失败
					if(_isShowFailNotice) $.growl.error({title:_operateTitle, message: data.info});
					_errorFnc()
					
				}).fail(function(){
					if(_isShowFailNotice) $.growl.error({title:_operateTitle, message: "服务器请求错误"});
				});
		},

		/**
		* 删除单条记录
		* @param params {type:'POST', data:请求参数, href:ajax请求url, dataType:'JSON',async:是否异步, confirmTitle:确认提示, operateTitle:操作提示, isShowSuccessNotice:是否显示操作成功提示, isShowFailNotice:是否显示操作失败提示,successFnc:成功回调, errorFnc:失败回调}
		*/
		delete: function(params){
			var params = params || {},
				_confirmTitle = params.confirmTitle || '确定删除该记录吗？',
				_this = this;

			if(!confirm(params.confirmTitle)){
				return false;
			}

			if(undefined !== params.btn_dom) $(params.btn_dom).attr('disabled', true);

			if(params.type == undefined){
				params.type = 'DELETE';
			}
			_this.request(params);

		},

	};

})(Rbac);
