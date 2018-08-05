<?php

/**
 * WEB后台 Controller父类 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Controller.class.php 588 2016-11-10 10:40:36Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace crontab\service;

abstract class Controller extends \Model
{

    protected $cron_process_check_model;

    /**
     * 初始化变量　定义私有变量
     */
    public function __construct()
    {
        parent::__construct();
        // $this->memberInfo = new stdClass();
        // $this->memberInfo->uid = 1;
        $this->cron_process_check_model = new CronProcessCheck();
    }

    /**
     * 运行此 Worker
     */
    public function checkProccess( $maxInstances = 1 )
    {
        $this->cron_process_check_model->setMaxInstances( $maxInstances );
        $this->cron_process_check_model->bootstrap();
    }

    protected function output( $msg )
    {
        if ( empty( $msg ) ) {
            return false;
        }
        echo date( 'Y-m-d H:i:s' ) . $msg . "\r\n";
        ob_flush();
    }

    public function __destruct()
    {
        $this->cron_process_check_model->terminate();
    }

}
