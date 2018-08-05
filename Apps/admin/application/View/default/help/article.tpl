<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{$BASE_V}layout.css" rel="stylesheet" type="text/css" />
<title>TBlog博客系统</title>
<script type="text/javascript" src="{STATIC_URL}js/tools.js"></script>
<script type="text/javascript" src="{$BASE_V}article.js"></script>
</head>
<body>

<div style="z-index: 1; right: 20px; top: 30px; color: rgb(255, 255, 255); position: absolute; display: none;" id="loading"><img src="{$BASE_V}images/loader.gif"></div>

<div id="main">
  <div class="main_box">    
<!--{if $action == 'add' }-->    
    <h2>添加文章</h2>
<form name="forms" id="forms" action="{PHP_SELF}?m=help/article.save" method="post"  onSubmit="return chkForm();">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody>
      <tr>
        <td class="td_right_f00" width="150">标题：</td>
        <td class="td_left" colspan="2"><input type="text" size="100" value="{$editinfo->help_title}" id="help_title" name="help_title">
	  </td>
      </tr>
	  
       <tr>
        <td class="td_right">选择分类：</td>
        <td class="td_left">
            <select name="help_cat_id" id="help_cat_id">
    			<option value="0">|-根分类</option>
				{$treers}
		    </select>
        </td>
      </tr>

      <tr>
          <td class="td_right">关键字</td>
          <td class="td_left"> <input type="text" size="35" name="help_keywords" id="help_keywords" value="{$editinfo->help_keywords}"> keywords</td>
      </tr>

      <tr>
          <td class="td_right">描述</td>
          <td class="td_left" colspan="2"><textarea id="help_description" rows="4" style="height:50px" cols="70" name="help_description">$editinfo->help_description</textarea> description</td>
      </tr>        
            
      <tr>
        <td class="td_right">内容：</td>
        <td class="td_left">
		<textarea name="help_content" id="help_content" rows="60" cols="100" class="editor">{$editinfo->help_content}</textarea>        
		</td>
      </tr>           
            
		<tr>
			<td class="td_right">排序：</td>
			<td class="td_left"><input type="text" value="{$editinfo->help_sort}" id="help_sort" name="help_sort"></td>
		</tr>
		
		<tr>
			<td class="td_right">推荐：</td>
			<td class="td_left">
				<select name="help_recommend" id="help_recommend">					
					{$help_recommend_option}
				</select>
			</td>
		</tr>
                
		<tr>
			<td class="td_right">&nbsp;</td>
			<td class="td_left">
			<input type="hidden" name="id" value="{$editinfo->help_article_id}" />          
			<input name="submit" type="submit" class="btn05" onmouseover="this.className='btn06'" onmouseout="this.className='btn05'" id="submit" value="提交" />
			<input type="reset" name="reset_button" value="清除" class="btn05" onmouseover="this.className='btn06'" onmouseout="this.className='btn05'">
			<input type="button" name="backbutton" id="backbutton" onClick="history.back(1);" value="返回" class="btn05" onmouseover="this.className='btn06'" onmouseout="this.className='btn05'"/></td>
		</tr>
    
    </table>
