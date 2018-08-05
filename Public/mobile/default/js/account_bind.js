var global_timer = 0;

$(function() {
    login.init();
});

var login = {

	init: function() {
		var _this = this;
		$("#mycart_user").show();
		_this.check_mobile();
	},
	check_mobile: function() {
		var _this = this;
		$("#submit_user_tel").click(function() {
			var tel = $("#tel").val();
			if (!/^1([3]|[5]|[8]|[4]|[7])[0-9]{9}$/.test(tel)) {
				alert('手机号码格式不正确！');
				return false;
			}
			var dataParam = {
				mobile: tel
			};
			$.ajax({
				type: "get",
				url: mobile_url + 'account/check_mobile_isreg',
				data: dataParam,
				dataType: "jsonp",
				cache:false,
				success: function(data) {
					if (data.success == false) { //已经注册过
						//_this.set_login(tel);
						$('#tel_notice').html('* 您的手机号码系统已经存在,请更换一个').attr({ style: "color:red" });
					} else { //新用户
						_this.set_password(tel, 2);
					}
				}
			});
		});
	},
	_send_sms: function(mob, send_type) { //发短信验证码
		var _this = this;
		var sending = $("#resend_tel_code").attr('sending');
		if (sending == 1) {
			return;
		}
		var dataParam = {
			sms_type: send_type,
			mobile: mob,
			verify_code: ''
		};
		$.ajax({
			type: "POST",
			url: mobile_url + 'account/send_verify_code',
			dataType: "jsonp",
			data: dataParam,
			cache:false,
			success: function(data) {
				if (data.success == true) {
					$("#resend_tel_code").attr('sending', '1');
					$("#tel_second").show();
					global_timer = 60;
					_this._timer_sendsms();
				} else {
					alert(data.message);
				}
			}
		});
	},
	_timer_sendsms: function() { //发短信倒记时
		if (global_timer > 0) {
			$("#tel_second").text(global_timer);
			global_timer--;
			setTimeout(function(){login._timer_sendsms();}, 1000);
		} else {
			$("#resend_tel_code").removeAttr('sending');
			$("#tel_second").hide();
		}
	},
	set_password: function(mob, isreg) { //isreg为0为重置密码 1为新注册 2为绑定手机号
		var _this = this;
		var tmp = '<p class="mycart_tle">已发送验证码到你的手机  <a id="speak_code" class="right hide">收不到?</a></p>' +
			'<div class="mycart_user_content">' +
			'<p>手机号码&nbsp;&nbsp;&nbsp;&nbsp;{mobile} <span id="resend_tel_code" class="right">重新发送 <em id="tel_second" style="display: none;">1</em></span> </p>' +
			'<p class="mycart_input_p rel">' +
			'<label for="code">验证码</label>' +
			'<input type="tel" maxlength="6" id="code" name="code" placeholder="填写验证码"><input type="hidden" value="{isreg}"> </p>' +
			'<p class="mycart_input_p rel hide">' +
			'<label for="code_pwd">设置密码</label>' +
			'<input type="password" id="code_pwd" name="code_pwd" placeholder="下次可用手机号+密码登录"> </p><a id="submit_tel_code" class="btnok">确认</a></div>';
		tmp = tmp.replace('{mobile}', mob);
		tmp = tmp.replace('{isreg}', isreg);
		$("#mycart_user").html(tmp);
		if ( isreg == 0 ) {
			var send_type = 2;
		}  else if ( isreg ==1 ) {
			var send_type = 1;
		} else if ( isreg == 2 ) {
			var send_type = 6;
		}		

		$("#resend_tel_code").off('click').on('click', function() { //发短信验证码方法
			_this._send_sms(mob, send_type);
		});

		$("#resend_tel_code").trigger('click'); //自动发1条短信验证码

		$("#submit_tel_code").off('click').on('click', function() { //提交
			var code = $.trim($("#code").val());
			if (!/^\d{6}$/.test(code)) {
				alert('短信验证码应该是6位纯数字！');
				$("#code").focus();
				return false;
			}			
			var pwd = $.trim($("#code_pwd").val());
			if (pwd != '' && pwd.length < 6) {
				alert('密码至少6位以上！');
				$("#code_pwd").focus();
				return false;
			}			
			var dataParam = {
				mobile: mob,
				pwd: pwd,
				sms_captcha: code,
				sms_type: send_type
			};
			var url = mobile_url;
			if (isreg == 0) { //脰脴脰脙脙脺脗毛
				url += 'account/password';
			} else if ( isreg == 1 ) {
				url += 'account/register_do';
			} else if ( isreg == 2 ) {//绑定手机号
				url += 'account/bind_mobile';
			}
			$.ajax({
				type: "POST",
				url: url,
				dataType: "jsonp",
				data: dataParam,
				cache:false,
				success: function(data) {
					//console.log(data);
					if (data.success == true) {
						location.href=mobile_url+'member/home'; //鲁脡鹿娄脣垄脨脗脪鲁脙忙
					} else {
						alert(data.message);
					}
				}
			});

		});
	},
	set_login: function(mob) {
		var _this = this;
		var tmp = '<p class="mycart_tle">你的号码已注册，请输入密码登录</p>' +
			'<div class="mycart_user_content">' +
			'<p>手机号码&nbsp;&nbsp;&nbsp;&nbsp;{mobile} <span id="forgot_pwd" class="right">忘记密码</span> </p>' +
			'<p class="mycart_input_p rel">' +
			'<label for="pwd">登录密码</label>' +
			'<input type="password" id="pwd" name="pwd" placeholder="请输入你设置的宝身茶密码"> </p><a id="submit_tel_pwd" class="btnok">确认</a></div>';
		tmp = tmp.replace('{mobile}', mob);
		$("#mycart_user").html(tmp);
		$("#forgot_pwd").off('click').on('click', function() { //忘记密码就发短信重置
			_this.set_password(mob, 0);
		});
		$("#submit_tel_pwd").off('click').on('click', function() {
			var account_name = mob;
			var account_pwd = $.trim($("#pwd").val());
			if (account_pwd.length < 6) {
				alert('密码至少6位以上!');
				$("#pwd").focus();
				return false;
			}
			var dataParam = {
				username: account_name,
				password: account_pwd,
				expries: 1
			};
			$.ajax({
				type: "POST",
				url: mobile_url + 'account/login_do',
				dataType: "jsonp",
				data: dataParam,
				cache:false,
				success: function(data) {
					if (data.success == true) {
						location.reload(); //登录成功刷新页面
					} else {
						alert(data.message);
					}
				}
			});
		});
	}
};
