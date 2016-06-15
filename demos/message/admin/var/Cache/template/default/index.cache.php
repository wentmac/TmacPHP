<?php if (!class_exists('template', false)) die('Access Denied');
0
|| self::check('default\index.tpl', 'D:\Project\web\site\TmacPHP\demos\message\admin\application\View\default\index.tpl', 1465977973)
|| self::check('default\index.tpl', 'D:\Project\web\site\TmacPHP\demos\message\admin\application\View\default\inc/header_paul.tpl', 1465977973)
|| self::check('default\index.tpl', 'D:\Project\web\site\TmacPHP\demos\message\admin\application\View\default\inc/sidebar_paul.tpl', 1465977973)
|| self::check('default\index.tpl', 'D:\Project\web\site\TmacPHP\demos\message\admin\application\View\default\inc/footer_paul.tpl', 1465977973)
;?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<link rel="apple-touch-icon-precomposed" href="/i/app-icon72x72@2x.png">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>用户中心</title>
		<meta name="description" content="用户中心">
		<meta name="keywords" content="index">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="renderer" content="webkit">
		<meta name="apple-mobile-web-app-title" content="Amaze UI" />		
		<link rel="stylesheet" href="<?php echo STATIC_URL; ?>common/amazeui/css/amazeui.min.css" />
		<link href="<?php echo STATIC_URL; ?>common/amazeui/css/admin.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $BASE_V;?>css/base.css" type="text/css" rel="stylesheet">
		<link href="<?php echo $BASE_V;?>css/page.css" type="text/css" rel="stylesheet">
		<link href="<?php echo $BASE_V;?>css/order_detail.css" type="text/css" rel="stylesheet">

	</head>

	<body>
<!-- header start -->
  
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
            <li><a href="<?php echo ADMIN_URL; echo PHP_SELF; ?>?m=account.loginout"><span class="am-icon-power-off"></span> 退出</a></li>
          </ul>
        </li>
        <li class="am-hide-sm-only"><a href="javascript:;" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span> <span class="admin-fullText">开启全屏</span></a></li>        
      </ul>
    </div>
  </header>
  <!-- header end -->  	<div class="am-cf admin-main">   
      <style type="text/css">
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
    <!-- sidebar end --><div class="admin-content">

	<div class="am-g">
	    <div class="am-cf am-padding">
	      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">首页</strong> / <small>一些常用模块</small></div>
	    </div>

	    <ul class="am-avg-sm-1 am-avg-md-4 am-margin am-padding am-text-center admin-content-list ">
	      <li><a href="#" class="am-text-success"><span class="am-icon-btn am-icon-file-text"></span><br>新增页面<br>2300</a></li>
	      <li><a href="#" class="am-text-warning"><span class="am-icon-btn am-icon-briefcase"></span><br>成交订单<br>308</a></li>
	      <li><a href="#" class="am-text-danger"><span class="am-icon-btn am-icon-recycle"></span><br>昨日访问<br>80082</a></li>
	      <li><a href="#" class="am-text-secondary"><span class="am-icon-btn am-icon-user-md"></span><br>在线用户<br>3000</a></li>
	    </ul>
	</div>

    <div class="am-g">
      <div class="am-u-md-6">

        <div class="am-panel am-panel-default">
          <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}">系统信息A<span class="am-icon-chevron-down am-fr"></span></div>
          <div id="collapse-panel-1" class="am-collapse am-in">
            <table class="am-table am-table-bd am-table-bdrs am-table-striped am-table-hover">
              <tbody>
              <tr>                
                <th>PHP版本</th>
                <th><?php echo phpversion(); ?></th>
              </tr>
              <tr>                
                <td>Register_Globals</td>
                <td><?php echo ini_get("register_globals") ? 'On' : 'Off' ?></td>
              </tr>
              <tr>                
                <td>支持上传的最大文件</td>
                <td><?php echo ini_get("post_max_size") ?></td>
              </tr>
              <tr>                
                <td>服务器操作系统</td>
                <td><?php echo PHP_OS; echo '('.$_SERVER['SERVER_ADDR'].')'; ?></td>
              </tr>
              <tr>                
                <td>MySQL 版本</td>
                <td><?php echo $mysql_version;?></td>
              </tr>
              <tr>                
                <td>版本名称</td>
                <td><?php echo $GLOBALS['TmacConfig']['config']['softname']; ?>[<?php echo $GLOBALS['TmacConfig']['config']['soft_enname']; ?>]</td>
              </tr>
              <tr>                
                <td>开发团队</td>
                <td<?php echo $GLOBALS['TmacConfig']['config']['soft_devteam']; ?></td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <div class="am-u-md-6">

        <div class="am-panel am-panel-default">
          <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}">系统信息B<span class="am-icon-chevron-down am-fr"></span></div>
          <div id="collapse-panel-2" class="am-collapse am-in">
            <table class="am-table am-table-bd am-table-bdrs am-table-striped am-table-hover">
              <tbody>
              <tr>                
                <th>GD版本</th>
                <th><?php echo $gdversion;?></th>
              </tr>
              <tr>                
                <td>Magic_Quotes_Gpc</td>
                <td><?php echo ini_get("magic_quotes_gpc") ? 'On' : 'Off' ?></td>
              </tr>
              <tr>                
                <td>是否允许打开远程连接</td>
                <td><?php echo ini_get("allow_url_fopen") ? '支持' : '不支持'; ?></td>
              </tr>
              <tr>
                <td>Web 服务器</td>
                <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?></td>
              </tr>
              <tr>                
                <td>安全模式</td>
                <td><?php $safe_mode = (boolean) ini_get('safe_mode') ? 'yes' : 'no'; echo $safe_mode; ?></td>
              </tr>
              <tr>                
                <td>版本号</td>
                <td><?php echo $GLOBALS['TmacConfig']['config']['version']; ?> Release <?php echo $upTime;?></td>
              </tr>
              <tr>                
                <td>程序编码</td>
                <td><?php echo $GLOBALS['TmacConfig']['config']['soft_lang']; ?></td>
              </tr>
              </tbody>
            </table>
          </div>
        </div> 


      </div>
    </div>
  </div>	

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
<script>
	var index_url = '<?php echo MOBILE_URL; ?>';
	var static_url = '<?php echo STATIC_URL; ?>';
	var base_v = '<?php echo $BASE_V;?>';
	var php_self = '<?php echo PHP_SELF; ?>';
	var param = {
		status: '',
		pagesize: 6,
		page: 1,
		totalput: 0
	}
</script>
<script src="<?php echo STATIC_URL; ?>common/amazeui/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo STATIC_URL; ?>common/amazeui/js/amazeui.min.js" type="text/javascript"></script>
<script src="<?php echo STATIC_URL; ?>js/jquery-plugin/ui/minified/jquery.cookie-min.js" type="text/javascript"></script>
<script src="<?php echo $BASE_V;?>js/common.js" type="text/javascript"></script>