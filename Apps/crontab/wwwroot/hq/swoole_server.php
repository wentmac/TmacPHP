<?php

error_reporting( E_PARSE | E_ERROR );
//$server = new swoole_websocket_server("0.0.0.0", 9501);
$server = new swoole_websocket_server( "0.0.0.0", 9502, SWOOLE_BASE );

$redis = new Redis();
$redis->pconnect( '127.0.0.1', 6379 );

//$server->addlistener('0.0.0.0', 9502, SWOOLE_SOCK_UDP);
//$server->set(['worker_num' => 4,
//    'task_worker_num' => 4,
//]);
function user_handshake( swoole_http_request $request, swoole_http_response $response )
{
    //自定定握手规则，没有设置则用系统内置的（只支持version:13的）
    if ( !isset( $request->header[ 'sec-websocket-key' ] ) ) {
        //'Bad protocol implementation: it is not RFC6455.'
        $response->end();
        return false;
    }
    if ( 0 === preg_match( '#^[+/0-9A-Za-z]{21}[AQgw]==$#', $request->header[ 'sec-websocket-key' ] ) || 16 !== strlen( base64_decode( $request->header[ 'sec-websocket-key' ] ) )
    ) {
        //Header Sec-WebSocket-Key is illegal;
        $response->end();
        return false;
    }
    $key = base64_encode( sha1( $request->header[ 'sec-websocket-key' ]
                    . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true ) );
    $headers = array(
        'Upgrade' => 'websocket',
        'Connection' => 'Upgrade',
        'Sec-WebSocket-Accept' => $key,
        'Sec-WebSocket-Version' => '13',
        'KeepAlive' => 'off',
    );
    foreach ( $headers as $key => $val ) {
        $response->header( $key, $val );
    }
    $response->status( 101 );
    $response->end();
    global $server;
    $fd = $request->fd;
    $server->defer( function () use ($fd, $server) {
        $server->push( $fd, "hello, welcome\n" );
    } );
    return true;
}

//$server->on( 'handshake', 'user_handshake' );
$server->on( 'open', function (swoole_websocket_server $_server, swoole_http_request $request) use ($redis) {
    echo "server#{$_server->worker_pid}: handshake success with fd#{$request->fd}\n";
    //var_dump( $_server->exist( $request->fd ), $_server->getClientInfo( $request->fd ) );
//    var_dump($request);


    $fd = $request->fd;
    $last_pmag = '';
    $_server->tick( 100, function($id) use ($fd, $_server, $redis, &$last_pmag) {
        //$len = $redis->lLen( 'pmag_stock_queue' );
        $pmag_stock_new = $redis->get( 'pmag_stock_new' );
        if ( empty( $last_pmag ) || $last_pmag <> $pmag_stock_new ) {
            //取行情趋势
            $trade_trend = getTradeTrend( $redis );
            //今天新手区剩余红包数量
            $lucky_money_new_room_overage_count = $redis->get( 'lucky_money_new_room_overage_count' );
            //今天小房间剩余红包数量
            $lucky_money_small_room_overage_count = $redis->get( 'lucky_money_small_room_overage_count' );
            //今天大房间剩余红包数量
            $lucky_money_big_room_overage_count = $redis->get( 'lucky_money_big_room_overage_count' );
            //今天土豪房间剩余红包数量
            $lucky_money_rich_room_overage_count = $redis->get( 'lucky_money_rich_room_overage_count' );

            $last_pmag = $pmag_stock_new;
            $_send = 'client:' . $fd . ',' . $last_pmag . ',' . $trade_trend . ',' . $lucky_money_new_room_overage_count . ',' . $lucky_money_small_room_overage_count . ',' . $lucky_money_big_room_overage_count . ',' . $lucky_money_rich_room_overage_count;
            $ret = $_server->push( $fd, $_send );
            if ( !$ret ) {
                var_dump( $id );
                var_dump( $_server->clearTimer( $id ) );
            }
        }
    } );
} );
$server->on( 'message', function (swoole_websocket_server $_server, $frame) use ($redis) {
    //var_dump( $frame->data );
    //echo "received " . strlen( $frame->data ) . " bytes\n";
    if ( $frame->data == "close" ) {
        $_server->close( $frame->fd );
    } elseif ( $frame->data == "task" ) {
        $_server->task( ['go' => 'die' ] );
    } elseif ( substr( $frame->data, 0, 12 ) == "pmag_k_line_" ) {
        $key_name = $frame->data;
        if ( $key_name == 'pmag_k_line_0' ) {
            $k_data_array = getHistoryTradeList( $redis );
            $prefix = 'line_data';
        } else {
            $llen = $redis->zCard( $key_name );
            if ( $llen ) {
                //其中成员的位置按分数值递减(从大到小)来排列。
                $score_data_array = $redis->zRevRange( $key_name, 0, 30 );
                //取hash中的值
                $value_data_array = $redis->hMGet('pmag_k_line_map', $score_data_array); /* returns array('field1' => 'value1', 'field2' => 'value2') */
                $k_data_array = array_values($value_data_array);
            }
            $prefix = 'k_data';
        }

        $_send = $prefix . ':' . $frame->fd . '==' . json_encode( array_reverse( $k_data_array ) );
        $_server->push( $frame->fd, $_send );
    } else {
        //echo "receive from {$frame->fd}:{$frame->data}, opcode:{$frame->opcode}, finish:{$frame->finish}\n";
        /*
          for ($i = 0; $i < 1; $i++)
          {
          $_send = str_repeat('B', rand(100, 800));
          $_server->push($frame->fd, $_send);
          // echo "#$i\tserver sent " . strlen($_send) . " byte \n";
          }
         */
        /*
          $redis = new Redis();
          $redis->pconnect( '127.0.0.1', 6379 );

          $fd = $frame->fd;
          $last_pmag = '';
          $_server->tick( 100, function($id) use ($fd, $_server,$redis,&$last_pmag) {
          //$len = $redis->lLen( 'pmag_stock_queue' );
          $pmag_stock_new = $redis->get( 'pmag_stock_new' );
          var_dump($last_pmag);
          var_dump($pmag_stock_new);
          if ( empty($last_pmag) || $last_pmag<>$pmag_stock_new ) {
          $last_pmag = $pmag_stock_new;
          $_send = 'client:' . $fd . ','.$last_pmag;
          $ret = $_server->push( $fd, $_send );
          if ( !$ret ) {
          var_dump( $id );
          var_dump( $_server->clearTimer( $id ) );
          }
          }
          } );
         * 
         */
    }
} );
$server->on( 'close', function ($_server, $fd) {
    echo "client {$fd} closed\n";
} );
$server->on( 'task', function ($_server, $worker_id, $task_id, $data) {
    var_dump( $worker_id, $task_id, $data );
    return "hello world\n";
} );
$server->on( 'finish', function ($_server, $task_id, $result) {
    var_dump( $task_id, $result );
} );
$server->on( 'packet', function ($_server, $data, $client) {
    echo "#" . posix_getpid() . "\tPacket {$data}\n";
    var_dump( $client );
} );
$server->on( 'request', function (swoole_http_request $request, swoole_http_response $response) {
    $response->end( <<<HTML
    <h1>Swoole WebSocket Server</h1>
    <script>
var wsServer = 'ws://127.0.0.1:9501';
var websocket = new WebSocket(wsServer);
websocket.onopen = function (evt) {
	console.log("Connected to WebSocket server.");
};
websocket.onclose = function (evt) {
	console.log("Disconnected");
};
websocket.onmessage = function (evt) {
	console.log('Retrieved data from server: ' + evt.data);
};
websocket.onerror = function (evt, e) {
	console.log('Error occured: ' + evt.data);
};
</script>
HTML
    );
} );
$server->start();

