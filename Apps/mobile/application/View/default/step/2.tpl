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

<div class="page__bd page__bd_spacing weui-cells">
	<!--{loop $option_array[object] $k $v}-->
	<!--{if $k%2==0}-->
	<div class="weui-flex  weui-cells_radio">
	<!--{/if}-->
		<div class="weui-flex__item">
			<label class="weui-cell weui-check__label" for="x{$v[value]}">
				<div class="weui-cell__bd">
					<p>{$v[name]}</p>
					<img class="weui-media-box__thumb" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAMAAAAOusbgAAAAeFBMVEUAwAD///+U5ZTc9twOww7G8MYwzDCH4YcfyR9x23Hw+/DY9dhm2WZG0kbT9NP0/PTL8sux7LFe115T1VM+zz7i+OIXxhes6qxr2mvA8MCe6J6M4oz6/frr+us5zjn2/fa67rqB4IF13XWn6ad83nxa1loqyirn+eccHxx4AAAC/klEQVRo3u2W2ZKiQBBF8wpCNSCyLwri7v//4bRIFVXoTBBB+DAReV5sG6lTXDITiGEYhmEYhmEYhmEYhmEY5v9i5fsZGRx9PyGDne8f6K9cfd+mKXe1yNG/0CcqYE86AkBMBh66f20deBc7wA/1WFiTwvSEpBMA2JJOBsSLxe/4QEEaJRrASP8EVF8Q74GbmevKg0saa0B8QbwBdjRyADYxIhqxAZ++IKYtciPXLQVG+imw+oo4Bu56rjEJ4GYsvPmKOAB+xlz7L5aevqUXuePWVhvWJ4eWiwUQ67mK51qPj4dFDMlRLBZTqF3SDvmr4BwtkECu5gHWPkmDfQh02WLxXuvbvC8ku8F57GsI5e0CmUwLz1kq3kD17R1In5816rGvQ5VMk5FEtIiWislTffuDpl/k/PzscdQsv8r9qWq4LRWX6tQYtTxvI3XyrwdyQxChXioOngH3dLgOFjk0all56XRi/wDFQrGQU3Os5t0wJu1GNtNKHdPqYaGYQuRDfbfDf26AGLYSyGS3ZAK4S8XuoAlxGSdYMKwqZKM9XJMtyqXi7HX/CiAZS6d8bSVUz5J36mEMFDTlAFQzxOT1dzLRljjB6+++ejFqka+mXIe6F59mw22OuOw1F4T6lg/9VjL1rLDoI9Xzl1MSYDNHnPQnt3D1EE7PrXjye/3pVpr1Z45hMUdcACc5NVQI0bOdS1WA0wuz73e7/5TNqBPhQXPEFGJNV2zNqWI7QKBd2Gn6AiBko02zuAOXeWIXjV0jNqdKegaE/kJQ6Bfs4aju04lMLkA2T5wBSYPKDGF3RKhFYEa6A1L1LG2yacmsaZ6YPOSAMKNsO+N5dNTfkc5Aqe26uxHpx7ZirvgCwJpWq/lmX1hA7LyabQ34tt5RiJKXSwQ+0KU0V5xg+hZrd4Bn1n4EID+WkQdgLfRNtvil9SPfwy+WQ7PFBWQz6dGWZBLkeJFXZGCfLUjCgGgqXo5TuSu3cugdcTv/HjqnBTEMwzAMwzAMwzAMwzAMw/zf/AFbXiOA6frlMAAAAABJRU5ErkJggg==" alt="">
				</div>
				<div class="weui-cell__ft">
					<input type="radio" class="weui-check" name="radio1" id="x{$v[value]}">
					<span class="weui-icon-checked"></span>
				</div>
			</label>
		</div>
		<!--{if $k%2==1}-->
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
	<a href="javascript:;" class="weui-btn weui-btn_default">上一题</a>
	<a href="javascript:;" class="weui-btn weui-btn_primary">选好了，下一题</a>
	<!--<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary">页面主操作 Disabled</a>-->

	<!--
	<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_default">页面次要操作 Disabled</a>
	<a href="javascript:;" class="weui-btn weui-btn_warn">警告类操作 Normal</a>
	<a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_warn">警告类操作 Disabled</a>
	-->
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