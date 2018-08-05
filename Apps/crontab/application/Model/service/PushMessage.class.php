<?php

/**
 * api 消息推送 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Member.class.php 641 2014-12-20 11:52:06Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class service_PushMessage_crontab extends service_PushMessage_base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 执行消息推送
     */
    public function push_execute()
    {
        $where = 'sms_type=' . service_Account_base::sms_type_message . ' AND sms_success=0';
        $dao = dao_factory_base::getSmsLogDao();
        $dao->setWhere( $where );
        $res = $dao->getListByWhere();

        if ( !$res ) {
            return true;
        }

        $sms_model = new service_utils_SmsApiChuanglan_base();

        //执行推送 $this->push_queue;
        //设计模式观察者模式
        foreach ( $res as $push ) {
            $push instanceof entity_SmsLog_base;
            //TODO app push
            $this->appPush( $push->sms_linked_id, '聚店通知消息:', $push->sms_content, $push_type = 'order' );
            //短信发送开始
            if ( $push->is_only_push == 1 ) {
                $entity_SmsLog_base = new entity_SmsLog_base();
                $entity_SmsLog_base->sms_success = 1;

                $dao->setPk( $push->sms_id );
                $res = $dao->updateByPk( $entity_SmsLog_base );
                continue;
            }

            if ( empty( $push->sms_mobile ) ) {
                continue;
            }            
            //判断sms_mobile是还是是手机号
            if ( !preg_match( '/^1([3]|[5]|[8]|[4]|[7])[0-9]{9}$/', $push->sms_mobile ) ) {                
                $sms_send_res[ 1 ] = 0;//不是手机号格式也发短信
            } else {
                $sms_model->setMobile( $push->sms_mobile );
                $sms_model->setMessage( $push->sms_content );
                $sms_send_res = $sms_model->send();
            }
            if ( $sms_send_res[ 1 ] == 0 ) {
                //写到sms_log表中
                $entity_SmsLog_base = new entity_SmsLog_base();
                $entity_SmsLog_base->result_code = $sms_send_res[ 0 ];
                $entity_SmsLog_base->sms_success = 1;

                $dao->setPk( $push->sms_id );
                $res = $dao->updateByPk( $entity_SmsLog_base );
            } else {
                //写错误log
                Log::getInstance( 'push_message' )->write( 'sms|' . $sms_model->getErrorMessage() . var_export( $push, true ) );
            }
        }

        return true;
    }

    /**
     * app push message
     * @param type $uid
     * @param type $title
     * @param type $content
     * @param type $push_type
     * @return boolean
     */
    public function appPush( $uid, $title, $content, $push_type = 'order' )
    {
        $push_config = Tmac::config( 'push.push', APP_BASE_NAME );
        $push_config_android = $push_config[ 'android' ];
        $push_config_ios = $push_config[ 'ios' ];
        require_once Tmac::findFile( 'XingeApp', APP_CRONTAB_NAME );

        $push = new XingeApp( $push_config_android[ 'access_id' ], $push_config_android[ 'secret_key' ] );
        $mess = new Message();
        $mess->setExpireTime( 86400 );
        $mess->setTitle( $title );
        $mess->setContent( $content );
        $custom = array( 'push_type' => $push_type );
        $mess->setCustom( $custom );
        $mess->setType( Message::TYPE_NOTIFICATION );
        $ret = $push->PushSingleAccount( 0, $uid, $mess );
        if ( $ret[ 'ret_code' ] <> 0 ) {
            //Log::getInstance( 'push_message' )->write( 'push_android|' . $ret[ 'ret_code' ] . $ret[ 'err_msg' ] . var_export( $mess, true ) );
        }


        $push = new XingeApp( $push_config_ios[ 'access_id' ], $push_config_ios[ 'secret_key' ] );
        $mess = new MessageIOS();
        $mess->setExpireTime( 86400 );
        $mess->setAlert( $content );
        $custom = array( 'push_type' => $push_type );
        $mess->setCustom( $custom );
        $ret = $push->PushSingleAccount( 0, $uid, $mess, XingeApp::IOSENV_PROD );
        if ( $ret[ 'ret_code' ] <> 0 ) {
            //Log::getInstance( 'push_message' )->write( 'push_ios|' . $ret[ 'ret_code' ] . $ret[ 'err_msg' ] . var_export( $mess, true ) );
        }
        return true;
    }

}
