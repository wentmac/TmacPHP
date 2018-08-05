<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
	<title>{$option_array[name]}-问卷调查-{$config[cfg_webname]}</title>
	<!-- 引入 WeUI -->
	<link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.3/weui.min.css"/>
	<link href="{$BASE_V}css/baoshencha/survey.css" rel="stylesheet" type="text/css" />
</head>
<body>
<!-- 使用 -->


<div class="weui-cells__title">{$option_array[name]}</div>

<div class="page__bd page__bd_spacing weui-cells" id="div_verify">
	<a href="javascript:;" class="weui-btn weui-btn_default">{$result_label}体质</a>
	<a href="{MOBILE_URL}survey/1" class="weui-btn weui-btn_primary" id="completed_button">再测一次</a>
</div>

<div id="div_add_cart" v-cloak>
	<div class="weui-panel weui-panel_access" v-for="(goods_category,index) in goods_array">
		<div class="weui-panel__hd">{{ goods_category_array[index] }}</div>
		<div class="weui-panel__bd">
			<div class="weui-media-box weui-media-box_appmsg" v-for="goods in goods_category">
				<div class="weui-media-box__hd">
					<img class="weui-media-box__thumb"  v-bind:src="goods.goods_image_url">
				</div>
				<div class="weui-media-box__bd">
					<h4 class="weui-media-box__title"><a href="http://www.baidu.com/">{{ goods.goods_name }}</a></h4>
					<p class="goods_title">￥{{ goods.goods_price }}</p>
					<div class="weui-stepper">
						<!--<span class="minus" v-on:click="item_id_array[goods.item_id] -= 1"><em>-</em></span>-->
						<span class="minus" v-on:click="goods_num_less(goods.item_id)"><em>-</em></span>
						<div class="input">
							<input type="tel" min="1" max="10" step="1" class="ng-untouched ng-pristine ng-valid" v-model="item_id_array[goods.item_id]">
						</div>
						<span class="plus" v-on:click="goods_num_plus(goods.item_id)"><em>+</em></span>
					</div>
				</div>
			</div>


		</div>
	</div>


	<div class="page__bd page__bd_spacing weui-cells">
		<a href="{MOBILE_URL}survey/1" class="weui-btn weui-btn_primary" id="add_cart_button">加入购物车</a>
	</div>
</div>

</body>
</html>
<script type="text/javascript">
    var mobile_url = '{MOBILE_URL}';
    var php_self = '{PHP_SELF}';
    var base_v = '{$BASE_V}';
    var static_url = '{STATIC_URL}';

    var production_mode = '{PRODUCTION_MODE}';
    var version = production_mode ?  (new Date()).getTime() : {STATIC_VERSION};


    var step_id = 'result';

    var result_score = '{$result_score}';
    var label_id = '{$label_id}';
    console.log('得分：'+result_score);
</script>

<!-- 开发环境版本，包含了用帮助的命令行警告 -->
<script data-main="{$BASE_V}js/baoshencha/survey.js?v={STATIC_VERSION}" src="{STATIC_URL}js/require.js"></script>
<!-- 生产环境版本，优化了尺寸和速度 -->
<!--<script src="https://cdn.jsdelivr.net/npm/vue"></script>-->