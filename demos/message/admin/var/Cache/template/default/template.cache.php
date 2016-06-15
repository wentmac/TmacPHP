<?php if (!class_exists('template', false)) die('Access Denied');
0
|| self::check('default\template.tpl', 'D:\Project\web\site\TmacPHP\demos\message\admin\application\View\default\template.tpl', 1465978375)
|| self::check('default\template.tpl', 'D:\Project\web\site\TmacPHP\demos\message\admin\application\View\default\inc/header_paul.tpl', 1465978375)
|| self::check('default\template.tpl', 'D:\Project\web\site\TmacPHP\demos\message\admin\application\View\default\inc/sidebar_paul.tpl', 1465978375)
|| self::check('default\template.tpl', 'D:\Project\web\site\TmacPHP\demos\message\admin\application\View\default\inc/footer_paul.tpl', 1465978375)
;?>
<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon-precomposed" href="/i/app-icon72x72@2x.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>用户中心</title>
    <meta name="description" content="用户中心">
    <meta name="keywords" content="index">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta name="apple-mobile-web-app-title" content="Amaze UI" />
    <link rel="stylesheet" href="<?php echo STATIC_URL; ?>common/amazeui/css/amazeui.min.css" />
    <link href="<?php echo STATIC_URL; ?>common/amazeui/css/admin.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $BASE_V;?>css/base.css" type="text/css" rel="stylesheet">
    <link href="<?php echo $BASE_V;?>css/goods_add.css" rel="stylesheet" type="text/css">   
    <link href="<?php echo $BASE_V;?>css/goods_edit.css" type="text/css" rel="stylesheet">    
    <link href="<?php echo $BASE_V;?>css/base.css" type="text/css" rel="stylesheet">
    <link href="<?php echo $BASE_V;?>css/page.css" type="text/css" rel="stylesheet">
    <link href="<?php echo $BASE_V;?>css/order_detail.css" type="text/css" rel="stylesheet">


<script src="<?php echo STATIC_URL; ?>common/amazeui/js/jquery.min.js" type="text/javascript"></script>
  </head>

  <body>
    
<!-- header start -->
  <header class="am-topbar admin-header">
    <div class="am-topbar-brand">
      <img src="<?php echo $BASE_V;?>image/logo-blue.png">管理后台
    </div>
    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>
    <div class="am-collapse am-topbar-collapse" id="topbar-collapse">
      <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
       <!-- <li><a href="javascript:;"><span class="am-icon-envelope-o"></span> 收件箱 <span class="am-badge am-badge-warning">5</span></a></li>-->
        <li class="am-dropdown" data-am-dropdown>
          <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
            <span class="am-icon-users"></span> 我的发大财 <span class="am-icon-caret-down"></span>
          </a>
          <ul class="am-dropdown-content">
            <!--<li><a href="#"><span class="am-icon-user"></span> 资料</a></li>
            <li><a href="#"><span class="am-icon-cog"></span> 设置</a></li>-->
            <li><a href="<?php echo ADMIN_URL; echo PHP_SELF; ?>?m=account.loginout"><span class="am-icon-power-off"></span> 退出</a></li>
          </ul>
        </li>
        <li class="am-hide-sm-only"><a href="javascript:;" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span> <span class="admin-fullText">开启全屏</span></a></li>        
      </ul>
    </div>
  </header>
  <!-- header end -->    <div class="am-cf admin-main">
          <style type="text/css">
    	.closed{
    		width: auto;
    	} 	
    	.closed #slider_bar{
    		display: inline;
    	}
    	#open_menu{display: none;}
    </style>
    
    	
    <!-- sidebar start -->
    <div class="admin-sidebar am-offcanvas closed" id="admin-offcanvas">
    	<div id="open_menu"  style="background-color: #fff;">
    		<a class="am-btn am-padding-sm am-btn-success" href="#" title="展开菜单">
    			<i class="am-icon-fw am-icon-angle-double-right"></i></a>
    	</div>
      <div id="slider_bar" class="am-offcanvas-bar admin-offcanvas-bar">
        <ul class="am-list admin-sidebar-list">
          <li><a href="#" id="close_menu"><span class="am-icon-fw am-icon-list-ul am-text-warning"></span><span class="am-text-warning">收起菜单</span><span class="am-icon-angle-double-left am-text-warning am-icon-fw am-fr am-margin-right-sm"></span></li></a>
          <?php if(is_array($menu_array)) foreach($menu_array AS $k => $sec1) { ?>
            <?php if(count($sec1['subname'])>0) { ?>
              <li class="admin-parent">
                <a class="am-cf" data-am-collapse="{target: '#collapse-nav-<?php echo $k;?>'}"><span class="<?php echo $sec1['class'];?>"></span><?php echo $sec1['title'];?><span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav-<?php echo $k;?>">
                    <?php if(is_array($sec1['subname'])) foreach($sec1['subname'] AS $kk => $sec2) { ?>
                    <li><a href="<?php echo $sec2['linktype'];?>" class="am-cf"><span class="<?php echo $sec2['class'];?>"></span><?php echo $sec2['nametype'];?></a></li>
                    <?php } ?>                    
                </ul>
              </li>            
            <?php } else { ?>
            <li><a href="<?php echo $sec1['link'];?>"><span class="<?php echo $sec1['class'];?>"></span><?php echo $sec1['title'];?></a></li>
            <?php } ?>          
          <?php } ?>
        </ul>        
        <div class="am-panel am-panel-default admin-sidebar-panel">
          <div class="am-panel-bd">
            <p><span class="am-icon-tag"></span> 最新消息</p>
            <p>云端产品库已经发布！
              <br></p>
          </div>
        </div>
      </div>
    </div>
    <!-- sidebar end -->      
      <div class="admin-content">
        <div class="am-cf am-padding">
          <div class="am-fl"><strong class="am-text-primary am-text-lg"><a href="<?php echo PHP_SELF; ?>?m=poster">模板管理</a></strong></div>
        </div>

        <div class="am-u-md-12">
          <div class="am-panel am-panel-default">
            <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}"><font class="bill_type_info">模板管理</font><span class="am-icon-chevron-down am-fr"></span></div>
            <div id="collapse-panel-2" class="am-in">                           



