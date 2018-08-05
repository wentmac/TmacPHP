<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $ 
 */

/**
 * Description of GoodsCategory.class.php
 *
 * @author Tracy McGrady
 */
namespace base\entity;

class GoodsCategory
{
    public $goods_cat_id;
    public $cat_name;
    public $cat_keywords;
    public $cat_description;
    public $cat_pid;
    public $cat_sort;
    public $goods_count;
    public $is_delete;
    public $is_cloud_product;
}