<?php if (!class_exists('template', false)) die('Access Denied');
0
|| self::check('default\account_login.tpl', 'D:\Project\web\site\TmacPHP\demos\message\admin\application\View\default\account_login.tpl', 1465977851)
;?>
<!DOCTYPE html>
<html>

<head lang="en">
  <meta charset="UTF-8">
  <title>用户登录－<?php echo $config['cfg_webname'];?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="stylesheet" href="<?php echo STATIC_URL; ?>common/amazeui/css/amazeui.min.css" />
  <style>
  .header {
    text-align: center;
  }
  
  .header h1 {
    font-size: 200%;
    color: #333;
    margin-top: 30px;
  }
  
  .header p {
    font-size: 14px;
  }
  </style>
</head>

<body>
  <div class="header">
    <div class="am-g">
      <h1>用户登录</h1>
      <p>后台系统</p>
    </div>
    <hr />
  </div>

  <div class="am-g">
  <div class="am-u-lg-4 am-u-md-4 am-u-sm-centered">
      <form method="post" class="am-form" id="form_login">
      <div class="am-form-group">
        <label for="account_name">用户名：</label>
        <input type="text" required minlength="3" class="am-radius"  name="account_name" id="account_name" placeholder="用户名" value="" required>
      </div>
      <div class="am-form-group">
        <label for="account_pwd">密码：</label>
        <input type="password" required minlength="6" name="account_pwd" class="am-radius" id="account_pwd" placeholder="密码" value="">
      </div>
      <div class="am-form-group">
        <label for="rember_pwd">
            <input id="rember_pwd" name="rember_pwd" value="1" type="checkbox"> <span style="font-weight:normal">记住密码</span>
        </label>                
      </div>
        <div class="am-form-group">                  
            <button id="btn_submit" type="submit" class="am-btn am-btn-primary am-btn-block"><i class="am-icon-lock am-icon-fw"></i> 登　录</button>                            
        </div>        
      </form>
  </div>
  </div>
  <footer>
    <hr>
    <p class="am-padding-left am-text-center">© 2015 TmacMVC, Inc.</p>
  </footer>
  <script src="<?php echo STATIC_URL; ?>common/amazeui/js/jquery.min.js" type="text/javascript"></script>
  <script src="<?php echo STATIC_URL; ?>common/amazeui/js/amazeui.js" type="text/javascript"></script>
  <script src="<?php echo STATIC_URL; ?>js/modal_html.js" type="text/javascript"></script>
  <script type="text/javascript">
  var index_url = '<?php echo ADMIN_URL; ?>';
  var static_url = '<?php echo STATIC_URL; ?>';
  var base_v = '<?php echo $BASE_V;?>';
  var php_self = '<?php echo PHP_SELF; ?>';
  var postField = new Object();
  var mobile_url = '<?php echo MOBILE_URL; ?>';
  </script>
  <script src="<?php echo $BASE_V;?>js/account_login.js" type="text/javascript"></script>
</body>

</html>
