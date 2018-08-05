<?php

/**
 * 接口 Controller父类 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Controller.class.php 793 2016-12-31 16:06:55Z zhangwentao $
 * http://shop.weixinshow.com；
 */
abstract class service_Controller_mobile extends service_Controller_base
{

    private $referer;
    protected $redirect_uri;

    /**
     * 初始化变量　定义私有变量
     */
    public function __construct()
    {
        parent::__construct();
        $loginUrl = MOBILE_URL . 'member/home?referer=' . urlencode( MOBILE_URL . substr( $_SERVER[ 'REQUEST_URI' ], 1 ) );
        $this->setLoginUrl( $loginUrl );

        $referer = $this->referer = isset( $_SERVER [ 'HTTP_REFERER' ] ) ? filter_input( INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_STRING ) : MOBILE_URL;

        $configcache = Tmac::config( 'configcache.config', APP_WWW_NAME, '.inc.php' );

        $this->redirect_uri = 'http://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];

        $this->assign( 'config', $configcache );
        $this->assign( 'referer_url', $referer );
    }

    /**
     * 基于微信公众号openid验证登录
     */
    protected function checkLogin()
    {
        $uid = Input::cookie( 'uid', 0 )->required( '用户UID不能为空' )->int();
        $token = Input::cookie( 'token', '' )->required( '用户验证密钥不能为空' )->string();


        if ( Filter::getStatus() === false ) {
            $this->redirectAuthorize();
            exit();
        }

        $checkEffective = self::checkMemberStatus( $uid );
        if ( $checkEffective === false ) {
            $this->redirectAuthorize();
            exit();
        }
        if ( $token <> md5( md5( $this->memberInfo->password ) . $this->memberInfo->salt ) ) {
            $this->redirectAuthorize();
            exit();
        }
        $this->memberInfo->member_image_id = empty( $this->memberInfo->member_image_id ) ? '' : $this->getImage( $this->memberInfo->member_image_id, 110, 'avatar' );
        return true;
    }

    protected function redirectAuthorize()
    {
        $model = new service_Oauth_base();
        $model->setRefererCookie( $this->redirect_uri );
        $weixin_model = new service_oauth_Weixin_base();
        $weixin_model->setRedirect_uri( MOBILE_URL . 'oauth/weixin' );
        parent::headerRedirect( $weixin_model->getAuthorizeUrl() );
    }

    /**
     * 检查接口签名
     */
    protected function checkSign()
    {
        $appkey = Input::get( 'appkey', 0 )->int();
        $sign = Input::get( 'sign', '' )->string();
        $timestamp = Input::get( 'timestamp', 0 )->int();
        $method = 'WebView.authorize';
        //$method = $_GET[ 'TMAC_CONTROLLER_FILE' ] . '.' . $_GET[ 'TMAC_ACTION' ];

        if ( empty( $appkey ) || empty( $sign ) || empty( $timestamp ) ) {
            $this->checkLogin();
            return true;
        }

        $time = $this->now;
        $timestampEarly = $time - 7200;
        $timestampLast = $time + 7200;
        if ( $timestamp < $timestampEarly || $timestamp > $timestampLast ) {
            die( '接口认证过期|请检查您的手机日期时间设置是否准确？当前服务器时间：' . date( 'Y-m-d H:i:s' ) );
        }
        $check_model = Tmac::model( 'Check', APP_API_NAME );
        $check_model instanceof service_Check_api;
        $checkSign = $check_model->generateSign( $method, $timestamp, $appkey, $sign );
        if ( $checkSign <> $sign ) {
            die( '接口签名认证失败' );
        }

        //验证oauth过来的用户登录认证是否合法
        $this->checkOauthLogin();
        //种下登录认证功能的cookie
        $login_model = new service_account_Login_base();
        //更新cookie
        $expire = 1; //1天过期时间
        $login_model->updateMemberCookieCheck( $this->memberInfo, $expire );
        return true;
    }

    /**
     * 检测是否登录 api的url登录
     */
    protected function checkOauthLogin()
    {
        $uid = Input::get( 'uid', 0 )->required( '用户UID不能为空' )->int();
        $token = Input::get( 'token', '' )->required( '用户验证密钥不能为空' )->string();

        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() );
        }
        $checkEffective = parent::checkMemberStatus( $uid );
        if ( $checkEffective === false ) {
            throw new ApiException( $this->getErrorMessage() );
        }
        if ( PRODUCTION_MODE == false ) {
            return true;
        }
        if ( $token <> md5( md5( $this->memberInfo->password ) . $this->memberInfo->salt ) ) {
            throw new ApiException( '认证失败，请先登录', -2 );
        }
    }

    public function no( $title = '' )
    {
        $array[ 'title' ] = $title;
        $this->assign( $array );
        $this->V( '404' );
        exit();
    }

    /**
     * 检测用户类型的页面访问权限
     * @param array $allow_member_type_array
     * @return bool
     */
    protected function checkPurview( $allow_member_type_array = [] )
    {
        if ( empty( $allow_member_type_array ) ) {
            return true;
        }
        if ( in_array( $this->memberInfo->member_type, $allow_member_type_array ) ) {
            return true;
        }
        $this->errorMessage = '没有此页面的访问权限哟';
        return false;
    }

}
