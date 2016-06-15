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
    <link href="{$BASE_V}css/goods_add.css" rel="stylesheet" type="text/css">   
    <link href="{$BASE_V}css/goods_edit.css" type="text/css" rel="stylesheet">    
    <link href="{$BASE_V}css/base.css" type="text/css" rel="stylesheet">
    <link href="{$BASE_V}css/page.css" type="text/css" rel="stylesheet">
    <link href="{$BASE_V}css/order_detail.css" type="text/css" rel="stylesheet">

  </head>

  <body>
    <!--{template inc/header_paul}-->
    <div class="am-cf admin-main">
      <!--{template inc/sidebar_paul}-->      
      <div class="admin-content">
        <div class="am-cf am-padding">
          <div class="am-fl"><strong class="am-text-primary am-text-lg"><a href="{PHP_SELF}?m=poster">模板管理</a></strong></div>
        </div>

        <div class="am-u-md-12">
          <div class="am-panel am-panel-default">
            <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}"><font class="bill_type_info">商城广告位</font><span class="am-icon-chevron-down am-fr"></span></div>
            <div id="collapse-panel-2" class="am-in">                           

    dd
          </div>
        </div>
      </div>

    </div>
    <a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>
    <!-- content end -->
    <!--{template inc/footer_paul}-->
  </body>

</html>
<script>
  var index_url = '{INDEX_URL}';
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
<script type="text/javascript" src="{$BASE_V}amazeui/js/jquery.min.js"></script>
<script type="text/javascript" src="{$BASE_V}amazeui/js/amazeui.js"></script>
<script type="text/javascript" src="{STATIC_URL}js/jquery-plugin/jquery.pagination-min.js"></script>
<script src="{STATIC_URL}js/jquery-plugin/ui/minified/jquery.cookie-min.js" type="text/javascript"></script>

<script type="text/javascript" src="{$BASE_V}js/modal_html.js"></script>

<script type="text/javascript" src="{STATIC_URL}js/ajaxfileupload.js"></script>
<script type="text/javascript" src="{STATIC_URL}js/ThumbAjaxFileUpload.js"></script>
<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>