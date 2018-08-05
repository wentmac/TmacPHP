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
	<!--{eval $option_count = count($option_array[object])-1;}-->
	<!--{loop $option_array[object] $k $v}-->
	<!--{if $k%2==0}-->
	<div class="weui-flex  weui-cells_radio">
	<!--{/if}-->
		<div class="weui-flex__item">
			<label class="weui-cell weui-check__label" for="x{$v[value]}">
				<div class="weui-cell__bd">
					<p class="option_name">{$v[name]}</p>
					<img class="weui-media-box__thumb" src="{$BASE_V}image/survey/{$v[image_name]}.png" alt="" width="120px" height="120px">
				</div>
				<div class="weui-cell__ft">
					<input type="radio" class="weui-check" name="radio1" value="{$v[value]}" id="x{$v[value]}" v-model="checkedNames">
					<span class="weui-icon-checked"></span>
				</div>
			</label>
		</div>
		<!--{if $k%2==1 || $k==$option_count}-->
		</div>
		<!--{/if}-->
	<!--{/loop}-->
</div>
	<!--
	<div class="weui-flex">
		<div class="weui-flex__item"><div class="placeholder">weui</div></div>
		<div class="weui-flex__item"><div class="placeholder">weui</div></div>
		<div class="weui-flex__item"><div class="placeholder">weui</div></div>
	</div>
	<div class="weui-flex">
		<div class="weui-flex__item"><div class="placeholder">weui</div></div>
		<div class="weui-flex__item"><div class="placeholder">weui</div></div>
		<div class="weui-flex__item"><div class="placeholder">weui</div></div>
		<div class="weui-flex__item"><div class="placeholder">weui</div></div>
	</div>
	-->



<div class="page__bd page__bd_spacing">
	<a href="{MOBILE_URL}survey/{$prev_step_id}" class="weui-btn weui-btn_default" id="prev_button">上一题</a>
	<a href="{MOBILE_URL}survey/{$next_step_id}" class="weui-btn weui-btn_primary" id="next_button">选好了，下一题</a>
	<!--<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary">页面主操作 Disabled</a>-->

	<!--
	<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_default">页面次要操作 Disabled</a>
	<a href="javascript:;" class="weui-btn weui-btn_warn">警告类操作 Normal</a>
	<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_warn">警告类操作 Disabled</a>
	-->
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


    var step_id = '{$step_id}';
</script>

<!-- 开发环境版本，包含了用帮助的命令行警告 -->
<script data-main="{$BASE_V}js/baoshencha/survey.js?v={STATIC_VERSION}" src="{STATIC_URL}js/require.js"></script>
<!-- 生产环境版本，优化了尺寸和速度 -->
<!--<script src="https://cdn.jsdelivr.net/npm/vue"></script>-->