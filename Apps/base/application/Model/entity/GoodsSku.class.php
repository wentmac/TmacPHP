<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $ 
 */

/**
 * Description of GoodsSku.class.php
 *
 * @author Tracy McGrady
 */
namespace base\entity;

class GoodsSku
{
    public $goods_sku_id;
    public $goods_id;
    public $goods_sku;
    public $goods_sku_json;
    public $price;
    public $stock;
    public $outer_code;
    public $commission_fee;
    public $sales_volume;
    public $create_time;
    public $modify_time;
    public $goods_source;
    public $goods_source_sku_id;
    public $is_delete;
}