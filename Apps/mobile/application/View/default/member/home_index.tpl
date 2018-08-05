<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <title>会员中心</title>
  <meta charset="utf-8">
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta content="eric.wu" name="author">
  <meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
  <meta content="telephone=no, address=no" name="format-detection">
  <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
  <link href="{$BASE_V}css/common/common.css" rel="stylesheet" />
  <link href="{$BASE_V}css/common/base.css" type="text/css" rel="stylesheet">

  <link href="{$BASE_V}css/buyer/userCenter.css" rel="stylesheet" />
  <style>
  .box{
    position: relative;
    
    padding: 0 15px;
    color: inherit;
    -webkit-box-align: center;    
  }  
  .checkined{color: gray;  }
  </style>
</head>

<body style="margin: 0 auto; max-width: 640px">
  <div data-role="container" class="body userCenter">
    <header data-role="header">
      <div class="uc-user">
        <div class="box" style="height: 115px;">
          <div>
            <span class="img-wrap"><img src="{$memberInfo[member_avatar_url]}"></span>
          </div>
          <!--显示昵称时，需添加样式：nickname-->
          <div class="nickname">
            <p>{$memberInfo[username]} {$memberInfo[member_level]}</p>
            <!--{if empty($memberInfo[mobile])}-->
            <p><a href="{MOBILE_URL}account/bind">绑定手机号</a> </p>
            <!--{else}-->
            <p>已绑定：{$memberInfo[mobile]}</p>
            <!--{/if}-->
            <p>我的推荐ID:{$memberInfo[uid]} <a style="cursor:pointer" id="check_in">&nbsp;&nbsp;   &nbsp;&nbsp;&nbsp;&nbsp;   签到</a></p>
          </div>
        </div>
      </div>
      
    </header>
    <section data-role="body" class="section-body">
      <!--通知-->
      <div class="uc-notification">
        <ul class="box">
          <li>
            <a href="{MOBILE_URL}member/order?status=waiting_payment"><i class="icon-pay" data-tip="{$homeinfo[order_status_buyer_waiting_payment]}"></i>待支付</a>
          </li>
          <li>
            <a href="{MOBILE_URL}member/order?status=wating_seller_delivery"><i class="icon-deliver" data-tip="{$homeinfo[order_status_buyer_wating_seller_delivery]}"></i>待发货</a>
          </li>
          <li>
            <a href="{MOBILE_URL}member/order?status=wating_receiving"><i class="icon-receipt" data-tip="{$homeinfo[order_status_buyer_wating_receiving]}"></i>待收货</a>
          </li>
          <li>
            <a href="{MOBILE_URL}member/order?status=wating_comment"><i class="icon-comment" data-tip="{$homeinfo[order_status_buyer_wating_comment]}"></i>待评价</a>
          </li>
          <li>
            <a href="{MOBILE_URL}member/order?status=complete"><i class="icon-complete" data-tip="{$homeinfo[order_status_buyer_complete]}"></i>已完成</a>
          </li>
          <li>
            <a href="{MOBILE_URL}member/order?status=close"><i class="icon-close" data-tip="{$homeinfo[order_status_buyer_close]}"></i>已关闭</a>
          </li>
          
        </ul>
      </div>
      <!--订单和收藏-->
      
      <div>
        <ul class="list">
          <li><a href="{MOBILE_URL}member/order"><i class="icon-order"></i>我的订单</a></li>
          <li><a href="{MOBILE_URL}member/refund"><i class="icon-fav"></i>退款维权</a></li>
        </ul>
      </div>

      <div>
        <ul class="list">
          <!--{if $is_agent}-->
          <!--<li><a href="{MOBILE_URL}member/agent.detail"><i class="icon-agent-uid"></i>我的东家</a></li>-->
          <li><a href="{MOBILE_URL}member/order.customer"><i class="icon-order"></i>我的销售订单</a></li>
          <li><a href="{MOBILE_URL}member/agent.level"><i class="icon-level"></i>我的顾客</a></li>
          <li><a href="{MOBILE_URL}member/bill.home"><i class="icon-bill"></i>我的账单</a></li>
          <!--{else}-->
          <li><a href="{MOBILE_URL}member/agent.apply"><i class="icon-agent-apply"></i>代理申请</a></li>
          <!--{/if}-->
          <li><a href="{MOBILE_URL}member/integral"><i class="icon-integral"></i>我的积分</a></li>
        </ul>
      </div>

      <!--管理地址-->
      <div>
        <ul class="list">
          <li><a href="{MOBILE_URL}member/address"><i class="icon-address"></i>管理收货地址</a></li>
          <li><a href="{MOBILE_URL}member/qrcode.detail?uid={$memberInfo[uid]}"><i class="icon-qrcode"></i>推广二维码</a></li>
        </ul>
      </div>

      <div>
        <ul class="list">

          <li><a href="{MOBILE_URL}member/collect?type=goods"><i class="icon-collect-shop"></i>收藏商品</a></li>
          <li><a href="{MOBILE_URL}member/collect?type=shop"><i class="icon-collect"></i>收藏店铺</a></li>
        </ul>
      </div>

      <div>
        <ul class="list">
          <li><a href="{MOBILE_URL}account/loginout"><i class="icon-login-out"></i>注销</a></li>
        </ul>
      </div>      
    </section>
    <footer data-role="footer">      
      <div data-role="data-widget" data-widget="home-menu" class="home-menu">
        <div class="widget_wrap">
          <ul class="box" ontouchstart="return true;">
            <li>
              <a id="btn_home" href="/shop/46" class=""><span class="category-1">&nbsp;</span><label>宝身茶</label></a>
            </li>
            <li>
              <a id="btn_shopcart" href="/order/cart" class=""><span>&nbsp;</span><label>购物车</label></a>
            </li>
            <li>
              <a id="btn_center" href="/member/home" class="on"><span>&nbsp;</span><label>会员中心</label></a>
            </li>
          </ul>
        </div>
      </div>
    </footer>
  </div>
</body>

</html>
<script type="text/javascript" src="{STATIC_URL}js/jquery/1.11.2/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="{$BASE_V}js/common.js"></script>

<script type="text/javascript">
  var index_url = '{INDEX_URL}';
  var mobile_url = '{MOBILE_URL}';
  var static_url = '{STATIC_URL}';
  var base_v = '{$BASE_V}';
  var php_self = '{PHP_SELF}';  

function check_in_status(){
    $.ajax({
        type: "get",
        url: mobile_url + "member/integral.check_in_status",
        data: {},
        cache: false,
        success: function(data) {          
          if (data.success == true) {

          } else {
              $('#check_in').html('&nbsp;&nbsp;签到成功');
              //$('#check_in').hide();
              //M._alert(data.message);
          }
        },
        error: function() {
          $("#scroll_loading_txt").hide();
          M._alert("系统正忙,请稍后再试...");
        }
    });
}
function check_in(){
    $.ajax({
        type: "post",
        url: mobile_url + "member/integral.check_in",
        data: {},
        cache: false,
        success: function(data) {          
          if (data.success == true) {
            $('#check_in').addClass('checkined').html('签到成功');
            M._alert("签到成功");
          } else {              
              M._alert(data.message);
          }
        },
        error: function() {
          $("#scroll_loading_txt").hide();
          M._alert("系统正忙,请稍后再试...");
        }
    });
}

$(function(){
  check_in_status();
  $('#check_in').click(function(){    
      if($(this).hasClass('checkined')){
        M._alert("今天已经成功签到过啦:-）");
        return true;
      }
      check_in();
  });
    

});
</script>