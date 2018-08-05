<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $ 
 */

/**
 * Description of Cart.class.php
 *
 * @author Tracy McGrady
 */
namespace base\entity;

class Cart
{
    public $cart_id;
    public $uid;
    public $session_id;
    public $item_id;
    public $goods_id;
    public $goods_name;
    public $goods_image_id;
    public $item_total_price;
    public $item_price;
    public $item_number;
    public $outer_code;
    public $goods_sku_id;
    public $goods_sku_name;
    public $goods_sku_json;
    public $goods_uid;
    public $item_uid;
    public $shop_name;
    public $confirm_show;
    public $commission_fee;
    public $commission_fee_rank;
    public $shipping_fee;
    public $goods_member_level;
    public $goods_type;
    public $is_integral;
}