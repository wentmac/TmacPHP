<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Base.class.php 1005 2017-04-03 18:10:43Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace base\service\trade;

use \service_Model_base;
use \dao_factory_base;

class Base extends service_Model_base
{

    /**
     * 交易状态
     * 已下单/成单
     */
    const trade_status_confirm = 1;

    /**
     * 交易状态
     * 已结算
     */
    const trade_status_settle = 2;

    /**
     * 交易类型
     * 交易
     */
    const trade_type_trade = 0;

    /**
     * 交易类型
     * 兑换
     */
    const trade_type_exchange = 1;

    /**
     * 交易类型
     * 充值 
     */
    const trade_type_recharge = 2;

    /**
     * 交易类型
     * 免费领取
     */
    const trade_type_free_currency = 3;

    /**
     * 交易类型
     * 推广赠送
     * 一个新人发码者得五十
     * 最多得 500
     */
    const trade_type_register_promotion = 4;

    /**
     * 交易产口类型
     * 默认空
     */
    const trade_source_default = 0;

    /**
     * 交易产口类型
     * 伦敦现货白银
     */
    const trade_source_london_silver = 1;

    /**
     * 交易产口类型
     * 好运连连 游戏类型
     */
    const trade_source_good_luck = 2;

    /**
     * 买涨OR买跌 
     * 涨
     */
    const trade_trend_payment_rise = 1;

    /**
     * 买涨OR买跌 
     * 跌
     */
    const trade_trend_payment_fall = -1;

    /**
     * 实际操盘结果 
     * 赢
     */
    const trade_trend_result_win = 1;

    /**
     * 实际操盘结果 
     * 亏
     */
    const trade_trend_result_lose = -1;

    /**
     * 交易类型为【充值】的类型      
     * 人民币=>富豪币
     */
    const trade_class_recharge_by_pay = 1;

    /**
     * 交易类型为【充值】的类型      
     * 购物券=>富豪币
     */
    const trade_class_recharge_by_VoucherMoney = 2;

    /**
     * 交易类型为【兑换】的类型 
     * 富豪币消耗子类型
     * 富豪币=>购物券 
     */
    const trade_class_exchange_FHCurrency_to_Voucher = 1;

    /**
     * 交易类型为【兑换】的类型 
     * 富豪币消耗子类型
     * 富豪币=>红包 
     */
    const trade_class_exchange_FHCurrency_to_LuckyMoney = 2;

    /**
     * 交易类型为【兑换】的类型 
     * 富豪币消耗子类型
     * 富豪币=>商品 
     */
    const trade_class_exchange_FHCurrency_to_Goods = 3;

    /**
     * 交易房间
     * 小房间     
     */
    const trade_room_new = 1;

    /**
     * 交易房间
     * 小房间     
     */
    const trade_room_small = 2;

    /**
     * 交易房间
     * 大房间     
     */
    const trade_room_big = 3;

    /**
     * 交易房间
     * 土豪区 
     */
    const trade_room_rich = 4;

    /**
     * 删除状态     
     */
    const is_delete_no = 0;

    /**
     * 删除状态     
     */
    const is_delete_yes = 1;

    /**
     * 行情看涨的redis key
     */
    const pmag_stock_rise_key = 'pmag_stock_rise';

    /**
     * 行情看跌的redis key
     */
    const pmag_stock_fall_key = 'pmag_stock_fall';

    /**
     * 实时行情的key
     */
    const pmag_stock_new_key = 'pmag_stock_new';

    /**
     * 行情队列 score 是秒 value是价
     */
    const pmag_stock_sorted_set_list_key = 'pmag_stock_sorted_set_list';

    /**
     * 行情队列左进右出
     */
    const pmag_stock_list_key = 'pmag_stock_list';

    /**
     * 行情看跌的redis key 过期时间
     */
    const pmag_stock_expire_time = 10800;

    /**
     * 推广二维码注册 注册，赠送富豪币的次数
     * 10次
     */
    const register_handsel_fh_currency_limit = 10;

