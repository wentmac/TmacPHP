<?php

/**
 * 用户登录注册页面
 * ============================================================================
 * @author  by time 22014-07-07
 * 
 */
use crontab\service\Controller;

class stockAction extends Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->checkLogin();        
    }

    public function index1()
    {
        $ws = new swoole_websocket_server( "0.0.0.0", 9527 );

        $ws->on( 'open', function ($ws, $request) {
            //var_dump( $request->fd, $request->get, $request->server );
            print_r($ws);
            print_r($request);
            ob_flush();
            $ws->push( $request->fd, "hello, welcome\n" );
        } );

        $ws->on( 'message', function ($ws, $frame) {
            echo "Message: {$frame->data}\n";
            $ws->push( $frame->fd, "server: {$frame->data}" );
        } );

        $ws->on( 'close', function ($ws, $fd) {
            echo "client-{$fd} is closed\n";
        } );

        $ws->start();
    }

}
