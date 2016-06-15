<?php

/**
 * WEB后台 Controller父类 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Controller.class.php 561 2014-12-09 10:08:03Z zhangwentao $
 * http://www.t-mac.org；
 */
abstract class service_Controller_admin extends service_Controller_base
{

    protected $userInfo;
    protected $expired_time;

    /**
     * 初始化变量　定义私有变量
     */
    public function __construct()
    {
        parent::__construct();
        $configcache = Tmac::config( 'configcache.config', APP_WWW_NAME, '.inc.php' );
        $this->assign( 'config', $configcache );

        $clear_cache = Input::get( 'clear_cache', '' )->string();
        $this->expired_time = 86400;
        if ( $clear_cache == 'tuzhibin' ) {
            $this->expired_time = 0;
        }
    }

    /**
     * 检查用户状态
     * @return type 
     */
    protected function checkUserStatus( $uid )
    {
        $dao = dao_factory_base::getUserDao();
        $user_type_dao = dao_factory_base::getUserTypeDao();

        $dao->join( $user_type_dao->getTable(), $user_type_dao->getTable() . '.rank=' . $dao->getTable() . '.rank', 'INNER' );
        $where = $dao->getTable() . ".uid={$uid}";
        $dao->setWhere( $where );
        $result = $dao->getInfoByWhere();
        if ( !$result ) {
            self::setErrorMessage( '用户不存在' );
            return false;
        }
        $this->userInfo = $result;
        return true;
    }

    /**
     * 检测是否登录
     */
    protected function checkLogin()
    {
        $uid = Input::cookie( 'admin_uid', 0 )->required( '用户UID不能为空' )->int();
        $token = Input::cookie( 'admin_token', '' )->required( '用户验证密钥不能为空' )->string();

        if ( Filter::getStatus() === false ) {
            $this->redirect( '请你登录系统', ADMIN_URL . PHP_SELF . '?m=account.login' );
        }
        $checkEffective = self::checkUserStatus( $uid );
        if ( $checkEffective === false ) {
            $this->redirect( self::getErrorMessage() );
        }
        if ( $token <> md5( md5( $this->userInfo->password ) . $this->userInfo->salt ) ) {
            $this->redirect( '认证失败，请先登录', ADMIN_URL . PHP_SELF . '?m=account.login' );
        }
        $this->setManageVariable();
        return true;
    }

    /**
     * 返回登录状态
     */
    protected function checkLoginStatus()
    {
        $uid = Input::cookie( 'admin_uid', 0 )->required( '用户UID不能为空' )->int();
        $token = Input::cookie( 'admin_token', '' )->required( '用户验证密钥不能为空' )->string();

        if ( Filter::getStatus() === false ) {
            return false;
        }
        $checkEffective = self::checkMemberStatus( $uid );
        if ( $checkEffective === false ) {
            return false;
        }
        if ( $token <> md5( md5( $this->memberInfo->password ) . $this->memberInfo->salt ) ) {
            return false;
        }
        return true;
    }

    protected function checkPurview( $n )
    {
        $check_model = new service_Check_admin();
        $check_model->setUserInfo( $this->userInfo );
        $check_model->CheckPurview( $n );
    }

    /**
     * manage控制台设置变量
     */
    protected function setManageVariable()
    {
        //$menu_array = $this->getMenu();
        $menu_array = Tmac::getCache( 'menu_array_' . $this->userInfo->uid, array( $this, 'getMenu' ), array( 0 ), $this->expired_time );
        $this->assign( 'menu_array', $menu_array );
        $this->assign( 'userInfo', $this->userInfo );
    }

    public function no( $title = '' )
    {
        $array[ 'title' ] = $title;
        $this->assign( $array );
        $this->V( '404' );
        exit();
    }

