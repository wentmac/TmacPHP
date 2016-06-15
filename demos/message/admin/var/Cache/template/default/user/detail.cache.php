<?php if (!class_exists('template', false)) die('Access Denied');
0
|| self::check('default\user/detail.tpl', 'D:\Web\Witkey\wk\tuhao\trunk\admin\application\View\default\user\detail.tpl', 1461689677)
|| self::check('default\user/detail.tpl', 'D:\Web\Witkey\wk\tuhao\trunk\admin\application\View\default\inc/header_paul.tpl', 1461689677)
|| self::check('default\user/detail.tpl', 'D:\Web\Witkey\wk\tuhao\trunk\admin\application\View\default\inc/sidebar_paul.tpl', 1461689677)
|| self::check('default\user/detail.tpl', 'D:\Web\Witkey\wk\tuhao\trunk\admin\application\View\default\inc/footer_paul.tpl', 1461689677)
;?>
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
		<link href="<?php echo STATIC_URL; ?>common/amazeui/css/amazeui.css" rel="stylesheet" type="text/css">
		<link href="<?php echo STATIC_URL; ?>common/amazeui/css/admin.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $BASE_V;?>css/base.css" type="text/css" rel="stylesheet">
		<link href="<?php echo $BASE_V;?>css/page.css" type="text/css" rel="stylesheet">		
	</head>

	<body>
