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
		<link rel="stylesheet" href="{STATIC_URL}common/amazeui/css/amazeui.min.css" />
		<link href="{STATIC_URL}common/amazeui/css/admin.css" rel="stylesheet" type="text/css">
		<link href="{$BASE_V}css/base.css" type="text/css" rel="stylesheet">
		<link href="{$BASE_V}css/page.css" type="text/css" rel="stylesheet">
		<link href="{$BASE_V}css/order_detail.css" type="text/css" rel="stylesheet">

	</head>

	<body>
<!-- header start -->
  	<!--{template inc/header_paul}-->
  	<div class="am-cf admin-main">   
  		<!--{template inc/sidebar_paul}-->
<div class="admin-content">

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
                <th>${echo phpversion();}</th>
              </tr>
              <tr>                
                <td>Register_Globals</td>
                <td>{eval echo ini_get("register_globals") ? 'On' : 'Off'}</td>
              </tr>
              <tr>                
                <td>支持上传的最大文件</td>
                <td>${echo ini_get("post_max_size")}</td>
              </tr>
              <tr>                
                <td>服务器操作系统</td>
                <td>${echo PHP_OS; echo '('.$_SERVER['SERVER_ADDR'].')';}</td>
              </tr>
              <tr>                
                <td>MySQL 版本</td>
                <td>{$mysql_version}</td>
              </tr>
              <tr>                
                <td>版本名称</td>
                <td>${echo $GLOBALS['TmacConfig']['config']['softname'];}[${echo $GLOBALS['TmacConfig']['config']['soft_enname'];}]</td>
              </tr>
              <tr>                
                <td>开发团队</td>
                <td${echo $GLOBALS['TmacConfig']['config']['soft_devteam'];}</td>
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
                <th>{$gdversion}</th>
              </tr>
              <tr>                
                <td>Magic_Quotes_Gpc</td>
                <td>${echo ini_get("magic_quotes_gpc") ? 'On' : 'Off'}</td>
              </tr>
              <tr>                
                <td>是否允许打开远程连接</td>
                <td>${echo ini_get("allow_url_fopen") ? '支持' : '不支持';}</td>
              </tr>
              <tr>
                <td>Web 服务器</td>
                <td>${echo $_SERVER['SERVER_SOFTWARE']}</td>
              </tr>
              <tr>                
                <td>安全模式</td>
                <td>${$safe_mode = (boolean) ini_get('safe_mode') ? 'yes' : 'no'; echo $safe_mode;}</td>
              </tr>
              <tr>                
                <td>版本号</td>
                <td>${echo $GLOBALS['TmacConfig']['config']['version'];} Release {$upTime}</td>
              </tr>
              <tr>                
                <td>程序编码</td>
                <td>${echo $GLOBALS['TmacConfig']['config']['soft_lang'];}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div> 


      </div>
    </div>
  </div>	

	</div>	
	<!--{template inc/footer_paul}-->
</body>

</html>
<script>
	var index_url = '{MOBILE_URL}';
	var static_url = '{STATIC_URL}';
	var base_v = '{$BASE_V}';
	var php_self = '{PHP_SELF}';
	var param = {
		status: '',
		pagesize: 6,
		page: 1,
		totalput: 0
	}
</script>
<script type="text/javascript" src="{STATIC_URL}common/amazeui/js/jquery.min.js"></script>
<script type="text/javascript" src="{STATIC_URL}common/amazeui/js/amazeui.min.js"></script>
<script src="{STATIC_URL}js/jquery-plugin/ui/minified/jquery.cookie-min.js" type="text/javascript"></script>
<script type="text/javascript" src="{$BASE_V}js/common.js"></script>