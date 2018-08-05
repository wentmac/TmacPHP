<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>购物车</title>
    <link href="{$BASE_V}css/common/base.css" type="text/css" rel="stylesheet">
    <link href="{$BASE_V}css/cart/mycart.css?v=1" type="text/css" rel="stylesheet">
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
    <a id="common_hd_logo" class="t_hide abs common_hd_logo">我的订单</a>
    <h1 class="hd_tle">绑定手机号</h1>
    <a id="hd_enterShop" class="hide abs" href="{MOBILE_URL}member/home" style="display: block;"> <span id="hd_enterShop_img" class="abs"> <img class="block" src="{$BASE_V}image/common/member_home.png" width="32" height="32" style="display: block;"> </span>会员中心</a>
</header>

<section id="mycart_user">
    <p class="mycart_tle">请填写需要绑定的手机号码</p>
    <div class="mycart_user_content">
        <p class="mycart_input_p rel">
            <label for="tel">手机号码</label>
            <input type="tel" required maxlength="11" minlength="11" pattern="^1([3]|[5]|[8]|[4]|[7])[0-9]{9}$" id="tel" name="tel" value="" placeholder="填写你的手机号码"> </p>
        <p id="tel_notice">* 交易通知短信会发到这个手机号码</p>
        <a id="submit_user_tel" class="btnok">确认手机号码</a>
    </div>
</section>

<script type="text/javascript" src="{STATIC_URL}common/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="{STATIC_URL}js/json2.js"></script>
<script type="text/javascript">
    var index_url = '{INDEX_URL}';
    var mobile_url = '{MOBILE_URL}';
    var static_url = '{STATIC_URL}';
    var base_v = '{$BASE_V}';
    var php_self = '{PHP_SELF}';

</script>
<script type="text/javascript" src="{$BASE_V}js/common.js"></script>
<script type="text/javascript" src="{$BASE_V}js/account_bind.js?v=11"></script>
</body>

</html>