<!-- header start -->
  <header class="am-topbar admin-header">
    <div class="am-topbar-brand">
      <img src="<?php echo $BASE_V;?>image/logo-blue.png">管理后台
    </div>
    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>
    <div class="am-collapse am-topbar-collapse" id="topbar-collapse">
      <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
       <!-- <li><a href="javascript:;"><span class="am-icon-envelope-o"></span> 收件箱 <span class="am-badge am-badge-warning">5</span></a></li>-->
        <li class="am-dropdown" data-am-dropdown>
          <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
            <span class="am-icon-users"></span> 我的发大财 <span class="am-icon-caret-down"></span>
          </a>
          <ul class="am-dropdown-content">
            <!--<li><a href="#"><span class="am-icon-user"></span> 资料</a></li>
            <li><a href="#"><span class="am-icon-cog"></span> 设置</a></li>-->
            <li><a href="<?php echo MOBILE_URL; echo PHP_SELF; ?>?m=account.loginout"><span class="am-icon-power-off"></span> 退出</a></li>
          </ul>
        </li>
        <li class="am-hide-sm-only"><a href="javascript:;" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span> <span class="admin-fullText">开启全屏</span></a></li>        
      </ul>
    </div>
  </header>
  <!-- header end --><div class="am-cf admin-main">    <style type="text/css">
    	.closed{
    		width: auto;
    	} 	
    	.closed #slider_bar{
    		display: inline;
    	}
    	#open_menu{display: none;}
    </style>
    
    	
    <!-- sidebar start -->
    <div class="admin-sidebar am-offcanvas closed" id="admin-offcanvas">
    	<div id="open_menu"  style="background-color: #fff;">
    		<a class="am-btn am-padding-sm am-btn-success" href="#" title="展开菜单">
    			<i class="am-icon-fw am-icon-angle-double-right"></i></a>
    	</div>
      <div id="slider_bar" class="am-offcanvas-bar admin-offcanvas-bar">
        <ul class="am-list admin-sidebar-list">
          <li><a href="#" id="close_menu"><span class="am-icon-fw am-icon-list-ul am-text-warning"></span><span class="am-text-warning">收起菜单</span><span class="am-icon-angle-double-left am-text-warning am-icon-fw am-fr am-margin-right-sm"></span></li></a>
          <?php if(is_array($menu_array)) foreach($menu_array AS $k => $sec1) { ?>
            <?php if(count($sec1['subname'])>0) { ?>
              <li class="admin-parent">
                <a class="am-cf" data-am-collapse="{target: '#collapse-nav-<?php echo $k;?>'}"><span class="<?php echo $sec1['class'];?>"></span><?php echo $sec1['title'];?><span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav-<?php echo $k;?>">
                    <?php if(is_array($sec1['subname'])) foreach($sec1['subname'] AS $kk => $sec2) { ?>
                    <li><a href="<?php echo $sec2['linktype'];?>" class="am-cf"><span class="<?php echo $sec2['class'];?>"></span><?php echo $sec2['nametype'];?></a></li>
                    <?php } ?>                    
                </ul>
              </li>            
            <?php } else { ?>
            <li><a href="<?php echo $sec1['link'];?>"><span class="<?php echo $sec1['class'];?>"></span><?php echo $sec1['title'];?></a></li>
            <?php } ?>          
          <?php } ?>
        </ul>        
        <div class="am-panel am-panel-default admin-sidebar-panel">
          <div class="am-panel-bd">
            <p><span class="am-icon-tag"></span> 最新消息</p>
            <p>云端产品库已经发布！
              <br></p>
          </div>
        </div>
      </div>
    </div>
    <!-- sidebar end --><!-- content start -->
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
								<input class="am-radius" type="text" id="username" name="username" value="<?php echo $editinfo->username;?>" placeholder="管理员的登录姓名" minlength="3" required>
							</div>
							<small id="sm_username"></small>
						</div>
						
						<div class="am-form-group">
							<label for="" class="am-u-sm-2 am-form-label">真实姓名</label>
							<div class="am-u-sm-3 am-u-end">
								<div class="am-input-group">									
									<input type="text" class="am-form-field am-radius" id="nicename" name="nicename" value="<?php echo $editinfo->nicename;?>" placeholder="管理员的真实姓名" minlength="3" required>				
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
             							<?php echo $admin_type_option;?>
            						</select>
            						
								</div>
							</div>							
						</div>
						
						<div class="am-form-group">
							<label for="" class="am-u-sm-2 am-form-label">email</label>
							<div class="am-u-sm-3 am-u-end">
								<div class="am-input-group">									
									<input type="text" class="am-form-field am-radius js-pattern-email" id="email" name="email" value="<?php echo $editinfo->email;?>" placeholder="管理员的联系邮箱">				
								</div>
								<small class="am-text-warning"></small>
							</div>
						</div>

						<hr>
						<div class="am-form-group" id="loading_box">
							<label for="" class="am-u-sm-2 am-form-label"></label>
							<div class="am-u-sm-2 am-u-end">
								<input type="hidden" value="<?php echo $uid;?>" name="uid" id="uid" />
								<button id="submit_i_do_item" type="submit" class="am-btn am-btn-primary am-btn-block am-radius" data-for-gaq="管理员-提交管理员">
									<i class="am-icon-check-circle"></i> 提　交</button>
							</div>
						</div>
					</form>
				</div>
				<hr/>
			</div>
			<!-- content end -->
		</div><footer>
	<div class="am-modal am-modal-prompt" tabindex="-1" id="my-prompt">
		<div class="am-modal-dialog">
			<div class="am-modal-hd">提醒</div>
			<div class="am-modal-bd">
				来来来，吐槽点啥吧
			</div>
			<input type="text" class="am-modal-prompt-input">
			<hr>	
			<div class="am-modal-footer">
				
				<span class="am-modal-btn" data-am-modal-cancel>取消</span>
				<span class="am-modal-btn" data-am-modal-confirm>提交</span>
			</div>
		</div>
	</div>

	<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
		<div class="am-modal-dialog">
			<div class="am-modal-hd">提醒</div>
			<div class="am-modal-bd" id="confirm_content">
				你，确定要删除这条记录吗？
			</div>
			<div class="am-modal-footer">
				<span class="am-modal-btn" data-am-modal-cancel>取消</span>
				<span class="am-modal-btn" data-am-modal-confirm>确定</span>
			</div>
		</div>
	</div>
	<hr>
	<p class="am-padding-left am-text-center">© 2015 TmacMVC, Inc.</p>
</footer></body>

</html>

<script src="<?php echo STATIC_URL; ?>js/jquery/1.11.2/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="<?php echo STATIC_URL; ?>common/amazeui/js/amazeui.min.js" type="text/javascript"></script>
<script type="text/javascript">
var index_url = '<?php echo MOBILE_URL; ?>';
var static_url = '<?php echo STATIC_URL; ?>';
var base_v = '<?php echo $BASE_V;?>';
var php_self = '<?php echo PHP_SELF; ?>';
var postField = {};
</script>
		
<script src="<?php echo $BASE_V;?>js/common.js" type="text/javascript"></script>
<script src="<?php echo STATIC_URL; ?>js/jquery-plugin/ui/minified/jquery.cookie-min.js" type="text/javascript"></script>
<script src="<?php echo $BASE_V;?>js/modal_html.js" type="text/javascript"></script>
<script src="<?php echo $BASE_V;?>js/user_detail.js" type="text/javascript"></script>		