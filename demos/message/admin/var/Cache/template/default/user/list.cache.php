<?php if (!class_exists('template', false)) die('Access Denied');
0
|| self::check('default\user/list.tpl', 'D:\Web\Witkey\wk\tuhao\trunk\admin\application\View\default\user\list.tpl', 1461689673)
|| self::check('default\user/list.tpl', 'D:\Web\Witkey\wk\tuhao\trunk\admin\application\View\default\inc/header_paul.tpl', 1461689673)
|| self::check('default\user/list.tpl', 'D:\Web\Witkey\wk\tuhao\trunk\admin\application\View\default\inc/sidebar_paul.tpl', 1461689673)
|| self::check('default\user/list.tpl', 'D:\Web\Witkey\wk\tuhao\trunk\admin\application\View\default\inc/footer_paul.tpl', 1461689673)
;?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<link rel="apple-touch-icon-precomposed" href="/i/app-icon72x72@2x.png">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>管理员-用户中心</title>
		<meta name="description" content="用户中心">
		<meta name="keywords" content="index">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="renderer" content="webkit">
		<meta name="apple-mobile-web-app-title" content="Amaze UI" />
		<meta name="save" content="history">
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
			<div class="admin-content">
				<div class="am-cf am-padding">
					<div class="am-fl"><strong class="am-text-primary am-text-lg">管理员</strong>
					</div>
				</div>
				<hr/> 

				<div class="am-g am-margin-top-sm am-form" id="condition_list">		
					<div class="am-u-sm-8">																		
							<div class="am-input-group">							  								  	
								<span class="am-input-group-label am-radius"><i class="am-icon-search am-icon-fw"></i></span>
								<input id="txt_keyword" type="text" class="am-form-field" placeholder="输入关键词搜索……">							    
								<span class="am-input-group-btn">
									<button class="am-btn am-btn-default" type="button" id="search_button">搜索</button>
								</span>
							</div>							  					 								
								
					</div>							
				</div>

			
				<hr>
				<div class="am-g">
			        <div class="am-u-lg-12">
						<button class="am-btn am-btn-sm am-btn-primary cbk_all"><i class="am-icon-check-circle"></i> 全选</button>
						<button class="am-btn am-btn-sm am-btn-primary cbk_no_all"><i class="am-icon-check-circle-o"></i> 反选</button>						
						<button class="am-btn am-btn-sm am-btn-danger cbk_del_all"><i class="am-icon-trash"></i> 批量删除</button>						
			        </div>
			     </div>

				<div class="am-g">
					<div class="am-u-lg-12">
						<table class="am-table am-table-striped am-table-hover table-main">
							<thead>
								<tr>									
									<th width="3%"></th>
									<th width="5%" class="am-text-middle am-text-center">ID</th>
									<th width="8%" class="am-text-middle am-text-center">用户名</th>
									<th width="10%" class="am-text-middle am-text-center">昵称</th>
									<th width="10%" class="am-text-middle am-text-center">级别</th>
									<th width="10%" class="am-text-middle am-text-center">注册时间</th>
									<th width="15%" class="am-text-middle am-text-center">最后登录时间</th>
									<th width="15%" class="am-text-middle am-text-center">最后登录IP</th>									
									<th width="8%" class="am-text-middle am-text-center">操作</th>
								</tr>
							</thead>
							<tbody  id="order_list_loading">
								<tr>
									<td colspan="11" class="am-text-center">
										<div class="am-modal-hd am-text-center"><img  src="<?php echo $BASE_V;?>image/loading.gif">正在载入...</div>
									</td>
								</tr>

							</tbody>
							<tbody style="display: none;" id="order_list_nofund">
								<tr>
									<td class="am-text-center" colspan="10">
												<div class="am-modal-hd">很抱歉，没有找到结果...</div>
										</td>
									
								</tr>

							</tbody>
							<tbody id="tbody_list">

							</tbody>
							<tfoot>
								<tr>
									<td colspan="11" id="roomListPages" class="am-text-center page pagination"></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>

				<div class="am-g">
				    <div class="am-u-lg-12">
				    <button class="am-btn am-btn-sm am-btn-primary cbk_all"><i class="am-icon-check-circle"></i> 全选</button>
				      <button class="am-btn am-btn-sm am-btn-primary cbk_no_all"><i class="am-icon-check-circle-o"></i> 反选</button>				      
				      <button class="am-btn am-btn-sm am-btn-danger cbk_del_all"><i class="am-icon-trash"></i> 批量删除</button>
				    </div>
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
<script type="text/javascript">
	var index_url = '<?php echo MOBILE_URL; ?>';
	var mobile_url = '<?php echo MOBILE_URL; ?>';
	var static_url = '<?php echo STATIC_URL; ?>';
	var base_v = '<?php echo $BASE_V;?>';
	var php_self = '<?php echo PHP_SELF; ?>';
	var searchParameter = <?php echo $searchParameter;?>;
</script>
<script src="<?php echo STATIC_URL; ?>common/amazeui/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo STATIC_URL; ?>common/amazeui/js/amazeui.min.js" type="text/javascript"></script>
<script src="<?php echo STATIC_URL; ?>js/jquery-plugin/jquery.pagination-min.js" type="text/javascript"></script>
<script src="<?php echo STATIC_URL; ?>js/jquery-plugin/ui/minified/jquery.cookie-min.js" type="text/javascript"></script>
<script src="<?php echo $BASE_V;?>js/common.js" type="text/javascript"></script>
<script src="<?php echo $BASE_V;?>js/modal_html.js" type="text/javascript"></script>
<script src="<?php echo $BASE_V;?>js/user_list.js?v=1" type="text/javascript"></script>
<script language="javascript">
	$(document).ready(function() {
		search.bindParam();
		search.getList();
		search.batch();
	});
</script>