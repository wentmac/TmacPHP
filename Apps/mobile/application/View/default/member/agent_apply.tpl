<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
  <meta content="telephone=no" name="format-detection">
  <meta name="apple-touch-fullscreen" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <title>申请成为代理商</title>
  <link href="{$BASE_V}css/common/base.css" type="text/css" rel="stylesheet">
  <link href="{$BASE_V}css/cart/cart.css" type="text/css" rel="stylesheet">
  <link href="{$BASE_V}css/user/user.css" type="text/css" rel="stylesheet">
  <style id="style-1-cropbar-clipper">
  /* Copyright 2014 Evernote Corporation. All rights reserved. */
  
  .en-markup-crop-options {
    top: 18px !important;
    left: 50% !important;
    margin-left: -100px !important;
    width: 200px !important;
    border: 2px rgba(255, 255, 255, .38) solid !important;
    border-radius: 4px !important;
  }
  
  .en-markup-crop-options div div:first-of-type {
    margin-left: 0px !important;
  }
  </style>
</head>

<body>
  <header id="common_hd" class="c_txt rel">
      <a id="hd_back" class="abs comm_p8" href="{$referer_url}">返回</a>
      <a id="common_hd_logo" class="t_hide abs common_hd_logo">申请成为代理商</a>
      <h1 class="hd_tle">申请代理</h1>
      <a id="hd_enterShop" class="hide abs" href="{MOBILE_URL}member/home" style="display: block;"> <span id="hd_enterShop_img" class="abs"> <img class="block" src="{$BASE_V}image/common/member_home.png" width="32" height="32" style="display: block;"> </span>会员中心</a>
  </header>

  <div class="main">
    <div id="address_form" style="margin:10px;background:#fff">
      <p class="address_p rel">
        <label for="realname" class="abs">姓名</label>
        <input type="text" id="realname" name="realname" class="block input" value="{$memberInfo->realname}" tabindex="1" placeholder="请输入申请人姓名">
      </p>
      <p class="address_p rel">
        <label for="tele" class="abs">手机号码</label>
        <input type="tel" id="member_contact" name="member_contact" maxlength="11" class="block input" tabindex="2" value="{$memberSettingInfo->member_contact}" placeholder="请输入手机号">
      </p>
      <p class="address_p rel">
        <label for="idcard" class="abs">身份证号码</label>
        <input type="number" id="idcard" name="idcard" maxlength="11" class="block input" tabindex="2" value="{$memberSettingInfo->idcard}" placeholder="请输入身份证号">
      </p>
      <p class="address_p rel">
        <label for="province" class="abs">所在地区</label>
        <select id="province" name="province" class="block input" tabindex="3">
          <option value="-1">--省份--</option>
        </select>
      </p>
      <p class="address_p rel">
        <label for="city" class="abs"></label>
        <select id="city" name="city" class="block input" tabindex="4">
          <option value="-1">--城市--</option>
        </select>
      </p>
      <p class="address_p rel">
        <label for="district" class="abs"></label>
        <select id="district" name="district" class="block input" tabindex="5">
          <option value="-1">--地区--</option>
        </select>
      </p>
      <p class="address_p rel address_p_area">
        <label for="detail_add" class="abs">详细地址</label>
        <textarea name="agent_address" cols="" rows="" id="agent_address" class="block input" tabindex="6" placeholder='请输入街道地址'>{$memberSettingInfo->agent_address}</textarea>
      </p>
      <p class="address_p rel address_p_area">
        <label for="remark" class="abs">备注</label>
        <textarea name="remark" cols="" rows="" id="remark" class="block input" tabindex="6" placeholder='请输入申请代理的备注'>{$memberSettingInfo->remark}</textarea>
      </p>
    </div>
  </div>
  <footer id="adadress_list_footer" class="wrap fix">
    <div id="adadress_list_footer_inner" class="wrap margin_auto">
      <div id="adadress_list_footer_btn_wrap"><a id="new_adadress_btn" class="btnok right">申请代理商</a></div>
    </div>
  </footer>
  <div style="height:100px"></div>
  <script type="text/javascript" src="{STATIC_URL}common/assets/js/jquery.min.js"></script>
  <script type="text/javascript" src="{STATIC_URL}js/json2.js"></script>
  <script type="text/javascript" src="{STATIC_URL}js/jquery-plugin/ui/minified/jquery.cookie-min.js"></script>
  <script type="text/javascript">
  var index_url = '{MOBILE_URL}';
  var mobile_url = '{MOBILE_URL}';
  var static_url = '{STATIC_URL}';
  var base_v = '{$BASE_V}';
  var php_self = '{PHP_SELF}';

  var global_editinfo = JSON.parse('{$editinfo_json}');

  var global_order_address_pid = $.cookie('global_order_address_pid');
  var global_order_address_cityid = $.cookie('global_order_address_cityid');
  var global_order_address_disid  = $.cookie('global_order_address_disid');

  var global_mobile = $.cookie('mobile');
  var global_backurl = $.cookie('back_order_address_url');
  
  //console.log(global_backurl);

  if(global_editinfo.province>0){
    global_order_address_pid = global_editinfo.province;
  }
  if(global_editinfo.city>0){
    global_order_address_cityid = global_editinfo.city;
  }
  if(global_editinfo.district>0){
    global_order_address_disid = global_editinfo.district;
  }

  </script>
  <script type="text/javascript" src="{$BASE_V}js/member_agent_apply.js?v=1"></script>
</body>

</html>