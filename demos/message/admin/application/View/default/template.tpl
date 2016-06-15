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
    <link rel="stylesheet" href="{STATIC_URL}common/amazeui/css/amazeui.min.css" />
    <link href="{STATIC_URL}common/amazeui/css/admin.css" rel="stylesheet" type="text/css">
    <link href="{$BASE_V}css/base.css" type="text/css" rel="stylesheet">
    <link href="{$BASE_V}css/goods_add.css" rel="stylesheet" type="text/css">   
    <link href="{$BASE_V}css/goods_edit.css" type="text/css" rel="stylesheet">    
    <link href="{$BASE_V}css/base.css" type="text/css" rel="stylesheet">
    <link href="{$BASE_V}css/page.css" type="text/css" rel="stylesheet">
    <link href="{$BASE_V}css/order_detail.css" type="text/css" rel="stylesheet">


<script type="text/javascript" src="{STATIC_URL}common/amazeui/js/jquery.min.js"></script>
  </head>

  <body>
    <!--{template inc/header_paul}-->
    <div class="am-cf admin-main">
      <!--{template inc/sidebar_paul}-->      
      <div class="admin-content">
        <div class="am-cf am-padding">
          <div class="am-fl"><strong class="am-text-primary am-text-lg"><a href="{PHP_SELF}?m=poster">模板管理</a></strong></div>
        </div>

        <div class="am-u-md-12">
          <div class="am-panel am-panel-default">
            <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}"><font class="bill_type_info">模板管理</font><span class="am-icon-chevron-down am-fr"></span></div>
            <div id="collapse-panel-2" class="am-in">                           



<!--{if $action == 'index' }-->
<h2>当前模板</h2>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody>
<tr>
        <td width="250" align="center"><img src="{$style_now[screenshot]}" id="screenshot"></td>
        <td valign="center" class="template">
        <ul>
          <li><span class="spanleft">模板名称:</span><strong><span class="span_right" id="style_name">$style_now[name]</span></strong></li>
          <li><span class="spanleft">版 本 号:</span><span class="span_right" id="style_version">$style_now[version]</span></li>
          <li><span class="spanleft">模板说明:</span><a target="_blank" id="style_uri" href="{$style_now[uri]}">$style_now[desc]</a></li>
          <li><span class="spanleft">模板作者:</span><a target="_blank" id="style_author_uri" href="{$style_now[author_uri]}">$style_now[author]</a></li>
          <li><span class="spanleft">模板目录:</span><span class="span_right" id="style_code">$style_now[code]</span></li>
        </ul>
        </td></tr>
      </tbody></table>
<h2>可用模板</h2>
<div style="margin-left:10px; float:left">
<!--{loop $info $k $v}-->
<div style="display:-moz-inline-stack;display:inline-block;vertical-align:top;zoom:1;*display:inline;">
    <table style="width: 220px;">
      <tbody><tr>
        <td><strong><a target="_blank" href="{$v[uri]}">$v[name]</a></strong></td>
      </tr>
      <tr>
        <td><img border="0" onclick="javascript:setupTemplate('{$v[code]}')" id="default" style="cursor:pointer; float:left; margin:0 2px;display:block;" src="{$v[screenshot]}" title="点击设置({$v[code]})为默认模板风格"></td>
      </tr>
      <tr>
        <td valign="top">$v[desc]</td>
      </tr>
    </tbody></table>
    </div>     
<!--{/loop}-->
</div>

