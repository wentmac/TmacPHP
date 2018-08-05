<?php
namespace base\service\utils;

use Functions;
use Log;

/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */

class CCPRestSmsSDK extends \service_Model_base
{
    private $AccountSid = '8aaf07085cf275c4015cf7910d9a02a4';
    private $AccountToken = '5d8a47065c85408fbdc364a6faddc283';
    private $AppId = '8aaf07085cf275c4015cf7910ef902aa';
    private $ServerIP = 'sandboxapp.cloopen.com';
    private $ServerPort = '8883';
    private $SoftVersion = '2013-12-26';
    private $Batch;  //时间戳
    private $BodyType = "json";//包体格式，可填值：json 、xml
    private $enabeLog = true; //日志开关。可填值：true、


    #######################################################
    private $template_id;
    private $replace_datas;

    private $mobile;
    private $message;
    private $sms_type;
    private $sms_code;
    #######################################################
    private $errorMessage;

    /**
     * @param mixed $template_id
     */
    public function setTemplateId( $template_id )
    {
        $this->template_id = $template_id;
    }

    /**
     * @param mixed $replace_datas
     */
    public function setReplaceDatas( $replace_datas )
    {
        $this->replace_datas = $replace_datas;
    }


    /**
     * @param mixed $mobile
     */
    public function setMobile( $mobile )
    {
        $this->mobile = $mobile;
    }

    /**
     * @param mixed $message
     */
    public function setMessage( $message )
    {
        $this->message = $message;
    }


    /**
     * @param mixed $sms_type
     */
    public function setSms_type( $sms_type )
    {
        $this->sms_type = $sms_type;
    }

    /**
     * @param mixed $sms_code
     */
    public function setSms_code( $sms_code )
    {
        $this->sms_code = $sms_code;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }




    function __construct()
    {
        $this->Batch = date( "YmdHis" );
    }

    /**
     * 打印日志
     *
     * @param log 日志内容
     */
    function showlog( $log )
    {
        if ( $this->enabeLog ) {
            Log::getInstance( 'sms_error' )->write( var_export( $log, true ) );
            $this->errorMessage = '短信服务失败，请联系管理员';
            return true;
        }
    }

    /**
     * 发起HTTPS请求
     */
    function curl_post( $url, $data, $header, $post = 1 )
    {
        //初始化curl
        $ch = curl_init();
        //参数设置
        $res = curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt( $ch, CURLOPT_HEADER, 0 );
        curl_setopt( $ch, CURLOPT_POST, $post );
        if ( $post )
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
        $result = curl_exec( $ch );
        //连接失败
        if ( $result == FALSE ) {
            if ( $this->BodyType == 'json' ) {
                $result = "{\"statusCode\":\"172001\",\"statusMsg\":\"网络错误\"}";
            } else {
                $result = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Response><statusCode>172001</statusCode><statusMsg>网络错误</statusMsg></Response>";
            }
        }

        curl_close( $ch );
        return $result;
    }


    /**
     * 发送模板短信
     * @param to 短信接收彿手机号码集合,用英文逗号分开
     * @param datas 内容数据
     * @param $tempId 模板Id
     */
    function sendTemplateSMS( $to, $datas, $tempId )
    {
        //主帐号鉴权信息验证，对必选参数进行判空。
        $auth = $this->accAuth();
        if ( $auth != "" ) {
            return $auth;
        }
        // 拼接请求包体
        if ( $this->BodyType == "json" ) {
            $data = "";
            for ( $i = 0; $i < count( $datas ); $i++ ) {
                $data = $data . "'" . $datas[ $i ] . "',";
            }
            $body = "{'to':'$to','templateId':'$tempId','appId':'$this->AppId','datas':[" . $data . "]}";
        } else {
            $data = "";
            for ( $i = 0; $i < count( $datas ); $i++ ) {
                $data = $data . "<data>" . $datas[ $i ] . "</data>";
            }
            $body = "<TemplateSMS>
                    <to>$to</to> 
                    <appId>$this->AppId</appId>
                    <templateId>$tempId</templateId>
                    <datas>" . $data . "</datas>
                  </TemplateSMS>";
        }
        // 大写的sig参数 
        $sig = strtoupper( md5( $this->AccountSid . $this->AccountToken . $this->Batch ) );
        // 生成请求URL        
        $url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SMS/TemplateSMS?sig=$sig";
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode( $this->AccountSid . ":" . $this->Batch );
        // 生成包头  
        $header = array( "Accept:application/$this->BodyType", "Content-Type:application/$this->BodyType;charset=utf-8", "Authorization:$authen" );
        // 发送请求
        $result = $this->curl_post( $url, $body, $header );

        if ( $this->BodyType == "json" ) {//JSON格式
            $datas = json_decode( $result );
        } else { //xml格式
            $datas = simplexml_load_string( trim( $result, " \t\n\r" ) );
        }
        //  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
        //重新装填数据
        if ( $datas->statusCode == 0 ) {
            if ( $this->BodyType == "json" ) {
                $datas->TemplateSMS = $datas->templateSMS;
                unset( $datas->templateSMS );
            }
        } else {
            $log_body = "request body = " . $body
                . "request url = " . $url
                . "response body = " . $result
                . "template_id={$tempId}";
            $this->showlog( $log_body );
        }

        return $datas;
    }

