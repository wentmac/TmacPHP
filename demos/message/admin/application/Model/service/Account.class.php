<?php

/**
 * WEB 后管理 学校模块
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhuqiang
 * $Id: School.class.php 6 2014-10-01 15:13:57Z 
 * http://www.t-mac.org；
 */
class service_Account_admin extends service_Model_base
{

    const MAX_FAILD_COUNT = 25; //允许的最大错误次数
    const FAILD_ALLOW_TIME = 60; //错误的冻结时间（分钟）       
    /**
     * 登录锁定状态
     * 正常
     */
    const locked_login_no = 0;

    /**
     * 登录锁定
     * 禁止登录
     */
    const locked_login_yes = 1;

    private $errorMessage;

    public function __construct()
    {
        parent::__construct();
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * 说明：根据用户名称，返回用户信息     
     * @param string $username
     */
    public function getUserInfoByUserName( $username )
    {
        $username = trim( $username );
        $dao = dao_factory_base::getUserDao();
        $user_type_dao = dao_factory_base::getUserTypeDao();

        $dao->join( $user_type_dao->getTable(), $user_type_dao->getTable() . '.rank=' . $dao->getTable() . '.rank', 'INNER' );
        $where = $dao->getTable() . ".username= '{$username}'";
        $dao->setWhere( $where );
        $result = $dao->getInfoByWhere();
        return $result;
    }

    /**
     * 说明：验证成功后，用户登录完成相关操作
     * @author zhangwentao
     */
    public function updateMemberCookieCheck( $user_info, $expire )
    {
        $expire_time = 86400 * $expire;
        $user_info instanceof entity_User_base;
        //$password = md5_16(md5($password).$salt);
        $token = md5( md5( $user_info->password ) . $user_info->salt );

        //更新数据库成功,保存session
        setcookie( 'admin_uid', $user_info->uid, $this->now + $expire_time, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );
        setcookie( 'admin_token', $token, $this->now + $expire_time, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );
        setcookie( 'admin_username', $user_info->username, $this->now + $expire_time, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );
        setcookie( 'admin_nicename', $user_info->nicename, $this->now + $expire_time, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );
    }

    /**
     * 说明：处理登录来源页面
     */
    public function getReferer()
    {
        $referer_url = Input::get( 'purl', '' )->String();

//        if ($referer_url == '') {
//			$referer_url = htmlspecialchars( Input::cookie ( 'Referer' )->String ());
//		}

        if ( $referer_url == 'ajax' ) {
            $referer_url = INDEX_URL . 'manage.php?m=bill.home';
        }

        if ( $referer_url == '' ) {
            $referer_url = isset( $_SERVER [ 'HTTP_REFERER' ] ) ? filter_input( INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_STRING ) : '';
        }

        $referer_url = urldecode( stripslashes( $referer_url ) );
        $referer_url = htmlspecialchars( $referer_url );

        $host = parse_url( $referer_url );

        if ( isset( $host [ 'host' ] ) && substr( $host [ 'host' ], - 6 ) != '090.cn' ) {
            $referer_url = '';
        }

        $referer_url = ($referer_url == '' || strpos( $referer_url, 'login' )) ? INDEX_URL . 'manage.php?m=bill.home' : $referer_url;

        return $referer_url;
    }

}