<?php if($action == 'index' ) { ?>
<h2>当前模板</h2>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody>
<tr>
        <td width="250" align="center"><img src="<?php echo $style_now['screenshot'];?>" id="screenshot"></td>
        <td valign="center" class="template">
        <ul>
          <li><span class="spanleft">模板名称:</span><strong><span class="span_right" id="style_name"><?php echo $style_now['name'];?></span></strong></li>
          <li><span class="spanleft">版 本 号:</span><span class="span_right" id="style_version"><?php echo $style_now['version'];?></span></li>
          <li><span class="spanleft">模板说明:</span><a target="_blank" id="style_uri" href="<?php echo $style_now['uri'];?>"><?php echo $style_now['desc'];?></a></li>
          <li><span class="spanleft">模板作者:</span><a target="_blank" id="style_author_uri" href="<?php echo $style_now['author_uri'];?>"><?php echo $style_now['author'];?></a></li>
          <li><span class="spanleft">模板目录:</span><span class="span_right" id="style_code"><?php echo $style_now['code'];?></span></li>
        </ul>
        </td></tr>
      </tbody></table>
<h2>可用模板</h2>
<div style="margin-left:10px; float:left">
<?php if(is_array($info)) foreach($info AS $k => $v) { ?>
<div style="display:-moz-inline-stack;display:inline-block;vertical-align:top;zoom:1;*display:inline;">
    <table style="width: 220px;">
      <tbody><tr>
        <td><strong><a target="_blank" href="<?php echo $v['uri'];?>"><?php echo $v['name'];?></a></strong></td>
      </tr>
      <tr>
        <td><img border="0" onclick="javascript:setupTemplate('<?php echo $v['code'];?>')" id="default" style="cursor:pointer; float:left; margin:0 2px;display:block;" src="<?php echo $v['screenshot'];?>" title="点击设置(<?php echo $v['code'];?>)为默认模板风格"></td>
      </tr>
      <tr>
        <td valign="top"><?php echo $v['desc'];?></td>
      </tr>
    </tbody></table>
    </div>     
<?php } ?>
</div>

<script language="javascript">
function setupTemplate(code){
  var r = confirm('您确定要启用选定的模板吗？');
  $('#loading').attr('display','block');
  if(r==true)
  {
    $.post("<?php echo PHP_SELF; ?>?m=template.ajaxSaveDefaultTemplate", {template_dir:code},
      function(data){
        var obj=eval("("+data+")");//转换为json对象      
        var error = obj.error;        
        if(error != null){
          alert(obj.error);
          return false;
        }
        var success = obj.success;        
        var screenshot = obj.style_now.screenshot;
        var code = obj.style_now.code;
        var name = obj.style_now.name;    
        var uri = obj.style_now.uri;
        var desc = obj.style_now.desc;
        var version = obj.style_now.version;
        var author = obj.style_now.author;
        var author_uri = obj.style_now.author_uri;
        alert(success);
        $('#loading').attr('display','none');
        $('#screenshot').attr('src',screenshot);
        $('#style_name').html(name);
        $('#style_version').html(version); 
        $('#style_uri').attr('href',uri);      
        $('#style_uri').html(desc);  
        $('#style_author_uri').attr('href',author_uri);        
        $('#style_author_uri').html(author); 
        $('#style_code').html(code);

    }); 
  }
  
}
</script>   
<?php } if($action == 'edit' ) { ?>
<h2>模板源文件修改</h2>
<div style="margin-left:10px; float:left">
<?php if(is_array($info)) foreach($info AS $k => $v) { ?>
<div style="display:-moz-inline-stack;display:inline-block;vertical-align:top;zoom:1;*display:inline;">
    <table style="width: 220px;">
      <tbody><tr>
        <td><strong><a target="_blank" href="<?php echo $v['uri'];?>"><?php echo $v['name'];?></a></strong></td>
      </tr>
      <tr>
        <td><a href="<?php echo PHP_SELF; ?>?m=template.temlist&dir=<?php echo $v['code'];?>" style="color:#09F"><img border="0" id="default" style="cursor:pointer; float:left; margin:0 2px;display:block;" src="<?php echo $v['screenshot'];?>" title="点击设置(<?php echo $v['code'];?>)为默认模板风格"></a></td>
      </tr>
      <tr>
        <td valign="top"><?php echo $v['desc'];?></td>
      </tr>
      <tr>
        <td valign="top"><a href="<?php echo PHP_SELF; ?>?m=template.temlist&dir=<?php echo $v['code'];?>" style="color:#09F">模板文件修改</a> | <a href="<?php echo PHP_SELF; ?>?m=template.stylelist&dir=<?php echo $v['code'];?>" style="color:#09F">JS/CSS修改</a></td>
      </tr>      
    </tbody></table>
    </div>     
<?php } ?>
</div>
<?php } if($action == 'temlist' ) { ?>
    <h2><?php echo $dir;?>目录模板文件列表（<?php echo $relative_tmp_dir;?>）<a href="<?php echo PHP_SELF; ?>?m=template.edit" style="color:#F00">返回模板列表</a></h2>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody><tr>
      <th width="60%"></th>
        <th width="30%">最后修改时间</th>
        <th width="10%">管理</th>
      </tr>
    <?php if($ErrorMsg) { ?>
    <tr>
      <td height=23 colspan=3 class=forumRowHigh align=center><?php echo $ErrorMsg;?></td>
    </tr>
    <?php } ?>     
      <?php echo $filelists;?> 
    <tr>
      <td height=23 colspan=3 class=forumRowHigh align=center><a href="<?php echo PHP_SELF; ?>?m=template.edit" style="color:#F00">返回模板列表</a></td>
    </tr>
      </tbody></table>

<?php } ?>   

