<?php

/**
 * 接口 Controller父类 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Model.class.php 745 2016-12-13 04:15:59Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace crontab\service;

class Model extends \Model
{
    //protected $common_api_model;

    /**
     * 初始化变量　定义私有变量
     */
    public function __construct()
    {

        parent::__construct();
    }

    static public function output( $message )
    {        
        if ( empty( $message ) ) {
            return false;
        }
        $message .= "\n";
        echo date( 'Y-m-d H:i:s' ) . "\t" . $message;
        ob_flush();        
    }

}
