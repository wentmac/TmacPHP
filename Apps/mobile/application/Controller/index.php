<?php

/**
 * 前台 首页 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: index.php 622 2016-11-15 16:23:15Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class indexAction extends service_Controller_mobile
{

    public function index()
    {
        header( 'location:' . MOBILE_URL . 'shop/' . service_Member_base::yph_uid );
        $union = Input::get( 'union', '' )->string();

        $array[ 'union' ] = $union;
        $this->assign( $array );
        $this->V( 'index_2016' );
    }

    public function download()
    {
        $union = Input::get( 'union', '' )->string();

        $array[ 'union' ] = $union;
        $array[ 'download_url' ] = INDEX_URL . 'download/090.apk';
        $array[ 'qq_download_url' ] = 'http://a.app.qq.com/o/simple.jsp?pkgname=cn.wd090.wd&g_f=100';
        $this->assign( $array );
        $this->V( 'index_download' );
    }

    public function mtext()
    {

        exit();
        $sms_type = 2;
        $sms_code = '561354';
        $mobile = '15011340204';
        $message = '您的短信验证码是：' . $sms_code;

        $sms_model = new \base\service\utils\CCPRestSmsSDK();
        $sms_model->setSms_type( $sms_type );
        $sms_model->setSms_code( $sms_code );
        $sms_model->setMobile( $mobile );
        $sms_model->setMessage( $message );

        $sms_model->setTemplateId(199521);
        $sms_model->setReplaceDatas([$sms_code]);
        $sms_res = $sms_model->sendSMS();

        if ( $sms_res == false ) {
            echo $sms_model->getErrorMessage();
            return true;
        }
        echo 'success';
        die;


        /*
          $cache = CacheDriver::getInstance('Memcached');
          $cache->set('key', 'valuerepl2ce11',600);
          $val = $cache->get('key');
          echo $val; //输出结果为“value”
         */
        $this->common_model->checkToken( 22, 'ddd' );
    }

    public function redis()
    {
        $redis = CacheDriver::getInstance( 'Redis', 'default' );
        $redis instanceof CacheRedis;

        $pmag_stock_list_key = 'pmag_stock_sorted_set_list';

        /*
        //测试有序集合删除 大于 小于
        $delete_time = time()-7200;
        $res = $redis->getRedis()->zRemRangeByScore($pmag_stock_list_key,0,$delete_time); 
        var_dump($res);
        $res = $redis->getRedis()->zRange( $pmag_stock_list_key, 0, -1, 'WITHSCORES' );
        echo '<Pre>';
        print_r( $res );
        die;
         * 
         */

        //测试查询有序集合
        //$redis->zRangeByScore('key', 0, 3, array('withscores' => TRUE, 'limit' => array(1, 1)); /* array('val2' => 2) */                
        //查询小于大于开区间  小于等于大于等于（闭区间
        //$res = $redis->getRedis()->zRevRangeByScore( $pmag_stock_list_key, '(1478628933.667949', '-inf', array( 'withscores' => false, 'limit' => array( 0, 1 ) ) );        

        $res = $redis->getRedis()->zRevRangeByScore( $pmag_stock_list_key, '1479220402', '-inf', array( 'withscores' => false, 'limit' => array( 0, 120 ) ) );
        $res = array_reverse( $res );
        $result = array();
        foreach ( $res as $value ) {
            $value_array = explode( '|', $value );
            $result[] = $value_array;

        }
        echo '<pre>';
        print_r( $result );
        exit;
        //测试有序集合


        $score = microtime( true );
        $price = rand( 15.685, 30.685 ) . '|' . $score;

        $redis->getRedis()->zAdd( $pmag_stock_list_key, (string) $score, $price );


        $res = $redis->getRedis()->zRange( $pmag_stock_list_key, 0, -1, 'WITHSCORES' );
        echo '<Pre>';
        print_r( $res );
        die;
        $pmag_stock_rise_key = 'pmag_stock_rise';
        $pmag_stock_fall_key = 'pmag_stock_fall';

        $time = time();
        $key_suffix_last_hour = date( 'Y_m_d_H', $time - 3600 );
        $key_suffix = date( 'Y_m_d_H', $time );

        $rise_count = $redis->get( $pmag_stock_rise_key . '_' . $key_suffix_last_hour ) + $redis->get( $pmag_stock_rise_key . '_' . $key_suffix );
        $fall_count = $redis->get( $pmag_stock_fall_key . '_' . $key_suffix_last_hour ) + $redis->get( $pmag_stock_fall_key . '_' . $key_suffix );


        var_dump( $rise_count );
        var_dump( $fall_count );
        die;
        set_time_limit( 0 );
        $redis = CacheDriver::getInstance( 'Redis', 'default' );
        $redis instanceof CacheRedis;

        for ( $i = 0; $i < 1000; $i++ ) {
            $price = rand( 15.685, 20.685 );
            $redis->getRedis()->set( 'pmag_stock_new', 'PMAG,白银现货,2016-10-31 13:52:50,' . $price . ',17.625,17.7455,17.575' );
            //$redis->getRedis()->lPush('pmag_stock_queue','PMAG,白银现货,2016-10-28 13:52:50,17.684,17.625,17.7455,17.575');
            sleep( 1 );
        }
    }

    private function microtime_float()
    {
        list( $usec, $sec ) = explode( " ", microtime() );
        return ( (float) $usec + (float) $sec );
    }

}
