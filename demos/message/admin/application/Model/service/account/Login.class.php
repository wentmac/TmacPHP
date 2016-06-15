<?php

/**
 * 前台 用户登录注册相关 模块 Model
 * ============================================================================
 * @author zhuqiang by time 2014-07-07 
 */
class service_account_Login_admin extends service_Account_admin
{

    private $username;
    private $password;
    private $expries;

    function setUsername( $username )
    {
        $this->username = $username;
    }

    function setPassword( $password )
    {
        $this->password = $password;
    }

    function setExpries( $expries )
    {
        $this->expries = $expries;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function checkLogin()
    {
        $user_info = $this->getUserInfoByUserName( $this->username );
        if ( empty( $user_info ) ) {
            $this->errorMessage = '亲，帐号或密码错误哦!';
            return false;
        }
        $user_info instanceof entity_User_base;
        //检测账号锁定
        if ( $user_info->locked_login == service_Account_admin::locked_login_yes ) {
            $this->errorMessage = '亲，您的账户被禁用!';
            return false;
        }
        //防暴破
        $checkLoginFailedResult = $this->checkLoginFailedCount( $user_info );
        if ( $checkLoginFailedResult == false ) {
            return false;
        }
        $check_password = md5( $this->password . $user_info->salt );
        if ( $user_info->password <> $check_password ) {
            self::updateLoginFailed( $user_info->uid );
            $this->errorMessage = "亲，帐号或密码错误了";
            return false;
        }
        $_SESSION[ 'verify_code_error_count' ] = 0;

        if ( $this->expries == 1 ) {
            $expire_day = 30;
        } else {
            $expire_day = 1;
        }

        //更新登录成功后member表的数据
        $this->modifyMemberLoginInfo( $user_info->uid );
        //更新cookie
        $this->updateMemberCookieCheck( $user_info, $expire_day );
        return $user_info;
    }

    /**
     * 更新用户登录失败次数及失败时间
     * @return type 
     */
    private function updateLoginFailed( $uid )
    {
        if ( isset( $_SESSION[ 'verify_code_error_count' ] ) ) {
            $_SESSION[ 'verify_code_error_count' ] ++;
        } else {
            $_SESSION[ 'verify_code_error_count' ] = 0;
        }
        $dao = dao_factory_base::getUserDao();
        $entity_User = new entity_User_base();
        $entity_User->last_login_time = $this->now;
        $entity_User->login_fail_count = new TmacDbExpr( 'login_fail_count+1' );

        $dao->setPk( $uid );
        return $dao->updateByPk( $entity_User );
    }

    /**
     * 检测登录次数是否过多
     * @param type $userInfo
     * @return type 
     */
    private function checkLoginFailedCount( $userInfo )
    {
        $userInfo instanceof entity_User_base;
        (int) $this->failedCount = self::MAX_FAILD_COUNT - $userInfo->login_fail_count + 1;

        if ( $userInfo->login_fail_count <= self::MAX_FAILD_COUNT ) {
            return true;
        }
        $lastLoginTime = $this->now - $userInfo->last_login_time;
        if ( $lastLoginTime > self::FAILD_ALLOW_TIME * 60 ) {
            return true;
        }
        $this->errorMessage = '密码错误错误次数过多,冻结' . self::FAILD_ALLOW_TIME . '分钟！';
        return false;
    }

    /**
     * 说明：登录成功后，更新用户登录信息
     * @author zhuqiang by time 2014-07-09
     * @param object $member_info
     */
    public function modifyMemberLoginInfo( $uid )
    {
        $entity_User_base = new entity_User_base();

        $entity_User_base->last_login_ip = Functions::get_client_ip();
        $entity_User_base->last_login_time = $this->now;
        $entity_User_base->login_fail_count = 0;

        $dao = dao_factory_base::getUserDao();
        $dao->setPk( $uid );
        $rs = $dao->updateByPk( $entity_User_base );
        return $rs;
    }

    /**
     * 说明：用户退出,清除对应的COOKIE
     * @author zhuqiang by time 2014-07-09
     */
    public function loginOut()
    {
        setcookie( 'admin_uid', '', $this->now - 3600, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );
        setcookie( 'admin_token', '', $this->now - 3600, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );
        setcookie( 'admin_username', '', $this->now - 3600, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );
        setcookie( 'admin_nicename', '', $this->now - 3600, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );
        return true;
    }

}
