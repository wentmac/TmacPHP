<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{$BASE_V}layout.css" rel="stylesheet" type="text/css" />
<title>TBlog博客系统</title>
<script type="text/javascript" src="{$BASE}js/tools.js"></script>
<script language="javascript">
var php_self = '{PHP_SELF}';
</script>
<script type="text/javascript" src="{$BASE_V}comment.js"></script>
</head>
<body>

<div style="z-index: 1; right: 20px; top: 30px; color: rgb(255, 255, 255); position: absolute; display: none;" id="loading"><img src="{$BASE_V}images/loader.gif"></div>

<div id="main">
  <div class="main_box">    
<!--{if $action == 'add' }-->    
<h2>编辑评论</h2>
<form name="modform" id="forms" action="{PHP_SELF}?m=comment.save" method="post"  onSubmit="return chkForm();">    
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody><tr>
        <td width="100" class="td_right">所在文章：</td>
        <td class="td_left">$editinfo->title</td>
      </tr>
      <tr>
        <td width="100" class="td_right">评论作者：</td>
        <td class="td_left"><input type="text" size="40" value="{$editinfo->comment_author}" id="comment_author" name="comment_author">
         </td>
      </tr>
      <tr>
        <td width="100" class="td_right">电子邮件：</td>
        <td class="td_left"><input type="text" size="40" value="{$editinfo->comment_author_email}" id="comment_author_email" name="comment_author_email">
         </td>
      </tr> 
      <tr>
        <td width="100" class="td_right">评论作者联系方式：</td>
        <td class="td_left"><input type="text" size="40" value="{$editinfo->comment_author_url}" id="comment_author_url" name="comment_author_url">
         </td>
      </tr>             
      <tr>
        <td width="100" class="td_right">评论内容：</td>
        <td class="td_left"><textarea name="comment_content" id="comment_content" cols="68" rows="10">$editinfo->comment_content</textarea>
         </td>
      </tr>            
      
      <tr>
        <td class="td_right">&nbsp;</td>
        <td class="td_left">
          <input type="hidden" value="{$editinfo->comment_id}" name="comment_id">
          <input type="submit" value="提交" id="submit" onmouseout="this.className='btn05'" onmouseover="this.className='btn06'" class="btn05" name="submit">
          <input type="reset" class="btn05" value="清除" name="reset_button">
          <input type="button" value="返回" onclick="history.back(1);" class="btn05" id="backbutton" name="backbutton"></td>
      </tr>
    
    </tbody></table>
</form>    
<!--{/if}-->
    
<!--{if $action == 'index' }-->
<h2>评论管理</h2>
<form method="GET" action="{PHP_SELF}" onSubmit="return checkSearchForm();">
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
   <tr>
	<td align="center">
    显示 : 
    <select name="comment_approved" id="comment_approved">
        $comment_type_ary_option
    </select>
         	　
	关键词：<input type="text" name="search_keyword" value="">　
	<input type="hidden" name="m" value="comment"/>
	<input type="submit" name="search_btn" value="　搜索　">
	</td>
  </tr>
</table>
</form>

<form name="list_form" method="POST" action="{PHP_SELF}?m=comment.comment_do&page={$pageCurrent}" onSubmit="return checkDelForm();">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody><tr>
      <th>&nbsp;</th>
        <th width="5%">编号</th>
        <th width="10%">评论作者</th>
        <th width="10%">邮箱地址</th>    
        <th width="10%">IP地址</th>        
        <th width="35%">内容</th>
        <th width="13%">评论时间</th>                
        <th width="10%">显示状态</th>        
        <th width="8%">管理</th>
      </tr>
	  <!--{if $ErrorMsg}-->
	  <tr>
	    <td height=23 colspan=7 class=forumRowHigh align=center>$ErrorMsg</td>
	  </tr>
	  <!--{/if}-->      
      <!--{loop $rs $k $v}-->
      <tr onmouseout="this.style.background='#fff'" onmouseover="this.style.background='#f6f9fd'" style="background: none repeat scroll 0% 0% rgb(255, 255, 255);">
      
      <td><input type="checkbox" value="{$v->comment_id}" name="id_a[]"></td>
      <td>$v->comment_id</td>
      <td class="td_left"><a href="{PHP_SELF}?m=comment.add&comment_id={$v->comment_id}">$v->comment_author</a></td>
      <td class="td_left">$v->comment_author_email</td>  
      <td>$v->comment_author_ip</td> 
      <td>${echo Functions::cut_str($v->comment_content,24)}</td> 
      <td>$v->time</td>   
      <td id="type_{$v->comment_id}"><a style="cursor:pointer" onclick="changeCommentType({$v->comment_id},{$v->comment_approved})" {if $v->comment_approved==1}title="点击变为隐藏状态" class="yes"{elseif $v->comment_approved==0}title="点击变为显示状态" class="no"{/if}>$v->type</a></td>                   
      <td><a href="{PHP_SELF}?m=comment.add&comment_id={$v->comment_id}">编辑</a> | <a href="{PHP_SELF}?m=comment.comment_do&action=del&id={$v->comment_id}">删除</a></td>
      </tr>
      <!--{/loop}-->      
      <tr style="background: none repeat scroll 0% 0% rgb(248, 248, 248);">
              <td><input type="checkbox" name="select_all_btn" onclick="select_fx();"></td>
              <td><select name="do" id='comment_do'>$comment_do_ary_option</select></td>              
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