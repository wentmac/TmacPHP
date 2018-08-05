<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Qrcode.class.php 832 2017-01-19 16:50:22Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class service_member_Qrcode_mobile extends service_Member_base
{

    /**
     * 商城二维码
     */
    const type_web_shop = 0;

    /**
     * 游戏二维码
     */
    const type_dfh = 1;

    /**
     * 红包二维码
     */
    const type_dfh_luckymoney = 2;

    private $reload_count = 0;
    private $expired_time = 7200;
    private $access_token;
    private $oauth_array;
    private $type;

    /**
     * 类型
     * 0：商城
     * 1：大富豪游戏
     * @param type $type
     */
    function setType( $type )
    {
        $this->type = $type;
    }

    public function __construct()
    {
        parent::__construct();
        $this->oauth_array = Tmac::config( 'oauth.oauth.wechat', APP_WWW_NAME );
    }

    public function getQrcode()
    {
        $weixin_token_model = new service_utils_WeixinToken_base();
        $weixin_token_model->setAppid( $this->oauth_array[ 'appid' ] );
        $weixin_token_model->setSecret( $this->oauth_array[ 'appsecret' ] );
        $weixin_token_model->setExpired_time( $this->expired_time );
        try {
            $this->access_token = $weixin_token_model->getAccessToken();
        } catch (TmacClassException $exc) {
            $this->errorMessage = $exc->getMessage();
            return FALSE;
        }

        $expired_time = 2592000;
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=" . $this->access_token; //获取ticket 请求路径 
        $postField = array(
            'expire_seconds' => $expired_time,
            'action_name' => 'QR_SCENE',
            'action_info' => array(
                'scene' => array(
                    'scene_id' => $this->uid
                )
            )
        );
        $data = json_encode( $postField );
        $res = Functions::curl_post_contents( $url, $data, 30, true );
        $token_array = json_decode( $res, true );
        if ( array_key_exists( 'errcode', $token_array ) ) {
            if ( empty( $this->reload_count ) ) {
                $this->reload_count++;
                $this->expired_time = 0;
                return $this->getQrcode();
            }
            throw new TmacException( $token_array[ 'errmsg' ] );
        }
        $url = $token_array[ 'url' ];
        $ticket = $token_array[ 'ticket' ];

        $date = date( 'Y-m-d', $this->now + $expired_time );

        if ( $this->type == self::type_dfh ) {
            $qrcode_model = new \dfh\service\QRCode();
            $qrcode_model->setQrcode_template( STATIC_URL . 'common/dfh_agent_qrcode.png' );
        } else if ( $this->type == self::type_dfh_luckymoney ) {
            $qrcode_model = new \dfh\service\QRCodeLuckyMoney();            
        } else {
            $qrcode_model = new service_utils_QRCode_base();
            $qrcode_model->setQrcode_template( STATIC_URL . 'common/agent_qrcode.png' );
        }        
        $qrcode_model->setShop_qrcode_status( true );
        $qrcode_model->setQrcode_title( 'fuck' );
        $qrcode_model->setQrcode_description( '此二维码有效推广期截止为' . $date );
        $qrcode_model->setUrl( $url );
        $qrcode_model->setUid( $this->uid );        
        $qrcode_model->getAgentQRCodeImage();
    }

}