    public function getMenu()
    {
        $menusMain = "
        <m:top mapitem='0' name='数据管理' rank='tb_admin,tb_editer' link='' class='am-icon-rmb am-icon-fw'>
          <m:item name='留言列表' link='" . PHP_SELF . "?m=message' rank='tb_admin,tb_editer' target='main' class='am-icon-rmb am-icon-fw'/>          
        </m:top>                  
        
        <m:top mapitem='0' name='分类' rank='tb_admin,tb_editer' link='' class='am-icon-rmb am-icon-fw'>
          <m:item name='分类管理' link='" . PHP_SELF . "?m=category' rank='tb_admin' target='main' class='am-icon-rmb am-icon-fw'/>
          <m:item name='添加分类' link='" . PHP_SELF . "?m=category.add' rank='tb_admin,tb_editer' target='main' class='am-icon-rmb am-icon-fw'/>
        </m:top>
                
        <m:top mapitem='0' name='文章' rank='tb_admin,tb_editer' link='' class='am-icon-rmb am-icon-fw'>
          <m:item name='文章管理' link='" . PHP_SELF . "?m=archives.arclist' rank='tb_admin' target='main' class='am-icon-rmb am-icon-fw'/>
          <m:item name='添加新文章' link='" . PHP_SELF . "?m=archives.add' rank='tb_admin,tb_editer' target='main' class='am-icon-rmb am-icon-fw'/>
        </m:top>

        <m:top mapitem='0' name='管理员' rank='tb_admin' link='' class='am-icon-rmb am-icon-fw'>
          <m:item name='管理员管理' link='" . PHP_SELF . "?m=user' rank='tb_admin' target='main' class='am-icon-rmb am-icon-fw'/>
          <m:item name='添加管理员' link='" . PHP_SELF . "?m=user.detail' rank='tb_admin' target='main' class='am-icon-rmb am-icon-fw'/>
          <m:item name='管理员操作日志' link='" . PHP_SELF . "?m=user.detail' rank='tb_admin' target='main' class='am-icon-rmb am-icon-fw'/>    
        </m:top>
        
        <m:top mapitem='0' name='模板管理' rank='tb_admin' link='' class='am-icon-rmb am-icon-fw'>          
          <m:item name='模板选择' link='" . PHP_SELF . "?m=template' rank='tb_admin' target='main' class='am-icon-rmb am-icon-fw'/>          
          <m:item name='模板修改' link='" . PHP_SELF . "?m=template.edit' rank='tb_admin' target='main' class='am-icon-rmb am-icon-fw'/>          
        </m:top>
        
        <m:top mapitem='0' name='统计' rank='tb_admin,tb_editer' link='' class='am-icon-rmb am-icon-fw'>
          <m:item name='每日流量' link='" . PHP_SELF . "?m=config' rank='tb_admin' target='main' class='am-icon-rmb am-icon-fw'/>
          <m:item name='每日转化' link='" . PHP_SELF . "?m=login.out' rank='tb_admin,tb_editer' target='main' class='am-icon-rmb am-icon-fw'/>
        </m:top>
        
        <m:top mapitem='0' name='设置' rank='tb_admin,tb_editer' link='' class='am-icon-rmb am-icon-fw'>
          <m:item name='网站设置' link='" . PHP_SELF . "?m=config' rank='tb_admin' target='main' class='am-icon-rmb am-icon-fw'/>
          <m:item name='退出系统' link='" . PHP_SELF . "?m=login.out' rank='tb_admin,tb_editer' target='main' class='am-icon-rmb am-icon-fw'/>
        </m:top>
        
        <m:top mapitem='0' name='注销' rank='tb_admin,tb_editer' link='" . PHP_SELF . "?m=account.loginout' class='am-icon-sign-out am-icon-fw'>          
        </m:top>
        ";
        $menu_model = new service_Menu_admin();
        $menu_model->setUser_info( $this->userInfo );
        $menua_array = $menu_model->getMenua( $menusMain );
        return $menua_array;
    }

}
