<?php

/*
 * Tmac PHP MVC framework
 * $Author: ZhangWentao<zwt007@gmail.com> $
 * $Id: Category.class.php 0 2016-06-03 18:42:42Z zhangwentao $
 */

/**
 * Description of Category.class.php
 *
 * @author Tracy McGrady
 */
class entity_Category_base
{
    public $cat_id;
    public $cat_pid;
    public $channeltype;
    public $cat_name;
    public $category_keywords;
    public $category_description;
    public $category_nicename;
    public $category_content;
    public $cat_order;
    public $article_count;
    public $urlfile;
    public $nav_show;
    public $attachment_file_id;
    public $attachment_file_name;
    public $is_delete;
}