<?php if($action == 'show' ) { ?>
    <h2><?php echo $dirname;?>目录模板文件列表  >> <a href="<?php echo PHP_SELF; ?>?m=template.temlist&dir=<?php echo $dirname;?>" style="color:#F00">返回模板列表</a></h2>
<form name="forms" id="forms" action="<?php echo PHP_SELF; ?>?m=template.showsave" method="post"  onSubmit="return chkFormTemplate();">    
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody><tr>
      <td width="100%" class="td_left" style="font-size:14px; line-height:30px; font-weight:bold; color:#0099CC; margin-left:-10px">编辑模板 - 模板文件名：<?php echo $dir;?> - 最后修改时间：<?php echo $edittime;?></td>
      </tr>

    <tr>
      <td><textarea name="info" id="info" style="width:99%;height:450px" rows="24" cols="150"><?php echo $template_file_info;?></textarea></td>
    </tr>
      
      
      <tr>
        <td class="td_left">
          <input type="hidden" name="dir" value="<?php echo $dir;?>" />
          <input type="hidden" name="dirname" id="dirname" value="<?php echo $dirname;?>" />             
          <input name="submit" type="submit" class="btn05" onmouseover="this.className='btn06'" onmouseout="this.className='btn05'" id="submit" value="提交" />
          <input type="reset" name="reset_button" value="重置" class="btn05" onmouseover="this.className='btn06'" onmouseout="this.className='btn05'">
          <input type="button" name="backbutton" id="backbutton" onClick="history.back(1);" value="返回" class="btn05" onmouseover="this.className='btn06'" onmouseout="this.className='btn05'"/></td>
      </tr>      
      </tbody></table>
</form>
<script language="javascript">
// JavaScript Document
function chkFormTemplate()
{
  if($('info').value == ""){
     alert("对不起，模板的内容不能为空！");   
     $('info').focus();
     return(false);
    }
}
</script>
<?php } ?>   

<?php if($action == 'stylelist' ) { ?>
    <h2><?php echo $dir;?>目录样式文件列表（<?php echo $relative_tmp_dir;?>）<a href="<?php echo PHP_SELF; ?>?m=template.edit" style="color:#F00">返回模板样式列表</a></h2>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody><tr>
      <th width="60%"></th>
        <th width="30%">最后修改时间</th>
        <th width="10%">管理</th>
      </tr>
    <?php if($ErrorMsg) { ?>
    <tr>
      <td height=23 colspan=3 class=forumRowHigh align=center><?php echo $ErrorMsg;?></td>
    </tr>
    <?php } ?>     
      <?php echo $filelists;?> 
    <tr>
      <td height=23 colspan=3 class=forumRowHigh align=center><a href="<?php echo PHP_SELF; ?>?m=template.edit" style="color:#F00">返回模板样式列表</a></td>
    </tr>
      </tbody></table>

<?php } ?>   

