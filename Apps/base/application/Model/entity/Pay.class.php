<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $ 
 */

/**
 * Description of Pay.class.php
 *
 * @author Tracy McGrady
 */
namespace base\entity;

class Pay
{
    public $pay_id;
    public $uid;
    public $pay_status;
    public $trade_no;
    public $trade_vendor;
    public $pay_amount;
    public $voucher_money;
    public $referer;
    public $create_time;
    public $pay_time;
    public $pay_note;
    public $is_delete;
}