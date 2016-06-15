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
										<div class="am-modal-hd am-text-center"><img  src="{$BASE_V}image/loading.gif">正在载入...</div>
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
		</div>
		<!--{template inc/footer_paul}-->
	</body>

</html>
<script type="text/javascript">
	var index_url = '{MOBILE_URL}';
	var mobile_url = '{MOBILE_URL}';
	var static_url = '{STATIC_URL}';
	var base_v = '{$BASE_V}';
	var php_self = '{PHP_SELF}';
	var searchParameter = $searchParameter;
</script>
<script type="text/javascript" src="{STATIC_URL}common/amazeui/js/jquery.min.js"></script>
<script type="text/javascript" src="{STATIC_URL}common/amazeui/js/amazeui.min.js"></script>
<script type="text/javascript" src="{STATIC_URL}js/jquery-plugin/jquery.pagination-min.js"></script>
<script type="text/javascript" src="{STATIC_URL}js/jquery-plugin/ui/minified/jquery.cookie-min.js"></script>
<script type="text/javascript" src="{$BASE_V}js/common.js"></script>
<script type="text/javascript" src="{$BASE_V}js/modal_html.js"></script>
<script type="text/javascript" src="{$BASE_V}js/user_list.js?v=1"></script>
<script language="javascript">
	$(document).ready(function() {
		search.bindParam();
		search.getList();
		search.batch();
	});
</script>