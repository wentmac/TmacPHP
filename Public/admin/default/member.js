function chkForm()
{
    /**
    if($('title').value == ""){
       alert("请填写标题！");   
       $('title').focus();
       return(false);
    }**/
        
}

$(document).ready(function() {
    $('#member_type').change(function(){
        var member_class = $(this).val();
        var member_class_array = member_class_json[member_class];
                
        $("#member_class").empty();
        $.each( member_class_array, function(i, n){
            if ( n == '' ) {
                $("#member_class").append("<option value='0'>-请选择-</option>");   //为Select追加一个Option(下拉项)
            } else {
                $("#member_class").append("<option value='"+i+"'>"+n+"</option>");   //为Select追加一个Option(下拉项)
            }
        });

        if ( member_class == 2 ) {
            $('#security_deposit_tr').show();
        } else {
            $('#security_deposit_tr').hide();
        }        
        if ( member_class == 5 ) {
            $('#member_type_dfh_agent').show();
            $('#member_type_dfh_agent_uid').show();
        } else {
            $('#member_type_dfh_agent').hide();
            $('#member_type_dfh_agent_uid').hide();
        }        
    });   
    region.init(); //地区
});





    

    //地区选择
var region = {
    init: function() {
        if (global_orderf_address_pid == '') global_orderf_address_pid = 0;
        if (global_orderf_address_cityid == '') global_orderf_address_cityid = 0;
        if (global_orderf_address_disid == '') global_orderf_address_disid = 0;
        this._pid_bind(global_orderf_address_pid);
        this._city_bind(global_orderf_address_pid, global_orderf_address_cityid);
        this._district_bind(global_orderf_address_cityid, global_orderf_address_disid);
        this._register(global_orderf_address_cityid, global_orderf_address_disid);
    },
    _register: function(cityid, disid) {
        var that = this;
        $("#province").on('change', function() {
            var pid = $(this).val();            
            that._city_bind(pid, cityid);
        });
        $("#city").on('change', function() {
            var cid = $(this).val();            
            that._district_bind(cid, disid);
        });
        $("#district").on('change', function() {
            var did = $(this).val();            
        });
    },
    _pid_bind: function(pid) {
        $.ajax({
            url: php_self + '?m=tool.getRegion&id=1',
            type: 'GET',
            cache: false,
            success: function(data) {
                if (!data.success) {
                    alert(data.message);
                } else {
                    var $selected = $("#province");
                    $selected.find('option').remove();
                    $selected.append('<option value="0">省份</option>');
                    var list = data.data;
                    for (var i = 0; i < list.length; i++) {
                        var seled = "";
                        var region_id = list[i].region_id;
                        if (pid == region_id) seled = " selected";
                        $selected.append('<option value=' + list[i].region_id + seled + '>' + list[i].region_name + '</option>');
                    }
                    $selected.trigger('change');
                }
            }
        });
    },
    _city_bind: function(pid, cityid) {
        if (pid > 0) {
            $.ajax({
                url: php_self + '?m=tool.getRegion&id=1',
                type: 'GET',
                data: {
                    id: pid
                },
                cache: false,
                success: function(data) {
                    if (!data.success) {
                        alert(data.message);
                    } else {
                        var $selected = $("#city");
                        $selected.find('option').remove();
                        $selected.append('<option value="0">城市</option>');
                        var list = data.data;
                        for (var i = 0; i < list.length; i++) {
                            var seled = "";
                            var region_id = list[i].region_id;
                            if (cityid == region_id) seled = " selected";
                            $selected.append('<option value=' + list[i].region_id + seled + '>' + list[i].region_name + '</option>');
                        }
                        $selected.trigger('change');
                    }

                }
            });
        } else {
            var $selected = $("#city");
            $selected.find('option').remove();
            $selected.append('<option value="0">城市</option>');
            $selected.trigger('change');
        }
    },
    _district_bind: function(cityid, disid) {
        if (cityid > 0) {
            $.ajax({
                url: php_self + '?m=tool.getRegion&id=1',
                type: 'GET',
                data: {
                    id: cityid
                },
                cache: false,
                success: function(data) {
                    if (!data.success) {
                        alert(data.message);
                    } else {
                        var $selected = $("#district");
                        $selected.find('option').remove();
                        $selected.append('<option value="0">地区</option>');
                        var list = data.data;
                        for (var i = 0; i < list.length; i++) {
                            var seled = "";
                            var region_id = list[i].region_id;
                            if (disid == region_id) seled = " selected";
                            $selected.append('<option value=' + list[i].region_id + seled + '>' + list[i].region_name + '</option>');
                        }
                        $selected.trigger('change');
                    }

                }
            });
        } else {
            var $selected = $("#district");
            $selected.find('option').remove();
            $selected.append('<option value="0">地区</option>');
            $selected.trigger('change');
        }
    }    
}
