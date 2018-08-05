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
    <h2>订单详情</h2>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="t_list">
      <tbody>
      <tr>
		<td class="td_right" width="150">订单相关编号</td>
		<td class="td_left_text" colspan="2">
			订单编号：{$order_info->order_sn}<br>
			下单帐号：{$order_info->mobile}<br>
			订单类型：{$order_info->demo_order}<br>
			订单状态：<font color="red">{$order_info->order_status_text}</font><br>
		</td>
      </tr>
	  
      <tr>
		<td class="td_right" width="150"></td>
		<td class="td_left_text refund_td" colspan="2" id="refund_td">
		<!--{if $order_info->have_return_service == 0 && $order_info->refund_status > 0}-->
			<!--{if $order_info->refund_status == 1}-->
				<a data-id="{$order_info->order_refund_id}" style="color:red; text-decoration: underline;" class="cursor">退款中，点击查看退款详情</a>
			<!--{else}-->
				<a data-id="{$order_info->order_refund_id}" style="color:red; text-decoration: underline;" class="cursor">有退款，点击查看退款详情</a>
			<!--{/if}-->
		<!--{/if}-->		
		
		<!--省去 供应商的 [发货]【分销商】[联系供应商] 的的权限  --下面是buyer的权限-->
		</td>
      </tr>	  

      <tr>
          <td class="td_right">收货人信息</td>
          <td class="td_left_text">
			姓名：{$order_info->consignee}<br>
			手机：{$order_info->mobile}<br>
			地址：{$order_info->full_address}<br>
			备注：{$order_info->consignee}			
		  </td>
      </tr>
      <tr>
          <td class="td_right">商品信息</td>
          <td class="td_left_text">
			<!--{loop $order_info->order_goods_array $v}-->
			<div>				
				<p class="o_li" style="height: 110px; line-height: 30px;">					
					<img src="{$v->goods_image_url}" style="float: left;">
					
					<a href="{MOBILE_URL}item/{$v->item_id}.html" target="_blank">&nbsp;{$v->item_name}</a>
					<a>&nbsp;{$v->goods_sku_name}</a>
					<br> &nbsp;&nbsp;价格：￥{$v->item_price}*{$v->item_number}					
					<br><a class="b_txt"></a>									
					<input class="hid_status_all" type="hidden" data_order_goods_id="$v->order_goods_id"  data_service_status="{$v->service_status}" data_service_status_text="{$v->service_status_text}" data_order_refund_id="{$v->order_refund_id}" data_return_service_status="{$v->return_service_status}"  />					
				</p>
				<hr>
			</div>
			<!--{/loop}-->			
			<br> 商品总数：{$order_info->order_item_count}								 
			<!--{if !empty($order_info->coupon_code)}-->
			<br> 订单总价：￥${echo $order_info->order_amount+$order_info->coupon_money;}
			<br> 实际付款：￥{$order_info->order_amount}
			<br> <font color="red">代金券付款：￥{$order_info->coupon_money}</font>
			<br> <font color="red">代金券：{$order_info->coupon_code}</font>
			<!--{else}-->
			<br> 订单总价：￥{$order_info->order_amount}
			<!--{/if}-->
			<br> 佣金：￥{$order_info->commission_fee}
			<br> 系统佣金：￥{$order_info->commission_fee_rank}
			<br> 运费：￥{$order_info->shipping_fee}
		  </td>
      </tr>
	  
      <tr>
          <td class="td_right">订单信息</td>
          <td class="td_left_text">
		  订单编号：{$order_info->order_sn}<br>
		  下单时间：{$order_info->create_time}<br>
		  付款时间：{$order_info->pay_time}<br> 
		  发货时间：{$order_info->shipping_time}<br> 
		  确认收货：{$order_info->confirm_time}<br>
		  </td>
      </tr>
	  
      <tr>
          <td class="td_right">买/代/供</td>
          <td class="td_left_text">
		  买家用户：<a href="{PHP_SELF}?m=order&order_list_type=1&uid={$order_info->uid}" target="_blank" title="查看买家<{$order_info->consignee}/{$order_info->mobile}>所有的订单" class="a_underline">{$order_info->consignee}/{$order_info->mobile}</a><br>
		  分销商：<a href="{MOBILE_URL}manage.php?m=order.detail&order_id={$order_info->order_id}&other_uid={$order_info->item_uid}"  target="_blank" title="在分销商<{$order_info->shop_name}>的管理中心中查看此订单" class="a_underline">{$order_info->shop_name}</a>/{$order_info->item_mobile}<br>
		  供应商：<a href="{MOBILE_URL}manage.php?m=order.detail&order_id={$order_info->order_id}&other_uid={$order_info->goods_uid}"  target="_blank" title="在供应商<{$order_info->supplier_mobile}>的管理中心中查看此订单" class="a_underline">{$order_info->supplier_mobile}</a>
		  </td>
      </tr>	  
      
      <tr>
          <td class="td_right">物流信息</td>
          <td class="td_left_text" colspan="2">
			物流公司：<a id="a_express_name" data-id="{$order_info->express_id}">{$order_info->express_name}</a><br>
			物流单号：<a id="a_express_no" data-no="{$order_info->express_no}">{$order_info->express_no}</a><br>
			物流跟踪：<a id="a_express" style="color:red; text-decoration: underline;" class="am-icon-paper-plane-o">点击查看</a>
			
			<div class="am-g">
				<ul id="div_express" class="am-u-sm-6">

				</ul>
			</div>
		  </td>		  		  
      </tr>	  

      <tr>
          <td class="td_right">买家操作<font color=red>（请一定谨慎操作不要误操作了正常用户的订单！！！）</font></td>
          <td class="td_left_text" colspan="2">
			<!--{if $order_info->order_status == 1}-->
			<a href="{MOBILE_URL}order/payment?sn={$order_info->order_sn}" target="_blank" class="a_underline">付款</a>|<a class="cursor a_underline" target="_blank" id="order_cancel">取消订单</a>
			<!--{elseif $order_info->order_status == 2}-->
			<a class="cursor a_underline" id="order_refund">退款</a>
			<!--{elseif $order_info->order_status == 3}-->
			<a class="cursor a_underline" id="order_confirm" >确认收货</a>
				<!--{if $order_info->extend_confirm_deadline_time_status}-->
				|<a class="cursor a_underline" id="order_extend">延长收货</a>
				<!--{/if}-->
			<!--{/if}-->			
		  </td>
      </tr>	  	  
	     
    </table>
	</div>
