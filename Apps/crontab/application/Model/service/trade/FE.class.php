<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Trade.class.php 972 2017-03-16 08:41:15Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace crontab\service\trade;

class FE extends \base\service\trade\FE
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
    public function handelTrade()
    {
        //取出待结算的订单
        $dao = \dao_factory_base::getTradeDao();
        $where = "trade_status=" . parent::trade_status_confirm
            . " AND trade_type=" . parent::trade_type_trade
            . " AND is_delete=0"
            . " AND settle_time<={$this->now}";
        $field = 'trade_id,uid,trade_type,fh_currency_payment,fh_currency_rate,settle_time,trade_trend_result,fh_currency,stock_payment_price,trade_trend_payment';
        $dao->setWhere( $where );
        $count = $dao->getCountByWhere();
        if ( $count == 0 ) {
            return true;
        }
        $dao->setOrderby( 'trade_id ASC' );
        $dao->setField( $field );
        $res = $dao->getListByWhere();

        foreach ( $res as $trade ) {
            //执行结算            
            $this->settleTrade( $trade );
        }
        return $count;
    }

    /**
     * 取最精确的结算价
     */
    private function getSettlePrice( $settle_time )
    {
        /*
          $rand = rand( 18100, 18600 );
          return $pmag_stock_new = $rand / 1000;
         */
        $redis = $this->redis;
        $redis instanceof \CacheRedis;
        $stock_settle_price = $redis->getRedis()->zRevRangeByScore( parent::pmag_stock_sorted_set_list_key, $settle_time, '-inf', array( 'withscores' => false, 'limit' => array( 0, 1 ) ) );
        //Array ( [0] => 20|1478618961.1726 )
        if ( empty( $stock_settle_price ) ) {
            return 0;
        }
        $stock_settle_price_map = explode( '|', $stock_settle_price[ 0 ] );
        $settle_price = $stock_settle_price_map[ 0 ];
        if ( empty( $settle_price ) ) {
            //删除
            // "undefined|1489561261.891"
            $redis->getRedis()->zRem( parent::pmag_stock_sorted_set_list_key, $stock_settle_price );
            return 0;
        }
        return $settle_price;
    }

    /**
     * 结算交易行情
     * @param type $trade
     */
    private function settleTrade( $trade )
    {
        $trade instanceof \base\entity\Trade;
        if ( $trade->trade_trend_result <> 0 ) {
            return true;
        }
        //进行结果对比，行判断赢亏

        $entity_Trade = new \base\entity\Trade();

        $now_payment_price = $this->getSettlePrice( $trade->settle_time );
        if ( $now_payment_price <= 0 ) {
            return false;
        }
        if ( $trade->stock_payment_price == $now_payment_price ) {
            //不赢不亏 最终得到的富豪币数量（正数就是赢的钱|负数就是输的钱）
            $entity_Trade->fh_currency = 0;
            $trade_trend_result = 0;
            $result_text = '';
        } elseif ( $trade->stock_payment_price < $now_payment_price && $trade->trade_trend_payment == parent::trade_trend_payment_rise ) {
            //赢  
            //结算的盈亏币 最终得到的富豪币数量（正数就是赢的钱）
            $entity_Trade->fh_currency = round( $trade->fh_currency_payment * $trade->fh_currency_rate );
            $trade_trend_result = parent::trade_trend_result_win;
            $result_text = '预测对了+' . $entity_Trade->fh_currency;
        } else if ( $trade->stock_payment_price > $now_payment_price && $trade->trade_trend_payment == parent::trade_trend_payment_fall ) {
            //赢  
            //结算的盈亏币 最终得到的富豪币数量（正数就是赢的钱）
            $entity_Trade->fh_currency = round( $trade->fh_currency_payment * $trade->fh_currency_rate );
            $trade_trend_result = parent::trade_trend_result_win;
            $result_text = '预测对了+' . $entity_Trade->fh_currency;
        } else {
            //亏  亏掉押的币 最终得到的富豪币数量（负数就是输掉投资的所有币）
            $entity_Trade->fh_currency = -$trade->fh_currency_payment;
            $trade_trend_result = parent::trade_trend_result_lose;
            $result_text = '预测错了-' . $trade->fh_currency_payment;
        }
        $entity_Trade->execute_settle_time = microtime( true );
        $entity_Trade->stock_settle_price = $now_payment_price;
        $entity_Trade->trade_trend_result = $trade_trend_result;
        $entity_Trade->trade_status = parent::trade_status_settle;
        $trade_trend_payment_text = parent::$trade_trend_payment_map[ $trade->trade_trend_payment ];
        $trade_note = "下单伦敦银交易，投资{$trade->fh_currency_payment}(币)，投资价格{$trade->stock_payment_price},{$trade_trend_payment_text},结算价格{$entity_Trade->stock_settle_price},{$result_text}";
        $entity_Trade->trade_note = $trade_note;
        $entity_Trade->uid = $trade->uid;
        $entity_Trade->fh_currency_payment = $trade->fh_currency_payment;
        //echo $trade_note . "\r\n";
        $dao = \dao_factory_base::getTradeDao();
        $dao->getDb()->startTrans();

        $dao->setWhere( "trade_id={$trade->trade_id} AND trade_status=" . parent::trade_status_confirm );
        $dao->updateByWhere( $entity_Trade );

        $this->updateMemberSettingCurrency( $entity_Trade );
        //if ( $spec_value_map_dao->getDb()->isSuccess() && $spec_value_map_dao->getDb()->getNumRows() > 0 ) {
        if ( $dao->getDb()->isSuccess() && $dao->getDb()->getNumRows() > 0 ) {
            $dao->getDb()->commit();
            return $entity_Trade;
        } else {
            $dao->getDb()->rollback();
            return false;
        }
    }

    /**
     * member_setting 表中的money相关字段更新
     * 只有押对了才更新钱
     * 更新
     * @return type
     */
    private function updateMemberSettingCurrency( \base\entity\Trade $entity_Trade_base )
    {
        if ( $entity_Trade_base->trade_trend_result == parent::trade_trend_result_lose ) {
            return true;
        }
        $member_setting_dao = \dao_factory_base::getMemberSettingDao();
        $entity_MemberSetting_base = new \base\entity\MemberSetting();
        //赢了还钱
        $current_fh_currency = $entity_Trade_base->fh_currency_payment + $entity_Trade_base->fh_currency;
        $entity_MemberSetting_base->current_fh_currency = new \TmacDbExpr( 'current_fh_currency+' . $current_fh_currency );
        //更新卖家的金钱 商品供应商UID
        $member_setting_dao->setPk( $entity_Trade_base->uid );
        $member_setting_dao->updateByPk( $entity_MemberSetting_base );
        return true;
    }

    /**
     * 封闭一个通过trade_id来进行主动结算
     * @param type $trade_id
     */
    public function settleTradeById( $trade_id )
    {
        $dao = \dao_factory_base::getTradeDao();
        $dao->setPk( $trade_id );
        $dao->setField( 'trade_id,uid,trade_status,trade_type,fh_currency_payment,fh_currency_rate,fh_currency,create_time,settle_time,execute_settle_time,stock_payment_price,stock_settle_price,trade_trend_payment,trade_trend_result,trade_time_cycle,is_delete' );
        $trade_info = $dao->getInfoByPk();
        if ( empty( $trade_info ) ) {
            $this->errorMessage = 'trade_id:' . $trade_id . '不存在';
            return false;
        }
        $trade_info instanceof \base\entity\Trade;
        if ( $trade_info->trade_status == parent::trade_status_settle ) {
            $trade_info->settle_time_text = date( 'i:s', $trade_info->settle_time );
            return $trade_info;
        }
        if ( $trade_info->trade_status <> parent::trade_status_confirm ) {
            $this->errorMessage = '当前状态不合法';
            return false;
        }
        if ( $trade_info->trade_type <> parent::trade_type_trade ) {
            $this->errorMessage = '当前类型不合法';
            return false;
        }
        if ( $trade_info->is_delete == 1 ) {
            $this->errorMessage = '不存在';
            return false;
        }
        if ( $trade_info->trade_trend_result <> 0 ) {
            return $trade_info;
        }
        if ( round( $trade_info->settle_time ) > $this->now ) {
            $this->errorMessage = '还没到结算时间啊！';
            return false;
        }
        $settleInfo = $this->settleTrade( $trade_info );
        if ( $settleInfo == false ) {
            $trade_info = $dao->getInfoByPk();
            $trade_info instanceof \base\entity\Trade;
            if ( $trade_info->trade_status == parent::trade_status_settle ) {
                $trade_info->settle_time_text = date( 'i:s', $trade_info->settle_time );
                return $trade_info;
            }
            $this->errorMessage = '结算操作失败，请联系管理员';
            return false;
        }
        unset( $trade_info->trade_status, $trade_info->uid, $trade_info->trade_type, $trade_info->is_delete, $settleInfo->is_delete );
        $trade_info->fh_currency_payment = $settleInfo->fh_currency_payment;
        $trade_info->fh_currency = $settleInfo->fh_currency;
        $trade_info->execute_settle_time = $settleInfo->execute_settle_time;
        $trade_info->stock_settle_price = $settleInfo->stock_settle_price;
        $trade_info->trade_trend_result = $settleInfo->trade_trend_result;
        $trade_info->trade_note = $settleInfo->trade_note;
        $trade_info->settle_time_text = date( 'H:i:s', $trade_info->settle_time );
        return $trade_info;
    }

}
