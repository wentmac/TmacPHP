<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Exchange.class.php 1004 2017-04-03 15:01:11Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace base\service;

use base\service\trade\Base as Trade;

class Exchange extends \service_Model_base
{

    /**
     * 最低起兑换的富豪币
     */
    const min_exchange_fh_currency = 3000;

    /**
     * 富豪币兑换购物券汇率
     */
    const exchange_fh_currency_rate_to_voucher = 100;

    protected $uid;
    protected $memberInfo;
    protected $memberSetting;
    protected $goods_id;
    protected $goods_class;
    protected $errorMessage;    
    static protected $goodsClassAction = [
        1 => 'LuckyMoney',
        2 => 'Goods',
        3 => 'Voucher'
    ];

    function setUid( $uid )
    {
        $this->uid = $uid;
    }

    function setGoods_id( $goods_id )
    {
        $this->goods_id = $goods_id;
    }

    function setMemberInfo( $memberInfo )
    {
        $this->memberInfo = $memberInfo;
    }

    function setMemberSetting( $memberSetting )
    {
        $this->memberSetting = $memberSetting;
    }

    function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 减库存
     * @return type
     */
    protected function lessExchangeGoodsStock()
    {
        $entity_Goods = new \base\entity\Goods();
        $entity_Goods->goods_stock = new \TmacDbExpr( 'goods_stock-1' );
        $dao = \dao_factory_base::getGoodsDao();
        return $dao->setPk( $this->goods_id )->updateByPk( $entity_Goods );
    }

    /**
     * 取兑换商品 且判断合法性
     * @return boolean
     */
    public function getExchangeGoods()
    {
        $dao = \dao_factory_base::getGoodsDao();
        $goods_info = $dao->setPk( $this->goods_id )->getInfoByPk();
        if ( $goods_info->goods_type <> \service_Goods_base::goods_type_exchange ) {
            $this->errorMessage = '非兑换类商品';
            return false;
        }
        //判断每天领取限制

        if ( $goods_info->exchange_limit_day > 0 ) {
            $exchange_today_count = $this->getExchangTodayCount();
            if ( $exchange_today_count >= $goods_info->exchange_limit_day ) {
                $this->errorMessage = "此兑换产品每天限额{$goods_info->exchange_limit_day},您今天已经兑换了{$exchange_today_count}了";
                return false;
            }
        }
        //判断富豪币余额
        if ( $this->memberSetting->current_fh_currency < $goods_info->fh_currency_need ) {
            $this->errorMessage = "您的可用富豪币余额是{$this->memberSetting->current_fh_currency},此兑换产品需要富豪币：{$goods_info->fh_currency_need}";
            return false;
        }
        //返回info
        if ( $goods_info->goods_stock <1){
            $this->errorMessage = "库存不够了，不能抽奖了。";
            return false;
        }
        return $goods_info;
    }

    /**
     * 取今天的兑换产品次数
     * $this->goods_id
     */
    private function getExchangTodayCount()
    {
        $today_time = strtotime( date( 'Y-m-d' ) );
        $dao = \dao_factory_base::getTradeDao();
        $where = "uid={$this->memberInfo->uid} AND trade_type=" . Trade::trade_type_exchange . " AND trade_exchange_goods_id={$this->goods_id} AND create_time>={$today_time}";
        return $dao->setWhere( $where )->getCountByWhere();
    }

    /**
     * 工厂创建
     * @param type $source
     * @return type 
     */
    public static function factory( $goods_id )
    {
        $dao = \dao_factory_base::getGoodsDao();
        $goods_info = $dao->setField( 'goods_type,goods_class' )->setPk( $goods_id )->getInfoByPk();
        if ( $goods_info->goods_type <> \service_Goods_base::goods_type_exchange ) {
            //$this->errorMessage = '非兑换类商品';
            return false;
        }
        $className = '\dfh\service\exchange\\' . self::$goodsClassAction[ $goods_info->goods_class ];
        return new $className();
    }
  
}
