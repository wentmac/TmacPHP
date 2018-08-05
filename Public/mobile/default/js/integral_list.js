$(function() {
	bill_list.init();
	bill_list.get_bill_list();	
	$(window).scroll(function() { //内容懒加载
		if ($(document).height() <= parseFloat($(window).height()) + parseFloat($(window).scrollTop()) && global_crrent_page < global_total_page) {		
			bill_list.get_bill_list();

		}
	});
});



var global_crrent_page = 0; //当前为第几页面
var global_total_page = 1; //总页数
var bill_list = {
	bill_click: function(status, _this) {
		_status = status;
		bill_list.init();
		bill_list.get_bill_list("");
		$("#tabPlus ul li a").removeClass('cur');
		$('#'+_status).addClass('cur');
	},
	init: function() {
		global_crrent_page = 0; //当前为第几页面
		global_total_page = 1; //总页数
		$('#js_bill_list').html("");
		$("#scroll_loading_txt").show();
	},
	//获取全部订单列表
	get_bill_list: function() {		
		$("#scroll_loading_txt").show();
		global_crrent_page++;
		if (global_crrent_page > global_total_page) {                        			
			$("#scroll_loading_txt").hide();
			return true;
		}
		$.ajax({
			type: "get",
			url: mobile_url + "member/integral.get_list",
			data: {
				page: global_crrent_page				
			},
			cache: false,
			success: function(data) {
				if (data.success == true) {
					if ( data.data.retHeader.totalput == 0 ) {						
						$("#scroll_loading_txt").hide();
						return true;
					}
					global_total_page = data.data.retHeader.totalpg;
					var list = data.data.reqdata;
					var _order_list = "";
					for (var i = 0; i < list.length; i++) {
						_order_list += '<nav class="shopList">';
						_order_list += '	<nav class="probody maxheight">';
						_order_list += '	     <a class="product">';
						_order_list += '	     <div class="flex">';
						_order_list += '	     	<div class="flex-auto p-details">';
						_order_list += '	     		<div class="flex">';
						_order_list += '	     			<div class="flex-auto">';
						_order_list += '	     				<span class="integral_title">【'+list[i].integral_type_text+'】【'+list[i].integral_class_text+'】</span>';
						_order_list += '	     				<span class="integral">'+list[i].integral+'分</span>';
						_order_list += '	     				<span class="integral_time">'+list[i].integral_time+'</span>';
						_order_list += '	     			</div>';							        
						_order_list += '	     		</div>';
						_order_list += '	     	</div>';
						_order_list += '	     </div>';
						_order_list += '	     </a>';
						_order_list += '	</nav>'	
						_order_list += '</nav>';						
					}

				}				
				$("#js_bill_list").append(_order_list);				
				$("#scroll_loading_txt").hide();											
			},
			error: function() {
				$("#scroll_loading_txt").hide();
				alert("系统正忙,请稍后再试...")
			}
		});

	}
};