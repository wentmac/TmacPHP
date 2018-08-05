<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
	<title>WeUI</title>
	<!-- 引入 WeUI -->
	<link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.3/weui.min.css"/>
</head>
<body>
<!-- 使用 -->


<div class="weui-panel weui-panel_access">

	<div class="weui-panel__hd" style="text-align: center; color:#000000; font-size:15px; font-weight: bold">选择一种检测方式
		<br>并按照您<p style="color:red; display:inline">一年以来的长期情况</p>进行填写</div>
	<div class="weui-panel__bd">
		<div class="weui-media-box weui-media-box_text">

			<h4 class="weui-media-box__title">
				<a href="{MOBILE_URL}survey/2" class="weui-btn weui-btn_primary">精减版</a>
			</h4>
			<p class="weui-media-box__desc">以中华中医药学会发布的《中医体质量表》为基础，以10个问题测出主要体质，测试时间约1分钟。</p>
		</div>
		<div class="weui-media-box weui-media-box_text">
			<h4 class="weui-media-box__title">
				<a href="{MOBILE_URL}survey/2" class="weui-btn weui-btn_primary">专业版</a>
			</h4>
			<p class="weui-media-box__desc">中华中医药学会发布的《中医体质量表》完整版，共65题，可测出主要体质和兼夹体质，测试时间约5分钟。</p>
		</div>
	</div>

</div>


</body>
</html>
<script type="text/javascript" src="https://res.wx.qq.com/open/libs/weuijs/1.1.3/weui.min.js"></script>
<!-- 开发环境版本，包含了用帮助的命令行警告 -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<!-- 生产环境版本，优化了尺寸和速度 -->
<!--<script src="https://cdn.jsdelivr.net/npm/vue"></script>-->
<script type="text/javascript">
    //weui.alert('alert');
</script>