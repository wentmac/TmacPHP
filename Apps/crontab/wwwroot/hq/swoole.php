<?php
$server = new swoole_websocket_server("0.0.0.0", 9501);

$server->on('open', function (swoole_websocket_server $server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";
    
    $fd = $request->fd;
    $server->tick( 3000, function($id) use ($fd, $server, $request) {
        //$len = $redis->lLen( 'pmag_stock_queue' );                 
            $last_pmag = '|open tick';
            $_send = 'client:' . $fd . ',' . $last_pmag;
            $ret = $server->push( $fd, $_send );
            if ( !$ret ) {
                var_dump( $id );
                var_dump( $server->clearTimer( $id ) );
            }       
    } );    
});

$server->on('message', function (swoole_websocket_server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $GLOBALS['last_pmag'] = $frame->data;
    $server->push($frame->fd, "this is server{$frame->data}");    
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();