<?php

/**
 * 前台 首页 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: index.php 179 2014-11-04 14:18:15Z zhangwentao $
 * http://www.t-mac.org；
 */
class indexAction extends service_Controller_www
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {        
        $title = 'helloworld TmacPHP MVC Framework';
        $array[ 'title' ] = $title;
        $this->assign( $array );
        $this->V( 'index' );
    }

    public function download()
    {
        $union = Input::get( 'union', '' )->string();


        $array[ 'union' ] = $union;        
        $this->assign( $array );
        $this->V( 'index_download' );
    }

}
