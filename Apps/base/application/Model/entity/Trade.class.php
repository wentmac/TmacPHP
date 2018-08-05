<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $ 
 */

/**
 * Description of Trade.class.php
 *
 * @author Tracy McGrady
 */
namespace base\entity;

class Trade
{
    public $trade_id;
    public $uid;
    public $nickname;
    public $trade_status;
    public $trade_type;
    public $trade_class;
    public $trade_exchange_type;
    public $trade_exchange_class;
    public $trade_source;
    public $trade_room;
    public $fh_currency_payment;
    public $fh_currency_rate;
    public $fh_currency;
    public $create_time;
    public $settle_time;
    public $execute_settle_time;
    public $stock_payment_price;
    public $stock_settle_price;
    public $trade_trend_payment;
    public $trade_trend_result;
    public $trade_time_cycle;
    public $trade_note;
    public $voucher_money;
    public $pay_amount;
    public $trade_exchange_relation_id;
    public $trade_exchange_goods_id;
    public $is_delete;
}