    /**
     * 主帐号鉴权
     */
    function accAuth()
    {
        if ( $this->ServerIP == "" ) {
            $data = new stdClass();
            $data->statusCode = '172004';
            $data->statusMsg = 'IP为空';
            return $data;
        }
        if ( $this->ServerPort <= 0 ) {
            $data = new stdClass();
            $data->statusCode = '172005';
            $data->statusMsg = '端口错误（小于等于0）';
            return $data;
        }
        if ( $this->SoftVersion == "" ) {
            $data = new stdClass();
            $data->statusCode = '172013';
            $data->statusMsg = '版本号为空';
            return $data;
        }
        if ( $this->AccountSid == "" ) {
            $data = new stdClass();
            $data->statusCode = '172006';
            $data->statusMsg = '主帐号为空';
            return $data;
        }
        if ( $this->AccountToken == "" ) {
            $data = new stdClass();
            $data->statusCode = '172007';
            $data->statusMsg = '主帐号令牌为空';
            return $data;
        }
        if ( $this->AppId == "" ) {
            $data = new stdClass();
            $data->statusCode = '172012';
            $data->statusMsg = '应用ID为空';
            return $data;
        }
    }

    /**
     * $this->mobile;
     * $this->message;
     * $this->sms_type;
     * $this->sms_code;//可选
     * $this->sendSMS();
     * @return boolean
     * @throws TmacClassException
     */
    public function sendSMS()
    {
        if ( empty( $this->mobile ) ) {
            $this->errorMessage = '接收短信的手机号不能为空';
            return false;
        }
        if ( empty( $this->message ) ) {
            $this->errorMessage = '短信内容不能为空';
            return false;
        }
        if ( empty( $this->sms_type ) ) {
            $this->errorMessage = '短信内容不能为空';
            return false;
        }
        $sms_send_res = $this->sendTemplateSMS( $this->mobile, $this->replace_datas, $this->template_id );

        if ( $sms_send_res->statusCode == '000000' ) {
            $this->message .= "|sms_tempalte_id={$this->template_id}|sms_message_id={$sms_send_res->TemplateSMS->smsMessageSid}";
            //写到sms_log表中
            $entity_SmsLog_base = new \base\entity\SmsLog();
            $entity_SmsLog_base->sms_type = $this->sms_type;
            $entity_SmsLog_base->sms_code = $this->sms_code;
            $entity_SmsLog_base->sms_mobile = $this->mobile;
            $entity_SmsLog_base->sms_content = $this->message;
            $entity_SmsLog_base->sms_time = time();
            $entity_SmsLog_base->sms_ip = Functions::get_client_ip();
            $entity_SmsLog_base->result_code = $sms_send_res->statusCode;

            $dao = \dao_factory_base::getSmsLogDao();
            $res = $dao->insert( $entity_SmsLog_base );
            if ( $res ) {
                return true;
            } else {
                Log::getInstance( 'sms_error' )->write( var_export( $entity_SmsLog_base, true ) );
                return false;
            }
        } else {
            //写错误log
            Log::getInstance( 'sms_error' )->write( var_export( $sms_send_res, true ) );
            $this->errorMessage = '短信服务失败，请联系管理员';
            return false;
        }

    }
}

?>
