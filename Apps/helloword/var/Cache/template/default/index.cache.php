<?php if (!class_exists('template', false)) die('Access Denied');
0
|| self::check('default/index.tpl', '/var/www/project/web/site/tmacphp/Apps/helloword/application/View/default/index.tpl', 1533456858)
;?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=640,user-scalable=no">
	<meta content="telephone=no" name="format-detection">
	<meta name="apple-touch-fullscreen" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title><?php echo $title;?></title>

</head>

<body>
<?php echo $title;?>
</body>

</html>