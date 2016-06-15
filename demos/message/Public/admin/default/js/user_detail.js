/*
			Create By wentmac @2015

                   _ooOoo_
                  o8888888o
                  88" . "88
                  (| -_- |)
                  O\  =  /O
               ____/`---'\____
             .'  \\|     |//  `.
            /  \\|||  :  |||//  \
           /  _||||| -:- |||||-  \
           |   | \\\  -  /// |   |
           | \_|  ''\---/''  |   |
           \  .-\__  `-`  ___/-. /
         ___`. .'  /--.--\  `. . __
      ."" '<  `.___\_<|>_/___.'  >'"".
     | | :  `- \`.;`\ _ /`;.`/ - ` : | |
     \  \ `-.   \_ __\ /__ _/   .-` /  /
======`-.____`-.___\_____/___.-`____.-'======
                   `=---='
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
			佛祖保佑       永无BUG

*/

$(document).ready(function() {
	

	//  $('#triggerUpload').click(function() {
	//      manualuploader.fineUploader('uploadStoredFiles');
	//  });

	//todo 库存stock默认数据展示	
	/*保存商品*/
	$("#submit_i_do_item").bind("click", function() {

	});


    //表单验证
    $('#form_user').validator({
        onValid: function(validity) {
        	
            $(validity.field).removeClass('has_err').addClass('validate_success').closest('.am-form-group').find('.am-alert').hide();
        },

        onInValid: function(validity) {
            var $field = $(validity.field);
            var $group = $field.closest('.am-u-end');
            var $alert = $group.find('.am-alert');
            // 使用自定义的提示信息 或 插件内置的提示信息
            var msg = $field.data('validationMessage') || this.getValidationMessage(validity);
			
            if (!$alert.length) {
            	
                $alert = $('<div class="am-alert am-alert-danger"></div>').hide().appendTo($group);
                
            }

            $alert.html(msg).show();

            $field.removeClass('validate_success').addClass('has_err');
            //return false;
        },

        submit: function(e) {
            if (this.isFormValid() === false) return false;
            var that = $("#btn_submit");
            if (that.hasClass("isLoading")) {
                return false;
            }
			$.AMUI.progress.start();

			var that = $(this);
			//提交后必选校验		
			if (!check_required_options.init($(this))) {
				$.AMUI.progress.done();
				return false;
			}
			var dataParam = postField;
			$.ajax({
				type: "POST",
				url: php_self + '?m=user.save',
				dataType: "json",
				data: dataParam,
				cache: false,
				success: function(data) {
					//console.log(data);
					if (data.success == true) {
						M._alert('成功');						
						$.AMUI.progress.done();
						location.href = php_self + "?m=user.index";
					} else {
						$.AMUI.progress.done();
						M._alert(data.message);
						that.removeClass("isLoading").html("提交");
						$.AMUI.progress.done();
						return false;
					}
				}
			});
			return false;
        }
    });

});


var isIe = navigator.userAgent.toLowerCase().match(/msie ([\d.]+)/) ? true : false;
if (isIe) {
	var position_top = 0;
} else {
	var position_top = -23;
}

//校验必选
var check_required_options = {
	init: function(_this) {
		if (_this.hasClass("isLoading")) {
			return false;
		}
		_this.addClass("isLoading").html("loading...");
			
		postField.username = $('#username').val();
		postField.password = $('#password').val();
		postField.nicename = $('#nicename').val();
		postField.email = $('#email').val();		
		postField.rank = $("#rank").val();		
		postField.uid = $("#uid").val();						
		return true;	
	}
}

var goods = {	
	initialize: function() {
		var that = this;
	},
	spec_choice: function() {
		var that = this;
		$('#i_do_wrap .select2-choice').off('click').on('click', function(event) {
			var choice_dom = $(this).parent().parent().parent();
			that.current_choice_dom = choice_dom;
			that.current_choice_dom.find('.select2-focusser').show().val('').focus();
			$(this).hide();
			//$('#i_do_wrap .js-add-sku-atom .close').click();
			event.stopImmediatePropagation();
			event.preventDefault();

			$('div.webui-popover').not('.webui-popover-fixed').removeClass('in').hide();
		});
	}
	
}