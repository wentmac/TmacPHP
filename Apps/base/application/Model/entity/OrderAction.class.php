<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $ 
 */

/**
 * Description of OrderAction.class.php
 *
 * @author Tracy McGrady
 */
namespace base\entity;

class OrderAction
{
    public $order_action_id;
    public $order_id;
    public $action_uid;
    public $action_username;
    public $order_status;
    public $shipping_status;
    public $pay_status;
    public $refund_status;
    public $action_note;
    public $action_time;
}