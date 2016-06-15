<?php

/**
 * 前台 首页 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: index.php 562 2014-12-09 15:38:31Z zhangwentao $
 * http://www.t-mac.org；
 */
class messageAction extends service_Controller_mobile
{

    public function __construct()
    {
        parent::__construct();
    }

    public function save()
    {
        $username = Input::post( 'username', '' )->required( '姓名不能为空' )->string();
        $mobile = Input::post( 'mobile', '' )->required( '手机号不能为空' )->tel();
        $message_class = Input::post( 'message_class', 0 )->required( '类型不能为空' )->int();
        $credit = Input::post( 'credit', 0 )->required( '额度不能为空' )->int();
        $id_card = Input::post( 'id_card', '' )->string();

        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }
        if ( !empty( $id_card ) ) {
            $check_id_card = $this->checkIdCard( $id_card );
            if ( $check_id_card == false ) {
                $id_card = '';
            }
        }

        $entity_Message_base = new entity_Message_base();
        $entity_Message_base->username = $username;
        $entity_Message_base->mobile = $mobile;
        $entity_Message_base->message_type = service_Message_base::message_type_loan;
        $entity_Message_base->message_class = $message_class;
        $entity_Message_base->credit = $credit;
        $entity_Message_base->id_card = $id_card;

        $model = new service_Message_mobile();
        $res = $model->createMessage( $entity_Message_base );
        if ( $res ) {
            $this->apiReturn();
        } else {
            $model_error = $model->getErrorMessage();
            $message = empty( $model_error ) ? '报告有情况，请联系管理员' : $model->getErrorMessage();
            throw new ApiException( $message );
        }
    }

    private function checkIdCard( $idcard )
    {

        // 只能是18位  
        if ( strlen( $idcard ) != 18 ) {
            return false;
        }

        // 取出本体码  
        $idcard_base = substr( $idcard, 0, 17 );

        // 取出校验码  
        $verify_code = substr( $idcard, 17, 1 );

        // 加权因子  
        $factor = array( 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2 );

        // 校验码对应值  
        $verify_code_list = array( '1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2' );

        // 根据前17位计算校验码  
        $total = 0;
        for ( $i = 0; $i < 17; $i++ ) {
            $total += substr( $idcard_base, $i, 1 ) * $factor[ $i ];
        }

        // 取模  
        $mod = $total % 11;

        // 比较校验码  
        if ( $verify_code == $verify_code_list[ $mod ] ) {
            return true;
        } else {
            return false;
        }
    }

}
