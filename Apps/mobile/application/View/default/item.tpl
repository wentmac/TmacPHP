<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
		<meta content="telephone=no" name="format-detection">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<title>{$item_info->item_name}</title>
		<link href="{STATIC_URL}common/assets/css/amazeui.css" type="text/css" rel="stylesheet">
		<link href="{$BASE_V}css/common/base.css" type="text/css" rel="stylesheet">
		<link href="{$BASE_V}css/index/index.css" type="text/css" rel="stylesheet">
		<link href="{$BASE_V}css/item/item.css" type="text/css" rel="stylesheet">
		<link href="{$BASE_V}css/common/itemListTemplate.css" type="text/css" rel="stylesheet">
		<link href="{$BASE_V}css/market/recommend.css" type="text/css" rel="stylesheet">
		<style>
			.en-markup-crop-options {
				top: 18px !important;
				left: 50% !important;
				margin-left: -100px !important;
				width: 200px !important;
				border: 2px rgba(255, 255, 255, .38) solid !important;
				border-radius: 4px !important;
			}
			hr {
				margin: 5px!important;
			}
			.en-markup-crop-options div div:first-of-type {
				margin-left: 0px !important;
			}
			#select_sku {
				max-height: 302px;
				overflow: auto;
			}
			#detail_wrap img {
				width: 100%;
			}
			
			.sku_img{
				width:50px;
			}
			#item_info_for_show_wrap section p img{
				width: 100%;
			}
			.de-description-detail{
				width:100%!important;
			}
			.focus{
				background: rgba(0,0,0,0)!important;
			}
		</style>
	</head>

	<body style="padding-bottom: 60px;">
		<div id="item_show_wrap" style="height: auto; overflow: visible;">
			<header id="common_hd" class="c_txt rel hide" style="display: block;"><a id="hd_back" class="abs" href="{$referer_url}">返回</a>
				<a id="common_hd_logo" class="hd_logo t_hide abs">{$config[cfg_webname]}</a>
				<div class="my_shop for_gaq" data-for-gaq="点击我的{$config[cfg_webname]};详情页"><span></span></div>
			</header>
			<div id="item_wrap_loading" class="loading" style="display: none;">&nbsp;</div>
			<div id="item_info_for_show_wrap" class="hide" style="display: block;">
				
				<section id="item_info" class="rel">
					<div class="slider">
							<ul>
								<!--{loop $goods_image_array $item}-->
								<li>
									<img src="{$item->goods_image_id}" width="100%">
								</li>
								<!--{/loop}-->
							</ul>
					</div>
					<br>
					<article id="item_show" class="loading c_txt rel">
						<!--<img width="100%" src="{$item_info->goods_image_id}" data-share-src="{$item_info->goods_image_id}">-->
						<div id="hd_bg_item" class="abs hide" style="display: block;"></div><em id="sold" class="abs">收藏&nbsp;<span id="collect-number">{$item_info->collect_count}</span></em>
						<div id="favorite" class="favorite for_gaq hide" data-for-gaq="收藏商品">收藏</div>
					</article>
					<h2 id="item_name"><strong>{$item_info->item_name}</strong><br>
        
        </h2>
					<div id="item_price_wrap" class="rel">
						<p><span id="seckill_price" class="hide"></span> <span id="item_price" class="i_pri">{$item_info->item_price}</span> <span id="seckill_discount"></span></p>
						<div id="control_seckill_wrap" class="hide"><span id="seckill_left"></span> <span id="seckill_right"></span></div>
						<p id="express_money_show" class="hide"></p>

						<!-- {if empty({$item_info->shipping_fee})}-->
						<p id="free_delivery" class="hide rel" style="display: block;">包邮 <em id="free_delivery_em">偏远地区请联系{$config[cfg_webname]}主</em></p>
						<!-- {else} -->
						<p id="free_delivery" class="hide rel" style="display: block;">邮费:<em id="free_delivery_em">￥{$item_info->shipping_fee}</em></p>

						<!--{/if}-->

						<div id="share_for_money" class="abs hide"><em id="share_for_money_icon" class="inline_b c_txt">¥</em>&nbsp;点我有奖</div>
					</div>
					<div class="itemrank hide" id="itemrank"></div>
				</section>
				<section class="guarantee_wrap">
					<section id="guarantee" class="guarantee hide"></section>
				</section>
				<section id="control_title" class="hide" style="display: block;">
					<a href="#" id="btn_select_sku" class="btn_select_sku">选择商品 型号尺寸<i class="am-fr am-icon-chevron-right"></i></a>
				</section>
				<section id="item_info_for_common" class="hide">
					<div id="comment">
						<h2><span id="count"></span><span class="scoreNumber" id="scoreNum"><a><em id="score"></em></a></span></h2>
						<ul id="commentList" class="hide"></ul><a id="moreContent" style="display:none" href="/itemComment.html">查看更多评论</a></div>
				</section>
				<section id="item_seller">
					<a id="seller_wrap" class="rel hide for_gaq" data-for-gaq="进入店铺" href="/shop/{$item_info->uid}" style="display: block;">
						<div id="seller_thumb_wrap" class="abs"><img width="100%" id="seller_thumb" class="abs" src="{$shop_info->shop_image_url}"></div>
						<p id="seller_name" class="over_hidden ellipsis">{$shop_info->shop_name}</p>						
					</a>
					<div id="seller_entry_wrap">
						<a id="enter_shop_class" class="left for_gaq c_txt" data-for-gaq="查看商品分类" href="/shop/search?id={$item_info->uid}"><em>&nbsp;</em>查看商品分类</a>
						<a id="enter_shop" href="/shop/{$item_info->uid}" class="right for_gaq c_txt" data-for-gaq="进入店铺"><em>&nbsp;</em>进入店铺</a></div>
				</section>
				<section id="item_detail">
					<div id="detail_wrap">
						<div id="video_wrap" class="hide">&nbsp;</div>
						
						<!--{if $item_photo_show}-->
						<!--{loop $goods_image_array $k $v}-->
						<img src="{$v->goods_image_id}" width="100%">
						<!--{/loop}-->
						<!--{/if}-->
						{$item_info->item_desc}
					</div>
					<a href="{MOBILE_URL}" target="_blank" id="iWantAShop" class="block c_txt for_gaq rel" data-for-gaq="详情页－我也要开{$config[cfg_webname]}" style="margin-top: 40px; display:none;">
					</a>
				</section>
			</div>
			<footer id="item_fix_btn" class="fix hide wrap" style="-webkit-transition: opacity 200ms ease; transition: opacity 200ms ease; opacity: 1; display: block;">
				<div class="contact_div">
					<ul>
						<li><a href="tel:{$shop_info->mobile}">拨打电话</a></li>
						<li style=" height: 2px; line-height: 2px;">
							<hr>
						</li>
						<li><a href="sms:{$shop_info->mobile}">发送短信</a></li>
					</ul>
				</div>
				<div id="control_btn" class="margin_auto">
					<div id="control_btn_inner" class="rel">
						<div id="control_btn_inner_left" class="abs" style="-webkit-transition: opacity 200ms ease; transition: opacity 200ms ease; opacity: 1; display: block;">

							<div class="contact for_gaq" data-for-gaq="点击联系卖家详情页">联系卖家</div>
							<div class="store for_gaq" data-for-gaq="商品收藏">
								<span class="store_tip">收藏</span>
								<span class="stored_tip">已收藏</span>
							</div>
						</div>
						<div id="control_btn_inner_right" class="abs"><a id="add_cart" class="c_txt abs for_gaq" data-for-gaq="加入购物车" style="display: block;">加入购物车</a> <a id="buy_now" class="btnok c_txt abs for_gaq" data-for-gaq="立即购买" style="display: block;">立即购买</a> <a id="control_bottom_submit" class="btnok c_txt abs"
							style="display: none;">确定</a></div>
					</div>
				</div>
			</footer>
			<section id="item_recommend_wrap">
				<div id="recommend_wrap_loading" class="loading" style="display: none;">&nbsp;</div>
				<div class="i_wrap margin_auto rel hide" id="item_recommend">
					<h3 class="i_title abs"><p id="hot_title" class="i_title_p over_hidden ellipsis">&nbsp;</p></h3>
					<ul class="i_ul rel" id="hot_ul"></ul>
					<div class="clear"></div>
				</div>
			</section>
		</div>
		<div id="select_sku" class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
			<div class="am-modal-dialog">
				<div class="am-modal-hd">
					<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
				</div>
				<div class="am-modal-bd am-text-left">
					<div class="select_sku_pro_info">
						<div class="sku_img am-fl">
							<img src="{$item_info->goods_image_id}" width="50" height="50">
						</div>
						<div id="sku_pro_tit" class="sku_pro_tit">{$item_info->item_name}</div>
						<div id="select_sku_price" class="select_sku_price am-left"></div>
						<div class="am-cf"></div>
					</div>
					<hr>
					<!-- {if empty({$item_info->shipping_fee})}-->
					无含邮费
					<!-- {else} -->
					<div id="shipping_fee" class="left sku_item_tit am-padding-left-sm">邮费:</div>￥{$item_info->shipping_fee}
					<!--{/if}-->
					<hr>
					<div id="select_sku_item_list">
					</div>
					<div id="select_sku_num" class="select_sku_num" style="display:none">
						<div class="sku_num_tit">数量</div>
						<div class="sku_num_sel">
							<div class="am-input-group am-input-group-sm">
								<span class="am-input-group-btn">
                <button class="am-btn am-btn-default" id="btn_stock_minus" type="button"><i class="am-icon-minus"></i></button>
              </span>
								<input type="text" id="buy_num" class="am-form-field am-text-center" value="0">
								<span class="am-input-group-btn">
                <button class="am-btn am-btn-default" id="btn_stock_add" type="button"><i class="am-icon-plus"></i></button>
              </span>
							</div>
						</div>
						<div id="sku_stock" class="sku_stock" style="display:none"></div>
						<div class="am-cf"></div>
					</div>
					<hr>
					<div class="sku_btn">
						<ul class="am-avg-sm-2">
							<li class="">
								<button id="btn_add2cart" class="am-btn am-btn-default am-btn-block am-radius">加入购物车</button>
							</li>
							<li class="">
								<button id="btn_buynow" class="am-btn am-btn-danger am-btn-block am-radius">立即购买</button>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div id="cps_wrap" class="fix hide"></div>
		<div class="cart_btn for_gaq" data-for-gaq="进入购物车;详情页" style="right: 651px;"><span></span></div>
		<script type="text/javascript" src="{STATIC_URL}common/assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="{$BASE_V}js/mobile_slider.js"></script>
		<script type="text/javascript" src="{STATIC_URL}common/assets/js/amazeui.min.js"></script>
		<script type="text/javascript" src="{STATIC_URL}js/modal_html.js"></script>
		<script type="text/javascript" src="{STATIC_URL}js/jquery-plugin/ui/minified/jquery.cookie-min.js"></script>
		<script type="text/javascript" src="{$BASE_V}js/jquery.lazyload.js"></script>
		<script>
			$(".slider").yxMobileSlider({
				width: 640,
				height: 640,
				during: 3000
			});
		</script>
		<script type="text/javascript">
			var index_url = '{INDEX_URL}';
			var mobile_url = '{MOBILE_URL}';
			var static_url = '{STATIC_URL}';
			var base_v = '{$BASE_V}';
			var php_self = '{PHP_SELF}';
			var gloabl_item_id = {$item_info->item_id};
			var gloabl_item_price = '{$item_info->item_price}';
			var gloabl_item_stock = {$item_info->item_stock};
			var global_goods_name = '{$item_info->item_name}';
			var global_goods_spec_array = {$goods_spec_array};
			var global_goods_sku_array = {$goods_sku_array};
			var global_sku_init = 0;
			var global_sku_current = "";
			var global_sku_current_obj = new Object();
		</script>

		<script type="text/javascript" src="{$BASE_V}js/item.js?v=20151230"></script>
	</body>

</html>