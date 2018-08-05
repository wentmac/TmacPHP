<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Trend.class.php 1002 2017-04-03 13:40:05Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace crontab\service;

class Trend extends Model
{

    private $redis;

    public function __construct()
    {
        parent::__construct();
        $this->redis = \CacheDriver::getInstance( 'Redis', 'default' );
    }

    /**
     * 处理交易结算
     * 10秒执行一次
     */
    public function batchInsertTrend()
    {

        $redis = $this->redis;
        $redis instanceof \CacheRedis;
        $success = $fail = 0;
        while ( true ) {
            $len = $redis->getRedis()->lLen( \base\service\trade\Base::pmag_stock_list_key );
            if ( empty( $len ) ) {
                if ( $success > 0 || $fail > 0 ) {
                    $this->output( "成功{$success},失败{$fail}" );
                }
                $this->output( '没有待处理的数据，退出' );
                return true;
            }
            $pamg_stock = $redis->getRedis()->rPop( \base\service\trade\Base::pmag_stock_list_key );
            $pamg_stock_array = explode( ',', $pamg_stock );
            if ( count( $pamg_stock_array ) <> 7 ) {
                continue;
            }
            $res = $this->insertTrend( $pamg_stock_array );
            if ( $res ) {
                $success++;
            } else {
                $fail++;
            }
        }
    }

    private function insertTrend( $pamg_stock_array )
    {
        $entity_Trend = new \base\entity\Trend();

        $trend_type_array = \Tmac::config( 'trend.trend.trend_type', APP_BASE_NAME );
        $entity_Trend->trend_price_time = $pamg_stock_array[ 6 ]/1000;
        $entity_Trend->trend_price = $pamg_stock_array[ 2 ];
        $entity_Trend->trend_open_price = $pamg_stock_array[ 3 ];
        $entity_Trend->trend_highest_price = $pamg_stock_array[ 4 ];
        $entity_Trend->trend_lowest_price = $pamg_stock_array[ 5 ];
        $entity_Trend->trend_code = $pamg_stock_array[ 0 ];
        $entity_Trend->trend_type = $trend_type_array[ $entity_Trend->trend_code ];
        $entity_Trend->trend_time = time();


        $dao = \dao_factory_base::getTrendDao();
        return $dao->insert( $entity_Trend );
    }

}
