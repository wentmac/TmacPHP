<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $ 
 */

/**
 * Description of MemberOauth.class.php
 *
 * @author Tracy McGrady
 */
namespace base\entity;

class MemberOauth
{
    public $id;
    public $uid;
    public $oauth_type;
    public $openid;
    public $unionid;
    public $access_token;
    public $expires_in;
    public $refresh_token;
    public $nickname;
    public $avatar_imgurl;
    public $oauth_time;
}