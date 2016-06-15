<?php

/*
 * Tmac PHP MVC framework
 * $Author: ZhangWentao<zwt007@gmail.com> $
 * $Id: User.class.php 0 2016-04-22 19:05:14Z zhangwentao $
 */

/**
 * Description of User.class.php
 *
 * @author Tracy McGrady
 */
class entity_User_base
{
    public $uid;
    public $username;
    public $password;
    public $nicename;
    public $email;
    public $url;
    public $reg_ip;
    public $reg_time;
    public $article_count;
    public $login_count;
    public $last_login_time;
    public $last_login_ip;
    public $login_fail_count;
    public $rank;
    public $salt;
    public $locked_login;
    public $is_delete;
}