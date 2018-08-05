/**
 * Created by zhang on 2017/3/23.
 */

define([
    'zepto',
    'vue',
    'weui'
], function ($, Vue, weui) {
    var tradeFactory = {};

    var verify_radio_data = {
        checkedNames: []
    }

    var open_time = {
        count_down_time: 0
    }

    var _init = function () {

        _vue_bind();
        //调用绑定事件
        _check_choose();

    };


    var _result = function () {
        var survey_result = new Vue({
            el: '#div_add_cart',
            data: function () {
                return {
                    goods_category_array:[],
                    goods_array:[],
                    item_id_array:[]
                }
            },
            beforeCreate: function() {
                label_id = 1;
                $.ajax({
                    type: "POST",
                    url: mobile_url + php_self + '?m=survey.getSurveyGoodsArray&label_id='+label_id,
                    dataType: "json",
                    data: {},
                    cache: false,
                    success: function (data) {
                        if (data.success == true) {
                            survey_result.goods_category_array = data.data.goods_cat_array;
                            survey_result.goods_array = data.data.return_array;
                            survey_result.item_id_array = data.data.item_id_array;
                            //console.log(survey_result.goods_category_array);
                        } else {
                            weui.alert(data.message);
                        }
                    }
                });
            },
            // 在 `methods` 对象中定义方法
            methods: {
                goods_num_plus: function (id) {
                    //console.log(image_name);
                    //console.log('-----------');
                    this.item_id_array[id] = this.item_id_array[id]+1;

                },
                goods_num_less: function (id) {
                    //console.log(image_name);
                    //console.log('-----------');
                    //goods_num=goods_num-1 && goods_num>=1;
                    if (this.item_id_array[id]>=1){
                        this.item_id_array[id] = this.item_id_array[id]-1;
                    }
                }
            }
        });


        $('#add_cart_button').on('click', function (e) {
            e.preventDefault();

            var dataParam = [];
            $.each(survey_result.item_id_array, function(item_id, item_num) {
                dataParam.push({'item_id':item_id,'item_num':item_num});
            });

            //ajax提交回答
            $.ajax({
                type: "POST",
                url: mobile_url + 'order/cart_batch_save',
                dataType: "json",
                data: JSON.stringify(dataParam),
                cache: false,
                success: function (data) {
                    if (data.success == true) {
                        //跳转下一题
                        var next_href = mobile_url + 'order/cart';
                        window.location.href = next_href;
                    } else {
                        weui.alert(data.message);
                    }
                }
            });
        });
    }

    var _vue_bind = function () {

        new Vue({
            el: '#div_verify',
            data: verify_radio_data,
            // 在 `methods` 对象中定义方法
            methods: {
                choose_nothing: function (image_name, nothing_value, current_value) {
                    //console.log(image_name);
                    //console.log('-----------');

                    if (image_name == 'nothing') { //选中“没有以上情况的选项时” 清空所有的其他选项
                        verify_radio_data.checkedNames = [];
                    } else {
                        var index = $.inArray(nothing_value, verify_radio_data.checkedNames);
                        if (index >= 0) { //选中其他的选项，把没有以上情况的选项取消
                            verify_radio_data.checkedNames.splice(index, 1);
                        }
                    }
                    //console.log(current_value);
                    //console.log(verify_radio_data.checkedNames);
                    if (step_id == 8) {// 对寒热的感觉 step_id=8   bc互斥 ad互斥
                        if (current_value == 2) {
                            var index = $.inArray('3', verify_radio_data.checkedNames);
                            index >= 0 && verify_radio_data.checkedNames.splice(index, 1)
                        }

                        if (current_value == 3) {
                            var index = $.inArray('2', verify_radio_data.checkedNames);
                            index >= 0 && verify_radio_data.checkedNames.splice(index, 1)
                        }

                        if (current_value == 1) {
                            var index = $.inArray('4', verify_radio_data.checkedNames);
                            index >= 0 && verify_radio_data.checkedNames.splice(index, 1)
                        }

                        if (current_value == 4) {
                            var index = $.inArray('1', verify_radio_data.checkedNames);
                            index >= 0 && verify_radio_data.checkedNames.splice(index, 1)
                        }
                    } else if (step_id == 6) {// 一年内感冒情况 step_id=6   ab互斥
                        if (current_value == 1) {
                            var index = $.inArray('2', verify_radio_data.checkedNames);
                            index >= 0 && verify_radio_data.checkedNames.splice(index, 1)
                        }
                        if (current_value == 2) {
                            var index = $.inArray('1', verify_radio_data.checkedNames);
                            index >= 0 && verify_radio_data.checkedNames.splice(index, 1)
                        }

                    } else if (step_id == 5) {// 精神面貌 step_id=5   ad互斥
                        if (current_value == 1) {
                            var index = $.inArray('4', verify_radio_data.checkedNames);
                            index >= 0 && verify_radio_data.checkedNames.splice(index, 1)
                        }
                        if (current_value == 4) {
                            var index = $.inArray('1', verify_radio_data.checkedNames);
                            index >= 0 && verify_radio_data.checkedNames.splice(index, 1)
                        }

                    }
                }
            }
        });



        /**
         new Vue({
            el: '#game_result_open',
            data: game_result_open
        });
         new Vue({
            el: '#top_open_result',
            data: top_open_result
        });
         */


    }

    /**
     * 检测是否选择单选radio
     */
    var _check_choose = function () {
        $('#next_button').on('click', function (e) {
            e.preventDefault();
            //console.log(verify_radio_data.checkedNames.length);
            if (verify_radio_data.checkedNames.length == 0) { //如果是空选择
                weui.alert('亲，请您先做出选择');
                return false;
            }

            var dataParam = {
                id: step_id,
                survey_result: verify_radio_data.checkedNames
            };
            var next_href = $(this).attr('href');
            //ajax提交回答
            $.ajax({
                type: "POST",
                url: mobile_url + php_self + '?m=survey.save',
                dataType: "json",
                data: dataParam,
                cache: false,
                success: function (data) {
                    if (data.success == true) {
                        //跳转下一题
                        var next_href = mobile_url + 'survey/' + data.data.next_step_id;
                        window.location.href = next_href;
                    } else {
                        weui.alert(data.message);
                    }
                }
            });

            return true;
        });

        $('#confirm_button').on('click', function (e) {
            e.preventDefault();
            //console.log(verify_radio_data.checkedNames.length);
            if (verify_radio_data.checkedNames.length == 0) { //如果是空选择
                weui.alert('亲，请您先做出选择');
                return false;
            }

            var dataParam = {
                id: step_id,
                survey_result: verify_radio_data.checkedNames
            };
            var next_href = $(this).attr('href');
            //ajax提交回答
            $.ajax({
                type: "POST",
                url: mobile_url + php_self + '?m=survey.confirm_save',
                dataType: "json",
                data: dataParam,
                cache: false,
                success: function (data) {
                    if (data.success == true) {
                        //跳转下一题
                        //var next_href = mobile_url+'survey/'+data.data.next_step_id;
                        window.location.href = next_href;
                    } else {
                        weui.alert(data.message);
                    }
                }
            });

            return true;
        });

        $('#completed_button').on('click', function (e) {
            e.preventDefault();

            var dataParam = {};
            var next_href = $(this).attr('href');
            //ajax提交回答
            $.ajax({
                type: "POST",
                url: mobile_url + php_self + '?m=survey.completed',
                dataType: "json",
                data: dataParam,
                cache: false,
                success: function (data) {
                    if (data.success == true) {
                        //跳转下一题
                        //var next_href = mobile_url+'survey/'+data.data.next_step_id;
                        window.location.href = next_href;
                    } else {
                        weui.alert(data.message);
                    }
                }
            });

            return true;
        });
    }


    tradeFactory.init = _init;
    tradeFactory.result = _result;
    tradeFactory.open_time = open_time;

    return tradeFactory;
});
