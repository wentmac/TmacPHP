<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="{$BASE_V}layout.css" rel="stylesheet" type="text/css"/>
    <title>TBlog博客系统</title>
    <script type="text/javascript" src="{STATIC_URL}js/tools.js"></script>
    <script type="text/javascript" src="{$BASE_V}article.js"></script>
</head>
<body>

<div style="z-index: 1; right: 20px; top: 30px; color: rgb(255, 255, 255); position: absolute; display: none;"
     id="loading"><img src="{$BASE_V}images/loader.gif"></div>

<div id="main">
    <div class="main_box">

        <h2>内容列表</h2>
        <form method="GET" action="{PHP_SELF}" onSubmit="return checkSearchForm();">
            <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
                <tr>
                    <td align="center">
                        提现状态 :
                        <select name="status" id="status">
                            <option value=0>全部</option>
                            $settle_status_option
                        </select>
                        　
                        关键词：<input type="text" name="query_string" value="{$query_string}"
                                   placeholder='输入：用户id/手机号/用户名 搜索' size=30>　
                        <input type="hidden" name="m" value="settle.index"/>
                        <input type="submit" name="search_btn" value="　搜索　">
                    </td>
                </tr>
            </table>
        </form>

        <form name="list_form" method="POST" action="{PHP_SELF}?m=settle.index&page={$pageCurrent}"
              onSubmit="return checkDelForm();">
            <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
                <tbody>
                <tr>
                    <th>&nbsp;</th>
                    <th width="5%">编号</th>
                    <th width="5%">用户UID</th>
                    <th width="10%">用户提现微信昵称</th>
                    <th width="20%">提现备注</th>
                    <th width="10%">提现金额</th>
                    <th width="5%">提现手续费</th>
                    <th width="10%">申请时间</th>
                    <th width="8%">打款时间</th>
                    <th width="5%">操作人姓名</th>
                    <th width="10%">状态</th>
                    <th width="10%">管理</th>
                </tr>
                <!--{if $ErrorMsg}-->
                <tr>
                    <td height=23 colspan=8 class=forumRowHigh align=center>$ErrorMsg</td>
                </tr>
                <!--{/if}-->
                <!--{loop $rs $k $v}-->
                <tr onmouseout="this.style.background='#fff'" onmouseover="this.style.background='#f6f9fd'"
                    style="background: none repeat scroll 0% 0% rgb(255, 255, 255);">

                    <td><input type="checkbox" value="{$v->settle_id}" name="id_a[]"></td>
                    <td>$v->settle_id</td>
                    <td><a href="{PHP_SELF}?m=settle.member_bill_list&uid={$v->uid}">$v->uid</a></td>
                    <td><a href="{PHP_SELF}?m=settle.member_bill_list&uid={$v->uid}">$v->realname</a></td>
                    <td>$v->settle_note</td>
                    <td>￥：{$v->money}</td>
                    <td>￥：{$v->commission_charge}</td>
                    <td>$v->settle_apply_time</td>
                    <td>$v->settle_execute_time</td>
                    <td>$v->admin_username</td>
                    <td>$v->settle_status</td>
                    <td><a href="{PHP_SELF}?m=settle.detail&id={$v->settle_id}">详细/操作</a></td>
                </tr>
                <!--{/loop}-->
                <tr style="background: none repeat scroll 0% 0% rgb(248, 248, 248);">
                    <td><input type="checkbox" name="select_all_btn" onclick="select_fx();"></td>
                    <td>
                        <select name="do" id='do'>$article_do_ary_option</select></td>
                    <td class="td_left" colspan="12">
                        <input type="submit" onclick="return confirm('确定要执行这次操作吗？会同步删除所有分销商的该商品哟')" class="btn02"
                               value="确定" name="Submit">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
        {$page}
    </div>
</div>
</body>
</html>
<script src="{STATIC_URL}js/jquery/1.11.2/jquery-1.11.2.min.js" type="text/javascript"></script>
<script>
    jq = jQuery.noConflict();
    jq(document).ready(function () {

    });
</script>