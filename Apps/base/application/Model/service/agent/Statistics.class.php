<?php

/**
 * 订单售后 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Statistics.class.php 1017 2017-04-10 17:43:25Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace base\service\agent;

use base\service\trade\Base as Trade;
use base\service\Pay;

class Statistics extends \service_Model_base
{

    /**
     * 现金充值
     */
    const statistics_type_recharge_cash = 'recharge_cash';
    /**
     * 购物卷充值
     */
    const statistics_type_recharge_voucher = 'recharge_voucher';
    /**
     * 兑换
     */
    const statistics_type_exchange = 'exchange';
    /**
     * 免费领取
     */
    const statistics_type_free_currency = 'free_currency';
    /**
     * 推广赠送
     */
    const statistics_type_register_promotion = 'register_promotion';
    /**
     * 交易
     */
    const statistics_type_trade = 'trade';

    /**
     * 净充值 =（交易盈亏-领取-赠送）/100-红包
     */
    const statistics_type_net_recharge = 'net_recharge';
    /**
     * 红包
     */
    const statistics_type_lucky_money = 'lucky_money';

    /**
     * 统计类型
     * @var
     */
    protected $type;
    protected $uid;
    protected $field;
    protected $where;
    protected $errorMessage;

    protected $start_date;
    protected $end_date;
    protected $player;
    protected $uid_type;

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }


    function setUid( $uid )
    {
        $this->uid = $uid;
    }

    /**
     * @param mixed $player
     */
    public function setPlayer( $player )
    {
        $this->player = $player;
    }

    /**
     * @param mixed $uid_type
     */
    public function setUidType( $uid_type )
    {
        $this->uid_type = $uid_type;
    }


    /**
     * @param mixed $start_date
     */
    public function setStartDate( $start_date )
    {
        $this->start_date = strtotime( $start_date );
    }

    /**
     * @param mixed $end_date
     */
    public function setEndDate( $end_date )
    {
        $this->end_date = strtotime( $end_date );
    }


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 取类型
     * @param $type
     */
    private function getWhere()
    {

        switch ( $this->type ) {
            case self::statistics_type_recharge_cash: //现金充值
                $this->where = 'trade_type=' . Trade::trade_type_recharge . ' AND trade_class=' . Trade::trade_class_recharge_by_pay;
                $this->field = "FROM_UNIXTIME(create_time, '%Y-%m-%d') AS day,sum(pay_amount) as data_value";
                break;
            case self::statistics_type_recharge_voucher: //购物卷充值
                $this->where = 'trade_type=' . Trade::trade_type_recharge . ' AND trade_class=' . Trade::trade_class_recharge_by_VoucherMoney;
                //统计充值的购物卷
                $this->field = "FROM_UNIXTIME(create_time, '%Y-%m-%d') AS day,sum(voucher_money) as data_value";
                break;
            case self::statistics_type_exchange: //兑换
                $this->where = 'trade_type=' . Trade::trade_type_exchange;
                //统计兑换消耗的富豪币
                $this->field = "FROM_UNIXTIME(create_time, '%Y-%m-%d') AS day,sum(fh_currency) as data_value";
                break;
            case self::statistics_type_free_currency: //免费领取
                $this->where = 'trade_type=' . Trade::trade_type_free_currency;
                //统计兑换免费领取的富豪币
                $this->field = "FROM_UNIXTIME(create_time, '%Y-%m-%d') AS day,sum(fh_currency) as data_value";
                break;
            case self::statistics_type_register_promotion: //推广赠送
                //统计兑换免费领取的富豪币
                $this->field = "FROM_UNIXTIME(create_time, '%Y-%m-%d') AS day,sum(fh_currency) as data_value";
                $this->where = 'trade_type=' . Trade::trade_type_register_promotion;
                break;
            case self::statistics_type_trade: //交易盈亏
                //统计交易盈亏
                $this->field = "FROM_UNIXTIME(create_time, '%Y-%m-%d') AS day,sum(fh_currency) as data_value";
                $this->where = 'trade_type=' . Trade::trade_type_trade;
                break;
            case self::statistics_type_lucky_money://红包
                $this->field = "FROM_UNIXTIME(bill_time, '%Y-%m-%d') AS day,sum(money) as data_value";
                $this->where = 'bill_type=' . \service_Member_base::bill_type_income . ' AND bill_type_class=' . \service_Member_base::bill_type_class_continue_trade_give_LuckyMoney;
                break;
        }
        if ( $this->type == self::statistics_type_lucky_money ) {
            $this->where .= " AND bill_time>={$this->start_date} AND bill_time<={$this->end_date}";
        } else {
            $this->where .= " AND create_time>={$this->start_date} AND create_time<={$this->end_date}";
        }

        if ( $this->player == 'true' && empty( $this->uid_type ) ) {
            $member_level_dao = \dao_factory_base::getMemberLevelDao();
            $agent_uid_where = "agent_uid={$this->uid} AND member_level_type=" . \service_Member_base::member_level_type_recommend . " AND agent_uid_recent={$this->uid}";
            $agent_uid_sql_in = $member_level_dao->setWhere( $agent_uid_where )->setField( 'uid' )->getSqlByWhere();
            $where = " AND (uid IN($agent_uid_sql_in)";
            $where .= " OR uid={$this->uid})";
        } else {
            $where = " AND uid={$this->uid}";
        }
        $this->where .= $where;
        return $this;
    }

    /**
     * 取Trade表中的统计
     */
    protected function getTradeStatistics()
    {
        $dao = \dao_factory_base::getTradeDao();
        $res = $dao->setField( $this->field )->setWhere( $this->where )->setGroupby( 'day' )->getListByWhere();
        $return = [];
        if ( empty( $res ) ) {
            return $return;
        }
        foreach ( $res AS $v ) {
            $return[ $v->day ] = $v->data_value;
        }
        return $return;
        //todo return array_column( $res, 'data_value', 'day' );
    }


    /**
     * 取member_bill表中的统计
     * 红包
     */
    protected function getMemberBillStatistics()
    {
        $dao = \dao_factory_base::getMemberBillDao();
        $res = $dao->setField( $this->field )->setWhere( $this->where )->setGroupby( 'day' )->getListByWhere();
        $return = [];
        if ( empty( $res ) ) {
            return $return;
        }
        foreach ( $res AS $v ) {
            $return[ $v->day ] = $v->data_value;
        }

        return $return;
        //todo return array_column( $res, 'data_value', 'day' );
    }

    /**
     * 取代理商 的统计列表
     * 日期
     * 现金充值      trade表 trade_type=2 AND trade_class=1 人民币=>富豪币
     * 购物卷充值    trade表 trade_type=2 AND trade_class=2 购物券=>富豪币
     * 兑换(币)      trade表  trade_type=1 兑换总额
     * 红包          member_bill表  bill_type=1 AND bill_type_class=7
     * 免费领取      trade表  trade_type=3
     * 赠送          trade表  trade_type=4
     */
    public function getList()
    {
        $time_diff = $this->end_date - $this->start_date;
        if ( $time_diff < 0 || $time_diff > 86400 * 180 ) {
            $this->errorMessage = '时间不能超过6个月';
            return false;
        }

        $this->type = self::statistics_type_recharge_cash;
        $recharge_cash_array = $this->getWhere()->getTradeStatistics();
        $this->type = self::statistics_type_recharge_voucher;
        $recharge_voucher_array = $this->getWhere()->getTradeStatistics();
        $this->type = self::statistics_type_exchange;
        $exchange_array = $this->getWhere()->getTradeStatistics();
        $this->type = self::statistics_type_free_currency;
        $free_currency_array = $this->getWhere()->getTradeStatistics();
        $this->type = self::statistics_type_register_promotion;
        $register_promotion_array = $this->getWhere()->getTradeStatistics();
        $this->type = self::statistics_type_trade;
        $trade_array = $this->getWhere()->getTradeStatistics();
        $this->type = self::statistics_type_lucky_money;
        $lucky_money_array = $this->getWhere()->getMemberBillStatistics();

        $result_array = [];
        for ( $i = $this->end_date; $i >= $this->start_date; $i -= 86400 ) {
            $date = date( 'Y-m-d', $i );

            $result = [
                self::statistics_type_recharge_cash => 0,
                self::statistics_type_recharge_voucher => 0,
                self::statistics_type_exchange => 0,
                self::statistics_type_free_currency => 0,
                self::statistics_type_register_promotion => 0,
                self::statistics_type_trade => 0,
                self::statistics_type_lucky_money => 0
            ];
            if ( !empty( $recharge_cash_array[ $date ] ) ) {
                $result[ self::statistics_type_recharge_cash ] = $recharge_cash_array[ $date ];
            }
            if ( !empty( $recharge_voucher_array[ $date ] ) ) {
                $result[ self::statistics_type_recharge_voucher ] = abs( $recharge_voucher_array[ $date ] );
            }
            if ( !empty( $exchange_array[ $date ] ) ) {
                $result[ self::statistics_type_exchange ] = $exchange_array[ $date ];
            }
            if ( !empty( $free_currency_array[ $date ] ) ) {
                $result[ self::statistics_type_free_currency ] = $free_currency_array[ $date ];
            }
            if ( !empty( $register_promotion_array[ $date ] ) ) {
                $result[ self::statistics_type_register_promotion ] = $register_promotion_array[ $date ];
            }
            if ( !empty( $trade_array[ $date ] ) ) {
                $result[ self::statistics_type_trade ] = $trade_array[ $date ] > 0 ? -$trade_array[ $date ] : abs( $trade_array[ $date ] );
            }
            if ( !empty( $lucky_money_array[ $date ] ) ) {
                $result[ self::statistics_type_lucky_money ] = $lucky_money_array[ $date ];
            }
            //净充值 =（交易盈亏-领取-赠送）/100-红包
            $result[ self::statistics_type_net_recharge ] = ( $result[ self::statistics_type_trade ] - $result[ self::statistics_type_free_currency ] - $result[ self::statistics_type_register_promotion ] ) / Pay::exchange_rate - $result[ self::statistics_type_lucky_money ];
            $result[ 'date' ] = $date;
            $result_array[] = $result;
        }

        $result[ self::statistics_type_recharge_cash ] = $this->multi_array_sum( $result_array, self::statistics_type_recharge_cash );
        $result[ self::statistics_type_recharge_voucher ] = $this->multi_array_sum( $result_array, self::statistics_type_recharge_voucher );
        $result[ self::statistics_type_exchange ] = $this->multi_array_sum( $result_array, self::statistics_type_exchange );
        $result[ self::statistics_type_free_currency ] = $this->multi_array_sum( $result_array, self::statistics_type_free_currency );
        $result[ self::statistics_type_register_promotion ] = $this->multi_array_sum( $result_array, self::statistics_type_register_promotion );
        $result[ self::statistics_type_trade ] = $this->multi_array_sum( $result_array, self::statistics_type_trade );
        $result[ self::statistics_type_lucky_money ] = $this->multi_array_sum( $result_array, self::statistics_type_lucky_money );
        $result[ self::statistics_type_net_recharge ] = $this->multi_array_sum( $result_array, self::statistics_type_net_recharge );
        $result[ 'date' ] = '汇总统计';
        $result_array[] = $result;
        //echo '<pre>';
        //print_r( $result_array );
        return $result_array;
    }

    /**
     * 计算二维数组某个值的合
     * $arr 二维数组
     * $key 需要对某个键进行求和运算
     */
    private function multi_array_sum( $arr, $key )
    {
        if ( $arr ) {
            $sum_no = 0;

            foreach ( $arr as $v ) {
                $sum_no += $v[ $key ];
            }
            return $sum_no;
        } else {
            return 0;
        }
    }
}