<?php if($action == 'showstyle' ) { ?>
    <h2><?php echo $dirname;?>目录新式文件列表  >> <a href="<?php echo PHP_SELF; ?>?m=template.stylelist&dir=<?php echo $dirname;?>" style="color:#F00">返回样式列表</a></h2>
<form name="forms" id="forms" action="<?php echo PHP_SELF; ?>?m=template.showstylesave" method="post"  onSubmit="return chkFormTemplate();">    
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody><tr>
      <td width="100%" class="td_left" style="font-size:14px; line-height:30px; font-weight:bold; color:#0099CC; margin-left:-10px">编辑样式 - 样式文件名：<?php echo $dir;?> - 最后修改时间：<?php echo $edittime;?></td>
      </tr>

    <tr>
      <td><textarea name="info" id="info" style="width:99%;height:450px" rows="24" cols="150"><?php echo $template_file_info;?></textarea></td>
    </tr>
      
      
      <tr>
        <td class="td_left">
          <input type="hidden" name="dir" value="<?php echo $dir;?>" />
          <input type="hidden" name="dirname" id="dirname" value="<?php echo $dirname;?>" />             
          <input name="submit" type="submit" class="btn05" onmouseover="this.className='btn06'" onmouseout="this.className='btn05'" id="submit" value="提交" />
          <input type="reset" name="reset_button" value="重置" class="btn05" onmouseover="this.className='btn06'" onmouseout="this.className='btn05'">
          <input type="button" name="backbutton" id="backbutton" onClick="history.back(1);" value="返回" class="btn05" onmouseover="this.className='btn06'" onmouseout="this.className='btn05'"/></td>
      </tr>      
      </tbody></table>
</form>
<script language="javascript">
// JavaScript Document
function chkFormTemplate()
{
  if($('info').value == ""){
     alert("对不起，模板的内容不能为空！");   
     $('info').focus();
     return(false);
    }
}
</script>
<?php } ?>   



            </div>
          </div>
      </div>

    </div>
    <a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>
    <!-- content end -->
    <footer>
	<div class="am-modal am-modal-prompt" tabindex="-1" id="my-prompt">
		<div class="am-modal-dialog">
			<div class="am-modal-hd">提醒</div>
			<div class="am-modal-bd">
				来来来，吐槽点啥吧
			</div>
			<input type="text" class="am-modal-prompt-input">
			<hr>	
			<div class="am-modal-footer">
				
				<span class="am-modal-btn" data-am-modal-cancel>取消</span>
				<span class="am-modal-btn" data-am-modal-confirm>提交</span>
			</div>
		</div>
	</div>

	<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
		<div class="am-modal-dialog">
			<div class="am-modal-hd">提醒</div>
			<div class="am-modal-bd" id="confirm_content">
				你，确定要删除这条记录吗？
			</div>
			<div class="am-modal-footer">
				<span class="am-modal-btn" data-am-modal-cancel>取消</span>
				<span class="am-modal-btn" data-am-modal-confirm>确定</span>
			</div>
		</div>
	</div>
	<hr>
	<p class="am-padding-left am-text-center">© 2015 TmacMVC, Inc.</p>
</footer>  </body>

</html>
<script>
  var index_url = '<?php echo INDEX_URL; ?>';
  var static_url = '<?php echo STATIC_URL; ?>';
  var base_v = '<?php echo $BASE_V;?>';
  var php_self = '<?php echo PHP_SELF; ?>';
  var param = {
    status: '',
    pagesize: 6,
    page: 1,
    totalput: 0
  }
</script>

<script src="<?php echo STATIC_URL; ?>common/amazeui/js/amazeui.js" type="text/javascript"></script>
<script src="<?php echo STATIC_URL; ?>common/amazeui/js/app.js" type="text/javascript"></script>
<script src="<?php echo STATIC_URL; ?>js/jquery-plugin/jquery.pagination-min.js" type="text/javascript"></script>
<script src="<?php echo STATIC_URL; ?>js/jquery-plugin/ui/minified/jquery.cookie-min.js" type="text/javascript"></script>

<script src="<?php echo $BASE_V;?>js/modal_html.js" type="text/javascript"></script>

<script src="<?php echo STATIC_URL; ?>js/ajaxfileupload.js" type="text/javascript"></script>
<script src="<?php echo STATIC_URL; ?>js/ThumbAjaxFileUpload.js" type="text/javascript"></script>
<script src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js" type="text/javascript"></script>