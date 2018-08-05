<?php

/**
 * 微信jssdk接口
  $this->oauth_array = Tmac::config( 'oauth.oauth.wechat', APP_WWW_NAME );
  $weixin_token_model = new \base\service\utils\WeixinJSSDK();
  $weixin_token_model->setAppid( $this->oauth_array[ 'appid' ] );
  $weixin_token_model->setSecret( $this->oauth_array[ 'appsecret' ] );
  $weixin_token_model->getSignPackage();
 */

namespace base\service\utils;

use Tmac;
use Functions;

class WeixinJSSDK extends \service_utils_WeixinToken_base
{

    private $url;

    function setUrl( $url )
    {
        $this->url = $url;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getSignPackage()
    {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] !== 'off' || $_SERVER[ 'SERVER_PORT' ] == 443) ? "https://" : "http://";
        //$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if ( empty( $this->url ) ) {
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        } else {
            $url = $this->url;
        }

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1( $string );

        $signPackage = array(
            "appId" => $this->appid,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr( $length = 16 )
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ( $i = 0; $i < $length; $i++ ) {
            $str .= substr( $chars, mt_rand( 0, strlen( $chars ) - 1 ), 1 );
        }
        return $str;
    }

    public function getJsApiTicket()
    {
        $ticket_array = Tmac::getCache( 'weixin_js_api_ticket_' . $this->appid, array( $this, 'getJsApiTicketFromWeixin' ), array(), $this->expired_time );
        if ( empty( $ticket_array[ 'ticket' ] ) ) {
            $this->expired_time = 0;
            return $this->getJsApiTicket();
        }
        return $ticket_array[ 'ticket' ];
    }

    public function getJsApiTicketFromWeixin()
    {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $accessToken = $this->getAccessToken();
        // 如果是企业号用以下 URL 获取 ticket
        // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
        $res = Functions::curl_file_get_contents( $url, 30, true );
        $ticket_array = json_decode( $res, true );
        if ( $ticket_array[ 'errcode' ] <> 0 ) {
            throw new TmacClassException( $ticket_array[ 'errmsg' ] );
        } else {
            return $ticket_array;
        }
    }

}