<script language="javascript">
function setupTemplate(code){
  var r = confirm('您确定要启用选定的模板吗？');
  $('#loading').attr('display','block');
  if(r==true)
  {
    $.post("{PHP_SELF}?m=template.ajaxSaveDefaultTemplate", {template_dir:code},
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
<!--{/if}-->


<!--{if $action == 'edit' }-->
<h2>模板源文件修改</h2>
<div style="margin-left:10px; float:left">
<!--{loop $info $k $v}-->
<div style="display:-moz-inline-stack;display:inline-block;vertical-align:top;zoom:1;*display:inline;">
    <table style="width: 220px;">
      <tbody><tr>
        <td><strong><a target="_blank" href="{$v[uri]}">$v[name]</a></strong></td>
      </tr>
      <tr>
        <td><a href="{PHP_SELF}?m=template.temlist&dir={$v[code]}" style="color:#09F"><img border="0" id="default" style="cursor:pointer; float:left; margin:0 2px;display:block;" src="{$v[screenshot]}" title="点击设置({$v[code]})为默认模板风格"></a></td>
      </tr>
      <tr>
        <td valign="top">$v[desc]</td>
      </tr>
      <tr>
        <td valign="top"><a href="{PHP_SELF}?m=template.temlist&dir={$v[code]}" style="color:#09F">模板文件修改</a> | <a href="{PHP_SELF}?m=template.stylelist&dir={$v[code]}" style="color:#09F">JS/CSS修改</a></td>
      </tr>      
    </tbody></table>
    </div>     
<!--{/loop}-->
</div>
<!--{/if}-->

<!--{if $action == 'temlist' }-->
    <h2>{$dir}目录模板文件列表（{$relative_tmp_dir}）<a href="{PHP_SELF}?m=template.edit" style="color:#F00">返回模板列表</a></h2>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody><tr>
      <th width="60%"></th>
        <th width="30%">最后修改时间</th>
        <th width="10%">管理</th>
      </tr>
    <!--{if $ErrorMsg}-->
    <tr>
      <td height=23 colspan=3 class=forumRowHigh align=center>$ErrorMsg</td>
    </tr>
    <!--{/if}-->     
      $filelists 
    <tr>
      <td height=23 colspan=3 class=forumRowHigh align=center><a href="{PHP_SELF}?m=template.edit" style="color:#F00">返回模板列表</a></td>
    </tr>
      </tbody></table>

<!--{/if}-->   

<!--{if $action == 'show' }-->
    <h2>{$dirname}目录模板文件列表  >> <a href="{PHP_SELF}?m=template.temlist&dir={$dirname}" style="color:#F00">返回模板列表</a></h2>
<form name="forms" id="forms" action="{PHP_SELF}?m=template.showsave" method="post"  onSubmit="return chkFormTemplate();">    
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody><tr>
      <td width="100%" class="td_left" style="font-size:14px; line-height:30px; font-weight:bold; color:#0099CC; margin-left:-10px">编辑模板 - 模板文件名：{$dir} - 最后修改时间：{$edittime}</td>
      </tr>

    <tr>
      <td><textarea name="info" id="info" style="width:99%;height:450px" rows="24" cols="150">$template_file_info</textarea></td>
    </tr>
      
      
      <tr>
        <td class="td_left">
          <input type="hidden" name="dir" value="$dir" />
          <input type="hidden" name="dirname" id="dirname" value="$dirname" />             
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
<!--{/if}-->   

<!--{if $action == 'stylelist' }-->
    <h2>{$dir}目录样式文件列表（{$relative_tmp_dir}）<a href="{PHP_SELF}?m=template.edit" style="color:#F00">返回模板样式列表</a></h2>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody><tr>
      <th width="60%"></th>
        <th width="30%">最后修改时间</th>
        <th width="10%">管理</th>
      </tr>
    <!--{if $ErrorMsg}-->
    <tr>
      <td height=23 colspan=3 class=forumRowHigh align=center>$ErrorMsg</td>
    </tr>
    <!--{/if}-->     
      $filelists 
    <tr>
      <td height=23 colspan=3 class=forumRowHigh align=center><a href="{PHP_SELF}?m=template.edit" style="color:#F00">返回模板样式列表</a></td>
    </tr>
      </tbody></table>

<!--{/if}-->   

<!--{if $action == 'showstyle' }-->
    <h2>{$dirname}目录新式文件列表  >> <a href="{PHP_SELF}?m=template.stylelist&dir={$dirname}" style="color:#F00">返回样式列表</a></h2>
<form name="forms" id="forms" action="{PHP_SELF}?m=template.showstylesave" method="post"  onSubmit="return chkFormTemplate();">    
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody><tr>
      <td width="100%" class="td_left" style="font-size:14px; line-height:30px; font-weight:bold; color:#0099CC; margin-left:-10px">编辑样式 - 样式文件名：{$dir} - 最后修改时间：{$edittime}</td>
      </tr>

    <tr>
      <td><textarea name="info" id="info" style="width:99%;height:450px" rows="24" cols="150">$template_file_info</textarea></td>
    </tr>
      
      
      <tr>
        <td class="td_left">
          <input type="hidden" name="dir" value="$dir" />
          <input type="hidden" name="dirname" id="dirname" value="$dirname" />             
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
<!--{/if}-->   



            </div>
          </div>
      </div>

    </div>
    <a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>
    <!-- content end -->
    <!--{template inc/footer_paul}-->
  </body>

</html>
<script>
  var index_url = '{INDEX_URL}';
  var static_url = '{STATIC_URL}';
  var base_v = '{$BASE_V}';
  var php_self = '{PHP_SELF}';
  var param = {
    status: '',
    pagesize: 6,
    page: 1,
    totalput: 0
  }
</script>

<script type="text/javascript" src="{STATIC_URL}common/amazeui/js/amazeui.js"></script>
<script type="text/javascript" src="{STATIC_URL}common/amazeui/js/app.js"></script>
<script type="text/javascript" src="{STATIC_URL}js/jquery-plugin/jquery.pagination-min.js"></script>
<script src="{STATIC_URL}js/jquery-plugin/ui/minified/jquery.cookie-min.js" type="text/javascript"></script>

<script type="text/javascript" src="{$BASE_V}js/modal_html.js"></script>

<script type="text/javascript" src="{STATIC_URL}js/ajaxfileupload.js"></script>
<script type="text/javascript" src="{STATIC_URL}js/ThumbAjaxFileUpload.js"></script>
<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>