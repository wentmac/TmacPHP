<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $ 
 */

/**
 * Description of Coupon.class.php
 *
 * @author Tracy McGrady
 */
namespace base\entity;

class Coupon
{
    public $coupon_id;
    public $uid;
    public $coupon_code;
    public $coupon_money;
    public $coupon_status;
    public $order_id;
    public $order_sn;
    public $create_time;
    public $use_time;
}