</div>
</body>
</html>
<script type="text/javascript" src="{STATIC_URL}js/jquery/1.7.2/jquery.min.js"></script>
<script language="javascript">
var mobile_url = '{MOBILE_URL}';
var php_self = '{PHP_SELF}';
var global_order_sn = '{$order_info->order_sn}';
jq = jQuery.noConflict(); 


jq(document).ready(function(){	
	for (var i = 0; i < jq(".o_li").length; i++) {
		var status_all = jq(".hid_status_all").eq(i);		
		if (status_all.attr("data_return_service_status") == "1") {
			jq(".b_txt").eq(i).attr("href", php_self + "?m=order.refund&sn=" + global_order_sn + "&order_goods_id=" + status_all.attr("data_order_goods_id"));
			jq(".b_txt").eq(i).attr("target", '_blank');
			jq(".b_txt").eq(i).addClass('a_underline');
			jq(".b_txt").eq(i).html("申请售后");
			jq(".b_txt").eq(i).show();
		}		
		if (parseInt(status_all.attr("data_service_status")) > 0) {			
			jq(".b_txt").eq(i).attr("data_order_refund_id", status_all.attr("data_order_refund_id"));
			jq(".b_txt").eq(i).html(status_all.attr("data_service_status_text"));
			jq(".b_txt").eq(i).addClass("a_txt");
			jq(".b_txt").eq(i).addClass("order_goods_refund");
			jq(".b_txt").eq(i).show();
		}
	}

	jq('#order_cancel').click(function(){
		var cm = confirm("确认取消订单？");
		if ( !cm ) {
			return false;
		}			
		jq.ajax({
			type: "post",
			url: php_self + '?m=order.cancel',
			data: {
				sn: global_order_sn
			},
			cache: false,
			success: function(data) {
				if ( data.success == true ) {
					alert('取消订单成功');
					window.location.reload(true);
				} else {
					alert(data.message);
				}
			},
			error: function(data) {
				alert("系统繁忙请稍后再试...");
			}
		});		
	});
	
	jq('#order_refund').click(function(){
		var cm = confirm("确认执行退款操作？");
		if ( !cm ) {
			return false;			
		}		
		var title = '退款';
		openWindow ( php_self+'?m=order.refund&sn='+global_order_sn, title, 500, 500 );
	});
	
	jq('#order_confirm').click(function(){
		var cm = confirm("确认已经收到货了？");
		if ( !cm ) {
			return false;
		}
		jq.ajax({
			type: "post",
			url: php_self + '?m=order.confirm',
			data: {
				sn: global_order_sn
			},
			cache: false,
			success: function(data) {
				if ( data.success == true ) {
					alert('确认收货成功');
					window.location.reload(true);
				} else {
					alert(data.message);
				}
			},
			error: function(data) {
				alert("系统繁忙请稍后再试...");
			}
		});		
	});

	jq('#order_extend').click(function(){
		var cm = confirm("确认执行延长收货时间？");
		if ( !cm ) {
			return false;
		}
		jq.ajax({
			type: "post",
			url: php_self + '?m=order.extend_confirm',
			data: {
				sn: global_order_sn
			},
			cache: false,
			success: function(data) {
				if ( data.success == true ) {
					alert('延长收货时间成功');
					window.location.reload(true);
				} else {
					alert(data.message);
				}
			},
			error: function(data) {
				alert("系统繁忙请稍后再试...");
			}
		});		
	});
	
	//退款
	jq('#refund_td a').click(function(){
		var order_refund_id = jq(this).attr('data-id');
		openWindow ( php_self+'?m=order.refund_detail&order_refund_id='+order_refund_id, '查看退款详细', 500, 500 );
	});
	jq('.order_goods_refund').click(function(){
		var order_refund_id = jq(this).attr('data_order_refund_id');
		openWindow ( php_self+'?m=order.refund_detail&order_refund_id='+order_refund_id, '查看退款详细', 500, 500 );
	});
	
	//查看物流
	jq('#a_express').click(function(){			
		jq("#a_express").html("正在查询中....");
		jq.ajax({
			type: "get",
			url: php_self + "?m=order.get_express_info",
			data: {
				express_id: jq("#a_express_name").attr("data-id"),
				express_no: jq("#a_express_no").attr("data-no"),
			},
			cache:false,
			success: function(data) {
				if (data.success == true) {
					var info = data.data.express_detail;
					if (info == "") {
						info = "还没有查询到任何信息!";
					}
					jq("#div_express").html(info);					
				} else {
					jq("#a_express").html("查询失败");
				}
			},
			error: function(data) {
				jq("#a_express").html("系统正忙请稍后查询...");
			}


		});	
	});
});  



function openWindow(url,name,iWidth,iHeight)
{
	var url;                                 //转向网页的地址;
	var name;                           //网页名称，可为空;
	var iWidth;                          //弹出窗口的宽度;
	var iHeight;                        //弹出窗口的高度;
	var iTop = (window.screen.availHeight-30-iHeight)/2;       //获得窗口的垂直位置;	
	var iLeft = (window.screen.availWidth-10-iWidth)/2;           //获得窗口的水平位置;	
	window.open(url,name,'height='+iHeight+'px,width='+iWidth+'px,top='+iTop+'px,left='+iLeft+'px,toolbar=no,menubar=no,scrollbars=auto,resizeable=no,location=no,status=no');	
}
</script>