<?php

/**
 * 账单 
 * ============================================================================
 * @author  by time 22014-07-07
 * 
 */
class qrcodeAction extends service_Controller_mobile
{

    //定义初始化变量

    public function __construct()
    {
        parent::__construct();
        //$this->checkLogin();        
    }

    /**
     * 取账单统计
     */
    public function detail()
    {
        $uid = Input::get( 'uid', 0 )->int();
        $type = Input::get( 'type', 0 )->int(); //推广类型 0：宝身茶商城|1：大富豪游戏
        $login = $this->checkLoginStatus();
        if ( empty( $uid ) && $login == false ) {
            die( '用户不能为空' );
        }
        if ( $login ) {
            $uid = $this->memberInfo->uid;
        }
        $array[ 'uid' ] = $uid;
        $array[ 'type' ] = $type;
        $this->assign( $array );
        $this->V( 'member/qrcode_detail' );
    }

    /**
     * 取账单统计
     */
    public function get_image()
    {
        set_time_limit( 0 );
        $uid = Input::get( 'uid', 7478 )->int();
        $type = Input::get( 'type', 0 )->int(); //推广类型 0：宝身茶商城|1：大富豪游戏
        $model = new service_member_Qrcode_mobile();
        $model->setUid( $uid );
        $model->setType( $type );
        $model->getQrcode();
    }

}
