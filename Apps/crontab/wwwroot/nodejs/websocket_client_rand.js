#!/usr/bin/env node
var WebSocketClient = require('websocket').client;
var client = new WebSocketClient();

var redis = require("redis"),
redis_client = redis.createClient();
redis_client.on("error", function (err) {
    console.log("Error " + err);
});

//temp data start
function pad(number) {
  if ( number < 10 ) {
    return '0' + number;
  }
  return number;
}

setInterval( function() { 	
    var date = new Date();
    var datetime = date.getFullYear()+'-'+pad(date.getMonth()+1)+'-'+pad(date.getDate())+' '+pad(date.getHours())+':'+pad(date.getMinutes())+':'+pad(date.getSeconds());
    
    var rand = Math.round(Math.random()*(156000-101000)+101000);
    var pmag_stock_new = rand / 1000;
    var stock_data = 'FXUSDJPY,'+datetime+','+pmag_stock_new+',18.325,18.7455,18.175,'+date.getTime();
    redis_client.set('pmag_stock_new', stock_data);
    var data_array = stock_data.split(',');
    var now_price = data_array[2];

    //var d = new Date();
    //var score = d.getTime() / 1000;
    var score = date.getTime() / 1000;
    var price = now_price + '|' + score;

    redis_client.zadd('pmag_stock_sorted_set_list', score, price);            
    //$redis->getRedis()->zAdd( $pmag_stock_list_key, $score, $price );
    //队列中推数据
    redis_client.lpush('pmag_stock_list',stock_data);  	
}, 300 );