</form>
<script type="text/javascript" src="{STATIC_URL}js/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="{STATIC_URL}js/xheditor-1.2.2/xheditor-1.2.2.min.js?v=20150822"></script>
<script type="text/javascript" src="{STATIC_URL}js/xheditor-1.2.2/xheditor_lang/zh-cn.js"></script>
<script type="text/javascript" src="{STATIC_URL}js/ajaxfileupload.js"></script>
<script type="text/javascript" src="{STATIC_URL}js/ThumbAjaxFileUpload.js"></script>
<script language="javascript">
jq = jQuery.noConflict(); 
//以后jquery中的都用jq代替即可。
var editor;
jq(pageInit);
function pageInit()
{	
	editor = jq('#help_content').xheditor({
		tools:'full',
		upImgUrl:"{PHP_SELF}?m=tool.uploadImg&action=help",		
		upImgExt:"jpg,jpeg,gif,png",
		loadCSS:'<style>pre{margin-left:2em;border-left:3px solid #CCC;padding:0 1em;}</style>'
	}); 
}
//jq('#content').xheditor({upLinkUrl:"Public/uploadfiles/upload.php",upLinkExt:"zip,rar,txt",upImgUrl:"Public/uploadfiles/upload.php",upImgExt:"jpg,jpeg,gif,png",upFlashUrl:"Public/uploadfiles/upload.php",upFlashExt:"swf",upMediaUrl:"Public/uploadfiles/upload.php",upMediaExt:"avi"});
function chkForm()
{
	if($('help_title').value == ""){
	   alert("请填写标题！");   
	   $('help_title').focus();
	   return(false);
   	}

	if(jq('#help_content').val() == ""){
	   alert("请填写内容！");   
	   $('help_content').focus();	   
	   return(false);
   	}	
		
}

jq(document).ready(function(){
  	
});
</script>
<!--{/if}-->
    
<!--{if $action == 'index' }-->
    <h2>内容列表</h2>
<form method="GET" action="{PHP_SELF}" onSubmit="return checkSearchForm();">
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
   <tr>
	<td align="center">
    显示 : 
    <select name="help_cat_id" id="help_cat_id">
	<option value="0">|-全部</option>
     $treers
    </select>
         	　
	关键词：<input type="text" name="query_string" value="{$query_string}">　
	<input type="hidden" name="m" value="help/article"/>
	<input type="submit" name="search_btn" value="　搜索　">	
	</td>	
  </tr>
</table>
</form>
    
<form name="list_form" method="POST" action="{PHP_SELF}?m=help/article.article_do&page={$pageCurrent}" onSubmit="return checkDelForm();">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody><tr>
      <th>&nbsp;</th>
        <th width="5%">编号</th>        
        <th width="35%">标题</th>
        <th width="20%">分类</th>        
        <th width="27%">添加时间</th>        
        <th width="20%">管理</th>
      </tr>
	  <!--{if $ErrorMsg}-->
	  <tr>
	    <td height=23 colspan=8 class=forumRowHigh align=center>$ErrorMsg</td>
	  </tr>
	  <!--{/if}-->      
      <!--{loop $rs $k $v}-->
      <tr onmouseout="this.style.background='#fff'" onmouseover="this.style.background='#f6f9fd'" style="background: none repeat scroll 0% 0% rgb(255, 255, 255);">
      
      <td><input type="checkbox" value="{$v->help_article_id}" name="id_a[]"></td>
      <td>$v->help_article_id</td>
      <td class="td_left">{$v->help_title}</td>
      <td><a href="{PHP_SELF}?m=help/article.index&cat_id={$v->help_cat_id}">{$v->cat_name}</a></td>      
      <td>$v->help_time</td>      
      <td><a href="{PHP_SELF}?m=help/article.add&id={$v->help_article_id}">修改</a> | <a href="{PHP_SELF}?m=help/article.article_do&action=del&id={$v->help_article_id}" onclick="{if(confirm('删除将包括该信息，确定删除吗?')){return true;}return false;}">删除</a></td>
      </tr>
      <!--{/loop}-->      
      <tr style="background: none repeat scroll 0% 0% rgb(248, 248, 248);">
              <td><input type="checkbox" name="select_all_btn" onclick="select_fx();"></td>
              <td><select name="do" id='do'>$article_do_ary_option</select></td>              
              <td class="td_left" colspan="7">
            <input type="submit" onclick="return confirm('确定要执行这次操作吗？')" class="btn02" value="确定" name="Submit">
      </td></tr>
      </tbody></table>
      </form>
      {$page}
<!--{/if}-->     


	</div>
</div>
</body>
</html>