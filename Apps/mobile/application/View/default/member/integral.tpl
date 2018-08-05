<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
		<meta content="telephone=no" name="format-detection">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<title>我的积分 - {$config[cfg_webname]}</title>
		<link href="{$BASE_V}css/common/base.css" type="text/css" rel="stylesheet">
		<link href="{$BASE_V}css/user/user.css" type="text/css" rel="stylesheet">
		<style>						 
			  .orderListType ul li{width:33.3%;}
.shopList .p-sum {    
    border-bottom: none;    
}			  
.main {
    padding-bottom: 0px;
}
.product {
	border-bottom: none;    
}
.integral_title {
	float: left;
	width: 60%;
}
.integral {
	float: left;
	width: 10%;
	text-align: center;
}
.integral_time{
	float: left;
	width: 30%;
	text-align: center;
}
		</style>
	</head>

	<body>
		<section class="main">
		    <header id="common_hd" class="c_txt rel">
		        <a id="hd_back" class="abs comm_p8" href="{$referer_url}">返回</a>
		        <a id="common_hd_logo" class="t_hide abs common_hd_logo">我的积分</a>
		        <h1 class="hd_tle">我的积分</h1>
		        <a id="hd_enterShop" class="hide abs" href="{MOBILE_URL}member/home" style="display: block;"> <span id="hd_enterShop_img" class="abs"> <img class="block" src="{$BASE_V}image/common/member_home.png" width="32" height="32" style="display: block;"> </span>会员中心</a>
		    </header>
			


			<div class="orderListTypeHolder">&nbsp;</div>
			<div class="orderListType" id="tabPlus">
				<ul>
					<li><a href="{MOBILE_URL}member/order" class="cur">全部[{$available_integral}分]</a>
					</li>
					<li><a id="waiting_payment">积分增加[{$sum_integral}分]</a>
					</li>
					<li><a id="wating_seller_delivery">积分消耗[-{$consume_integral}分]</a>
					</li>					
				</ul>
			</div>


<div class="orderList" id="js_bill_list">
	<div style="display:block"> 
		<nav class="shopList">   			
			<nav class="probody maxheight">
			     <a class="product">       
			     <div class="flex">         
			     	
			     	<div class="flex-auto p-details">           
			     		<div class="flex">             
			     			<div class="flex-auto">
			     				<span class="integral_title">张迪[自营收入][退款]申请商品退款</span>
			     				<span class="integral">-1分</span>
			     				<span class="integral_time">2015-07-08 17:30:47</span>
			     			</div>
			     			         
			     		</div>
			     	</div>
			     </div>
			     </a>
			</nav>			  		
		</nav>

		<nav class="shopList">   			
			<nav class="probody maxheight">
			     <a class="product">       
			     <div class="flex">         
			     	
			     	<div class="flex-auto p-details">           
			     		<div class="flex">             
			     			<div class="flex-auto">
			     				<span class="integral_title">张迪[自营收入][退款]申请商品退款</span>
			     				<span class="integral">-1分</span>
			     				<span class="integral_time">2015-07-08 17:30:47</span>
			     			</div>
			     			         
			     		</div>
			     	</div>
			     </div>
			     </a>
			</nav>			  		
		</nav>

		<nav class="shopList">   			
			<nav class="probody maxheight">
			     <a class="product">       
			     <div class="flex">         
			     	
			     	<div class="flex-auto p-details">           
			     		<div class="flex">             
			     			<div class="flex-auto">
			     				<span class="integral_title">张迪[自营收入][退款]申请商品退款</span>
			     				<span class="integral">-1分</span>
			     				<span class="integral_time">2015-07-08 17:30:47</span>
			     			</div>
			     			         
			     		</div>
			     	</div>
			     </div>
			     </a>
			</nav>			  		
		</nav>					

		</div></div>
		</section>
		<p id="scroll_loading_txt" class="loading ">&nbsp;</p>		
	</body>

</html>

<script type="text/javascript" src="{STATIC_URL}js/jquery/1.11.2/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="{STATIC_URL}js/jquery-plugin/jquery.tmpl.min.js"></script>

<script type="text/javascript">
	var index_url = '{INDEX_URL}';
	var mobile_url = '{MOBILE_URL}';
	var static_url = '{STATIC_URL}';
	var base_v = '{$BASE_V}';
	var php_self = '{PHP_SELF}';	
</script>
<script type="text/javascript" src="{$BASE_V}js/integral_list.js"></script>