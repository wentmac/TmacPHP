#!/usr/bin/env node
var WebSocketClient = require('websocket').client;
var client = new WebSocketClient();

var redis = require("redis"),
    redis_client = redis.createClient();
redis_client.on("error", function (err) {
    console.log("Error " + err);
});

//每时每刻的时间对应的本时间段数据
var pmag_list_hash_key = 'pmag_k_line_map';

client.on('connectFailed', function (error) {
    console.log('Connect Error: ' + error.toString());
});

client.on('connect', function (connection) {
    console.log('WebSocket Client Connected');
    connection.on('error', function (error) {
        console.log("Connection Error: " + error.toString());
    });
    connection.on('close', function () {
        console.log('echo-protocol Connection Closed');
    });
    connection.on('message', function (message) {
        if (message.type === 'utf8') {
            var stock_data = message.utf8Data;

            var data_array = stock_data.split(',');
            var trend_code = data_array[0];//行情代码
            var now_date = data_array[2];//时间 2016-10-28 13:52:50
            var now_price = data_array[3];//当前价
            var open_price = data_array[4];//开盘价
            var highest_price = data_array[5];//今日最高价
            var lowest_price = data_array[6];//今日最低价                        

            if (now_date) {
                var date = new Date(now_date.replace(/-/g, '/'));
            } else {
                var date = new Date();
            }
            // 有三种方式获取，在后面会讲到三种方式的区别
            var now_time = date.getTime();//时间2016-10-28 13:52:50 转成毫秒时间戳
            //新组数组结构| 行情代码，时间，当前价，今日开盘价，今日最高价，今日最低价，毫秒时间戳
            var pmag_stock_new = trend_code + ',' + now_date + ',' + now_price + ',' + open_price + ',' + highest_price + ',' + lowest_price + ',' + now_time;
            //实时价格更新Redis
            //redis_client.set('pmag_stock_new', pmag_stock_new);

            var score = now_time / 1000;
            var price = now_price + '|' + score;

            //redis_client.zadd('pmag_stock_sorted_set_list', score, price);
            //$redis->getRedis()->zAdd( $pmag_stock_list_key, $score, $price );
            //队列中推数据
            //redis_client.lpush('pmag_stock_list',pmag_stock_new);
            //console.log("Received: '" + message.utf8Data + "'");
            //K线图数据准备 使用redis list
            update_k_data(1, now_time, now_price);
            //update_k_data(5, now_time, now_price);
            //update_k_data(15,now_time,now_price);
            //update_k_data(30,now_time,now_price);
            //update_k_data(60,now_time,now_price);
        }
    });
});

/**
 * 更新K线数据
 */
function update_k_data(time_scale, now_time, now_price) {
    if (!now_price) {
        return;
    }
    var pmag_list_key = 'pmag_k_line_' + time_scale;
    pmag_list_key = pmag_list_key.toString();

    // -Infinity and +Infinity also work
    // zrevrangebyscore var args2 = [ 'myzset', max, min, 'WITHSCORES', 'LIMIT', offset, count ];
    var args = [pmag_list_key, 0, 0, 'WITHSCORES'];
    //其中成员的位置按分数值递减(从大到小)来排列。 取第一个
    redis_client.zrevrange(args, function (err, pmag_k_res) {
        if (pmag_k_res && pmag_k_res.length > 0) {
            var res_field = pmag_k_res[0];  //第一个的value
            var res_score = pmag_k_res[1];  //第一个的score
            var pmag_list_hash_key = 'pmag_k_line_map';

            //开始查hashmap中找值
            redis_client.hget(pmag_list_hash_key, res_field, function (err, res_value) {
                var pmag_k_array = res_value.split(',');
                if (pmag_k_array.length == 0) {
                    createNewKData(pmag_list_key, time_scale, now_price);
                } else {
                    var last_now_time = pmag_k_array[0];
                    var last_open = pmag_k_array[1];
                    var last_high = pmag_k_array[2];
                    var last_low = pmag_k_array[3];
                    var last_close = pmag_k_array[4];

                    var timeDiff = now_time - last_now_time;
                    var period = time_scale * 60 * 1000;

                    if (timeDiff >= period) { //超过时刻，写入新的一时间间隔数据
                        var push_now_time = parseInt(last_now_time) + period;//整点时间 上一次时间+整点区间
                        var value_array = [push_now_time, now_price, now_price, now_price, now_price];
                        //redis_client.lpush(pmag_list_key, value_array.join());
                        //开启事务
                        var multi = redis_client.multi();
                        //写入有序集合中，score和value都是毫秒时间戳。 ZADD key score1 member1 [score2 member2]
                        redis_client.zadd(pmag_list_key, push_now_time, push_now_time);
                        //写入hash中 key_name='pmag_k_line_map' field=毫秒时间戳 value=数据内容  HSET KEY_NAME FIELD VALUE
                        redis_client.hset(pmag_list_hash_key, push_now_time, value_array.join());
                        //提交事务
                        multi.exec(function (err, replies) {
                            if ( err ) {
                                console.log(err); // 101, 2
                            }
                        });
                    } else { //更新上一时间间隔的数据
                        var update_now_time = last_now_time;
                        var update_open = last_open;
                        var update_high = Math.max(last_high, now_price);
                        var update_low = Math.min(last_low, now_price);
                        var update_close = now_price;
                        var value_array = [update_now_time, update_open, update_high, update_low, update_close];
                        //redis_client.lpush(pmag_list_key, value_array.join());
                        //写入hash中 key_name='pmag_k_line_map' field=毫秒时间戳 value=数据内容  HSET KEY_NAME FIELD VALUE
                        redis_client.hset(pmag_list_hash_key, update_now_time, value_array.join());
                    }
                }
            });
        } else {
            createNewKData(pmag_list_key, time_scale, now_price);
        }

    });
}

/**
 * 创建新k线的函数
 */
function createNewKData(pmag_list_key, time_scale, now_price) {
    var myDate = new Date();
    var minutes = myDate.getMinutes();
    var new_minutes = Math.ceil(minutes / time_scale) * time_scale;
    myDate.setMinutes(new_minutes);
    myDate.setSeconds(0);
    myDate.setMilliseconds(0);

    var now_insert_time = myDate.getTime();
    var value_array = [now_insert_time, now_price, now_price, now_price, now_price];

    //开启事务
    var multi = redis_client.multi();
    //写入有序集合中，score和value都是毫秒时间戳。 ZADD key score1 member1 [score2 member2]
    redis_client.zadd(pmag_list_key, now_insert_time, now_insert_time);
    //写入hash中 key_name='pmag_k_line_map' field=毫秒时间戳 value=数据内容  HSET KEY_NAME FIELD VALUE
    redis_client.hset(pmag_list_hash_key, now_insert_time, value_array.join());
    //提交事务
    multi.exec(function (err, replies) {
        if ( err ) {
            console.log(err); // 101, 2
        }
    });
}

//client.connect('ws://192.168.1.135:9501/');
//client.connect('ws://101.200.181.237:40001/YF1615896/WA/PMAG');
//client.connect('ws://hq.iyunfun.com:40001/YF1615896/WA/PMAG');
client.connect('ws://hq.iyunfun.com:40001/YF1615896/WA/FXUSDJPY');

/*
 var args = ['test', 0, 1, 'WITHSCORES'];

 redis_client.zrevrange(args, function (err, pmag_k_res) {
 //console.log(pmag_k_res);
 pmag_k_res.forEach(function (reply, index) {
 console.log("Reply " + index + ": " + reply.toString());
 });
 });
 return;
 */
