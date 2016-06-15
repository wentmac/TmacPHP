<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<link rel="apple-touch-icon-precomposed" href="/i/app-icon72x72@2x.png">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>管理员管理</title>
		<meta name="description" content="用户中心">
		<meta name="keywords" content="index">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="renderer" content="webkit">
		<meta name="apple-mobile-web-app-title" content="Amaze UI" />
		<link href="{STATIC_URL}common/amazeui/css/amazeui.css" rel="stylesheet" type="text/css">
		<link href="{STATIC_URL}common/amazeui/css/admin.css" rel="stylesheet" type="text/css">
		<link href="{$BASE_V}css/base.css" type="text/css" rel="stylesheet">
		<link href="{$BASE_V}css/page.css" type="text/css" rel="stylesheet">		
	</head>

	<body>
		<!--{template inc/header_paul}-->
		<div class="am-cf admin-main">
			<!--{template inc/sidebar_paul}-->
			<!-- content start -->
			<div class="admin-content" id="i_do_wrap">
				<div class="am-cf am-padding">
					<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">新增管理员</strong></div>
				</div>
				<hr/>
				<div class="am-container" style="margin-left: 0">
					<form class="am-form am-form-horizontal" id="form_user">
						<div class="am-form-group">
							<label for="username" class="am-u-sm-2 am-form-label">登录名</label>
							<div class="am-u-sm-4 am-u-end">
								<input class="am-radius" type="text" id="username" name="username" value="{$editinfo->username}" placeholder="管理员的登录姓名" minlength="3" required>
							</div>
							<small id="sm_username"></small>
						</div>
						
						<div class="am-form-group">
							<label for="" class="am-u-sm-2 am-form-label">真实姓名</label>
							<div class="am-u-sm-3 am-u-end">
								<div class="am-input-group">									
									<input type="text" class="am-form-field am-radius" id="nicename" name="nicename" value="{$editinfo->nicename}" placeholder="管理员的真实姓名" minlength="3" required>				
								</div>
								<small class="am-text-warning"></small>
							</div>
						</div>
						
						
						
						<div class="am-form-group">
							<label for="" class="am-u-sm-2 am-form-label">密码</label>
							<div class="am-u-sm-4 am-u-end">
								<div class="am-input-group">
									<input type="password" class="am-form-field am-radius" id="password" name="password" value="" placeholder="填新密码">
									<span class="am-input-group-label am-radius">不修改就留空</span>
								</div>
							</div>
						</div>

						<div class="am-form-group">
							<label for="" class="am-u-sm-2 am-form-label">权限分组</label>
							<div class="am-u-sm-8 am-u-end">							
								<div class="am-input-group">
									<select id="rank" class="am-dropdown" style="width: 150px;" name="rank">
             							{$admin_type_option}
            						</select>
            						
								</div>
							</div>							
						</div>
						
						<div class="am-form-group">
							<label for="" class="am-u-sm-2 am-form-label">email</label>
							<div class="am-u-sm-3 am-u-end">
								<div class="am-input-group">									
									<input type="text" class="am-form-field am-radius js-pattern-email" id="email" name="email" value="{$editinfo->email}" placeholder="管理员的联系邮箱">				
								</div>
								<small class="am-text-warning"></small>
							</div>
						</div>

						<hr>
						<div class="am-form-group" id="loading_box">
							<label for="" class="am-u-sm-2 am-form-label"></label>
							<div class="am-u-sm-2 am-u-end">
								<input type="hidden" value="{$uid}" name="uid" id="uid" />
								<button id="submit_i_do_item" type="submit" class="am-btn am-btn-primary am-btn-block am-radius" data-for-gaq="管理员-提交管理员">
									<i class="am-icon-check-circle"></i> 提　交</button>
							</div>
						</div>
					</form>
				</div>
				<hr/>
			</div>
			<!-- content end -->
		</div>
		<!--{template inc/footer_paul}-->
		
		
	</body>

</html>

<script type="text/javascript" src="{STATIC_URL}js/jquery/1.11.2/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="{STATIC_URL}common/amazeui/js/amazeui.min.js"></script>
<script type="text/javascript">
var index_url = '{MOBILE_URL}';
var static_url = '{STATIC_URL}';
var base_v = '{$BASE_V}';
var php_self = '{PHP_SELF}';
var postField = {};
</script>
		
<script type="text/javascript" src="{$BASE_V}js/common.js"></script>
<script type="text/javascript" src="{STATIC_URL}js/jquery-plugin/ui/minified/jquery.cookie-min.js"></script>
<script type="text/javascript" src="{$BASE_V}js/modal_html.js"></script>
<script type="text/javascript" src="{$BASE_V}js/user_detail.js"></script>		