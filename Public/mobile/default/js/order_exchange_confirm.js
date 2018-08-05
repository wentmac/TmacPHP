$(function() {
    $("#order_request").click(function(){
        location.href = '/member/address.select?address_id='+global_address_id;
    });

    $("#submit_order").click(function() {
    	if($(this).hasClass("isloading")){
    		return false;
    	}
    	var _this=this;
    	$(this).addClass("isloading");
    	$(this).html("提交中...")
        var remark = $("#remark").val();
        var wxID = $("#wxID").val();
                
       
        var dataParam = {
            goods_id: global_goods_id,            
            address_id: global_address_id,
            postscript: remark,
            weixin_id: wxID            
        };
        $.ajax({
            url: mobile_url + 'order/exchange_save',
            type: 'POST',
            dataType: 'json',
            data: dataParam,
            cache:false,
            success: function(data) {
                if (data.success == true) {
                	$(_this).removeClass("isloading");
                    location.href = mobile_url + 'order/success?sn=' + data.data;                    
                } else {
                    M._alert(data.message);
                    if (data.status == -2) {
                        location.href = mobile_url + 'goods/'+global_goods_id+'.html';
                    }
                }
            }
        });
    })
});