    /**
     * 推广二维码注册 注册，一次 赠送富豪币数量
     * 50个
     */
    const register_handsel_fh_currency = 50;

    static public $match_rate = array(
        'pingmin' => '0.7',
        'tuhao' => '0.8',
        'fuweng' => '0.85'
    );
    static public $trade_trend_payment_map = array(
        self::trade_trend_payment_rise => '买涨',
        self::trade_trend_payment_fall => '买跌'
    );
    static public $trade_room_map = array(
        'new' => self::trade_room_new,
        'small' => self::trade_room_small,
        'big' => self::trade_room_big,
        'rich' => self::trade_room_rich
    );    
    protected $errorMessage;
    protected $pagesize = 20;
    protected $where;

    function setPagesize( $pagesize )
    {
        $this->pagesize = $pagesize;
    }

    function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getTradeInfoById( $trade_id )
    {
        $dao = dao_factory_base::getTradeDao();
        $dao->setPk( $trade_id );
        return $dao->getInfoByPk();
    }

    /**
     * 取买家的订单列表
     * $this->where;
     * $this->getTradeList();
     */
    public function getTradeList()
    {
        $trade_dao = dao_factory_base::getTradeDao();

        $trade_dao->setWhere( $this->where );
        $count = $trade_dao->getCountByWhere();

        if ( $count === false ) {
            $retmsg = 0;
        } else {
            $retmsg = 1; //业务返回信息
        }
        $pages = $this->P( 'Pages' );
        $pages->setTotal( $count );
        $pages->setPrepage( $this->pagesize );
        $limit = $pages->getSqlLimit();

        $order_info_array = array();
        if ( $count > 0 ) {
            $trade_dao->setLimit( $limit );
            $trade_dao->setOrderby( 'trade_id DESC' );
            $trade_dao->setField( 'trade_id,uid,nickname,trade_status,stock_payment_price,stock_settle_price,trade_type,fh_currency_payment,fh_currency,fh_currency_rate,create_time,settle_time,trade_trend_payment,trade_trend_result,voucher_money,pay_amount' );
            $res = $trade_dao->getListByWhere();

            //$order_config_array = Tmac::config( 'order.seller.order_status', APP_BASE_NAME );            
            foreach ( $res as $value ) {
                $value->date_time = $this->formatCreateTime( $value->create_time );
                if ( $value->trade_trend_result == self::trade_trend_result_win ) {
                    $value->fh_currency = '+' . ($value->fh_currency + $value->fh_currency_payment);
                } else if ( $value->trade_trend_result == self::trade_trend_result_lose ) {
                    $value->fh_currency = $value->fh_currency;
                } else {
                    $value->fh_currency = (int) $value->fh_currency;
                }
                $value->voucher_money = (int) $value->voucher_money > 0 ? '+' . $value->voucher_money : $value->voucher_money;
                $order_info_array[] = $value;
            }
        }
        $retHeader = array(
            'totalput' => $count,
            'totalpg' => intval( ceil( $count / $this->pagesize ) ),
            'pagesize' => $this->pagesize,
            'page' => $pages->getNowPage()
        );
        $return = array(
            'retHeader' => $retHeader,
            'retcode' => 'trade_list',
            'retmsg' => $retmsg,
            'reqdata' => $order_info_array,
        );
        return $return;
    }

    protected function formatCreateTime( $time )
    {
        $return = array(
            'date' => '',
            'time' => date( 'H:i:s', $time )
        );
        $today_time = strtotime( date( 'Y-m-d', $this->now ) );
        if ( $time >= $today_time ) {
            $return[ 'date' ] = '今日';
        } else {
            $return[ 'date' ] = date( 'm/d', $time );
        }
        return $return;
    }

    /**
     * 取实时的白银报价
     */
    protected function getSliverPrice()
    {
        $redis = \CacheDriver::getInstance( 'Redis', 'default' );
        $redis instanceof \CacheRedis;
        $now_price = $redis->get( self::pmag_stock_new_key );
        if ( empty( $now_price ) ) {
            return false;
        }
        $now_price_array = explode( ',', $now_price );
        return $now_price_array[ 2 ];
    }

}