/**
 * 取交易行情的趋势
 */
function getTradeTrend( $redis )
{
    $pmag_stock_rise_key = 'pmag_stock_rise';
    $pmag_stock_fall_key = 'pmag_stock_fall';

    $time = time();
    $key_suffix_last_hour = date( 'Y_m_d_H', $time - 3600 );
    $key_suffix = date( 'Y_m_d_H', $time );

    $rise_count = $redis->get( $pmag_stock_rise_key . '_' . $key_suffix_last_hour ) + $redis->get( $pmag_stock_rise_key . '_' . $key_suffix );
    $fall_count = $redis->get( $pmag_stock_fall_key . '_' . $key_suffix_last_hour ) + $redis->get( $pmag_stock_fall_key . '_' . $key_suffix );

    $count = $rise_count + $fall_count;
    $trade_trend = round( $rise_count / $count, 4 ) * 100;
    return empty( $trade_trend ) ? 50 : $trade_trend;
}

/**
 * 取历史数据报表
 * @return array
 */
function getHistoryTradeList( $redis )
{
    $time = time();
    $result_array = array();
    $settle_time = $time - 1900; //半小时前的        
    $stock_settle_price = $redis->zRevRangeByScore( 'pmag_stock_sorted_set_list', '+inf', $settle_time, array( 'withscores' => false, 'limit' => array( 0, 3600 ) ) );
    if ( empty( $stock_settle_price ) ) {
        return $result_array;
    }
    //$stock_settle_price = array_reverse( $stock_settle_price );
    $price_array = array();
    foreach ( $stock_settle_price as $value ) {
        $value_array = explode( '|', $value );
        $now_time = floor( $value_array[ 1 ] );
        $time_seconds = (int) date( 's', $now_time );
        $value_array[ 1 ] = $now_time - $time_seconds;
        $value_array[ 2 ] = date( 'H:i:s', $value_array[ 1 ] );
        $value_array[ 3 ] = date( 'H:i:s', $time );
        //$value_array[4] = date('i:s',$settle_time);            

        $key = date( 'H_i', $value_array[ 1 ] );
        if ( empty( $price_array[ $key ] ) ) {
            $price_array[ $key ] = $key;
            $result_array[] = $value_array;
            //echo $key."<br>";
        }
    }
    return $result_array;
}
