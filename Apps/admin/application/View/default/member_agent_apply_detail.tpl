<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{$BASE_V}layout.css" rel="stylesheet" type="text/css" />
<title>TBlog博客系统</title>
<script type="text/javascript" src="{STATIC_URL}js/tools.js"></script>
</head>
<body>

<div style="z-index: 1; right: 20px; top: 30px; color: rgb(255, 255, 255); position: absolute; display: none;" id="loading"><img src="{$BASE_V}images/loader.gif"></div>

<div id="main">
  <div class="main_box">    

    <h2>会员代理申请审核详情</h2>
<form name="forms" id="forms" action="{PHP_SELF}?m=member.agent_apply_save" method="post"  onSubmit="return chkForm();">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody>
      <tr>
        <td class="td_right_f00" width="150">用户uid：</td>
        <td class="td_left" colspan="2">{$memberInfo->uid}</td>
      </tr>
	  <tr>
        <td class="td_right_f00" width="150">用户username：</td>
        <td class="td_left" colspan="2">{$memberInfo->username}</td>
      </tr>
      <tr>
          <td class="td_right_f00" width="150">手机号码：</td>
          <td class="td_left" colspan="2">{$memberSettingInfo->member_contact}</td>
      </tr>
	  <tr>
        <td class="td_right_f00" width="150">用户真实姓名：</td>
        <td class="td_left" colspan="2">{$memberInfo->realname}</td>
      </tr>
	  <tr>
        <td class="td_right_f00" width="150">身份证号：</td>
        <td class="td_left" colspan="2">{$memberSettingInfo->idcard}</td>
      </tr>
      <tr>
          <td class="td_right_f00" width="150">备注：</td>
          <td class="td_left" colspan="2">{$memberSettingInfo->remark}</td>
      </tr>


      <tr id="member_type_dfh_agent">
        <td class="td_right" width="150">地区：</td>
        <td class="td_left" colspan="2">
          <select id="province" name="province" class="block input" tabindex="3" data-region-id="2">
          </select>
          <select id="city" name="city" class="block input" tabindex="4" data-region-id="52">
          </select>
          <select id="district" name="district" class="block input" tabindex="5" data-region-id="500">
          </select>
        </td>
      </tr>
      <tr>
          <td class="td_right_f00" width="150">详细街道：</td>
          <td class="td_left" colspan="2">{$memberSettingInfo->agent_address}</td>
      </tr>
      <tr>
          <td class="td_right">审核操作：</td>
          <td class="td_left">
              <input type="radio" value="1" name="verify" id="verify_1"><label for="verify_1">审核通过</label>     <input type="radio" value="0" name="verify" id="verify_0"><label for="verify_0">审核不通过</label>
          </td>
      </tr>
      <tr>
        <td class="td_right">&nbsp;</td>
        <td class="td_left">
          <input type="hidden" name="uid" value="{$memberInfo->uid}" />          
          <input name="submit" type="submit" class="btn05" onmouseover="this.className='btn06'" onmouseout="this.className='btn05'" id="submit" value="提交" />
          <input type="reset" name="reset_button" value="清除" class="btn05" onmouseover="this.className='btn06'" onmouseout="this.className='btn05'">
          <input type="button" name="backbutton" id="backbutton" onClick="history.back(1);" value="返回" class="btn05" onmouseover="this.className='btn06'" onmouseout="this.className='btn05'"/></td>
      </tr>
    
    </table>
</form>
<script language="javascript">
var php_self = "{PHP_SELF}";
var member_class_json = {$member_class_json};
var global_orderf_address_pid = "{$memberSettingInfo->agent_province}";
var global_orderf_address_cityid = "{$memberSettingInfo->agent_city}";
var global_orderf_address_disid = "{$memberSettingInfo->agent_district}";
</script>
<script type="text/javascript" src="{STATIC_URL}js/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="{$BASE_V}member.js"></script>



	</div>
</div>
</body>
</html>
<script type="text/javascript" src="{STATIC_URL}js/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="{STATIC_URL}js/jq.date.js"></script>
<script language="javascript">

function checkDelForm(){
	var check = GetCheckboxValue('id_a[]');
	var article_do = $('do').value;

	if( article_do == '0' )
	{
		alert("好像您没有选择任何管理操作吧?:-(");	
		document.getElementById('do').focus();
		return false;		
	}
	if( check == '')
	{
		alert("好像您没有选择任何要操作的评论吧?:-(");	
		return false;
	}
}

var member_class_json = {$member_class_json};
jq = jQuery.noConflict(); 
jq(document).ready(function(){
	jq('#member_type').change(function(){
		var member_class = jq(this).val();
		var member_class_array = member_class_json[member_class];
		
		jq("#member_class").empty();
		jq.each( member_class_array, function(i, n){			
			if ( n == -1 ) {
				jq("#member_class").append("<option value='"+i+"'>"+n+"</option>");   //为Select追加一个Option(下拉项)
			} else {
				jq("#member_class").append("<option value='"+i+"' selected='selected'>"+n+"</option>");   //为Select追加一个Option(下拉项)
			}
		});

	});	
});

//以后jquery中的都用jq代替即可。 
jq(document).ready(function() {
	jq('#forms input#start_date').datepicker({ dateFormat: 'yy-mm-dd', showOn: 'button', buttonImage: '{STATIC_URL}js/calendar.gif', buttonImageOnly: true });
});

jq(document).ready(function() {
	jq('#forms input#end_date').datepicker({ dateFormat: 'yy-mm-dd', showOn: 'button', buttonImage: '{STATIC_URL}js/calendar.gif', buttonImageOnly: true });
});
</script>