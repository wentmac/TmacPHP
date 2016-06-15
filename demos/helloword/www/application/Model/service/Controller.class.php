<?php

/**
 * 接口 Controller父类 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Controller.class.php 604 2014-12-15 03:03:46Z zhangwentao $
 * http://www.t-mac.org；
 */
abstract class service_Controller_www extends service_Controller_base
{

    protected $memberMallInfo;

    /**
     * 初始化变量　定义私有变量
     */
    public function __construct()
    {
        parent::__construct();        
        $referer = isset( $_SERVER [ 'HTTP_REFERER' ] ) ? filter_input( INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_STRING ) : INDEX_URL;                
        $this->assign( 'referer_url', $referer );
    }


    public function no( $title = '' )
    {
        $array[ 'title' ] = $title;
        $this->assign( $array );
        $this->V( '404' );
        exit();
    }

}
