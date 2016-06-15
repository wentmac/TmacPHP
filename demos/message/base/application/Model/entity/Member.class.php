<?php

/*
 * Tmac PHP MVC framework
 * $Author: ZhangWentao<zwt007@gmail.com> $
 * $Id: Member.class.php 0 2016-04-19 16:08:05Z zhangwentao $
 */

/**
 * Description of Member.class.php
 *
 * @author Tracy McGrady
 */
class entity_Member_base
{
    public $uid;
    public $username;
    public $password;
    public $mobile;
    public $realname;
    public $nickname;
    public $email;
    public $register_source;
    public $member_type;
    public $member_class;
    public $member_image_id;
    public $reg_time;
    public $salt;
    public $last_login_time;
    public $last_login_ip;
    public $login_fail_count;
    public $agent_uid;
    public $locked_type;
    public $sex;
}