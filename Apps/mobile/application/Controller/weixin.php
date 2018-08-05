<?php

/**
 * 用户登录注册页面
 * ============================================================================
 * @author  by time 22014-07-07
 * 
 */
class weixinAction extends service_Controller_mobile
{

    const token = 'lancepan';

    //定义初始化变量

    public function _init()
    {
        parent::__construct();
    }

    /**
     * 第三方联合登录回调
     * 微博登录
     * TODO将来上线后做
     */
    public function receive()
    {
        $this->valid();
    }

    private function valid()
    {
        $echoStr = empty( $_GET[ "echostr" ] ) ? '' : $_GET[ "echostr" ];

        //valid signature , option
        if ( $this->checkSignature() ) {
            echo $echoStr;
        }
        $this->responseMsg();
    }

    private function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS[ "HTTP_RAW_POST_DATA" ];

        //extract post data
        if ( !empty( $postStr ) ) {

            $postObj = simplexml_load_string( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
            //post_error
            //Log::getInstance( 'post_error' )->write( var_export( $postObj, true ) );
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = isset( $postObj->Content ) ? trim( $postObj->Content ) : '';
            $event = $postObj->Event;
            $eventKey = $postObj->EventKey;
            $time = time();
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
            if ( !empty( $keyword ) ) {
                $msgType = "text";
                $contentStr = "欢迎来到宝身茶";
                $resultStr = sprintf( $textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr );
            } else if ( $event == 'subscribe' ) {
                $register_model = new service_account_Register_mobile();
                $register_model->setOpenid( $fromUsername );
                $register_model->setEventKey( $eventKey );
                try {
                    $res = $register_model->mpRegister();
                    $expire = 86400*10; //10天内
                    setcookie( 'is_first_login', 1, $this->now + $expire, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );
                } catch (TmacClassException $exc) {
                    Log::getInstance( 'post_error' )->write( $exc->getMessage() );
                }
                /*
                  $msgType = "text";
                  $contentStr = '您好，欢迎光临宝身茶!

                  买饰品就来宝身茶! 想创业加入宝身茶！

                  你美丽，我买单！千万饰品免费送！首次关注公众号获得50积分，转发二维码、签到均可获得积分，可用积分在积分兑换区免费兑换商品。

                  积分兑换，<a href="http://yph.weixinshow.com/shop/goodslist?id=46&goods_cat_id=467">请点击</a>

                  活动细则，<a href="http://yph.weixinshow.com/article/167.html">请点击</a>

                  兑换流程，<a href="http://yph.weixinshow.com/article/169.html">请点击</a>

                  每日签到，<a href="http://yph.weixinshow.com/member/home">请点击</a>

                  一元夺宝，<a href="http://www.xinwuwu.com/app/index.php?i=366&c=entry&do=attention&m=feng_duobao&from=singlemessage&isappinstalled=0">请点击</a>

                  会员详情，<a href="http://yph.weixinshow.com/article/168.html">请点击</a>

                  创业投资，请点击：

                  更多问题请加宝身茶粉丝群，微信号：
                  ';
                  $resultStr = sprintf( $textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr );
                 * 
                 */
                //$picUrl = STATIC_URL . APP_MOBILE_NAME . 'default/image/1519748916.jpg';
                $newsContent[ 0 ] = array(
                    'title' => '一款免费能赚钱的游戏',
                    'description' => '一款免费能赚钱的游戏-大富豪',
                    'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz_jpg/iburianKbjDarOnA0EaHkUkZ0WdNticQAfcrUI8a7CMOiamBo0LdNlZSOTgbzUx9LToO9tBzs6Mg5VqP6u0ia5rE4Sg/0?wx_fmt=jpeg',
                    'url' => 'http://yph.weixinshow.com/article/176.html'
                );
                $newsContent[ 1 ] = array(
                    'title' => '30元现金大奖天天有',
                    'description' => '30元现金大奖天天有',
                    'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz_jpg/iburianKbjDapJvT67Na1qOzibPTSEibVicuX62MheSaqITsrwn90eAI07hobPRqWLiaeAib07IsWLvEq3eQoHZWsR6ww/0?wx_fmt=jpeg',
                    'url' => 'http://yph.weixinshow.com/article/178.html'
                );
                $newsContent[ 2 ] = array(
                    'title' => '千万饰品免费大派送',
                    'description' => '千万饰品免费大派送',
                    'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz_jpg/iburianKbjDapITiayNxQjYdI1lgOLV2MJSmWM7rYNmo4GRcKR1IGnL4skZCZ5sy9OgzZm614VDzjt0IQsdcepffQ/0?wx_fmt=jpeg',
                    'url' => 'http://yph.weixinshow.com/article/174.html'
                );
                $newsContent[ 3 ] = array(
                    'title' => '选饰品就来宝身茶',
                    'description' => '选饰品就来宝身茶',
                    'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz_jpg/iburianKbjDapITiayNxQjYdI1lgOLV2MJS2cgI6D3ImZ0kmcJMPV9Qsib06Eux6oPjoyJoy00G5dSHWGIsGfkdrbw/0?wx_fmt=jpeg',
                    'url' => 'http://yph.weixinshow.com/article/173.html'
                );
                /**
                  $newsContent[ 4 ] = array(
                  'title' => '一元夺宝',
                  'description' => '一元夺宝',
                  'picUrl' => 'http://public.yph.weixinshow.com/mobile/default/image/wx_push_5.png',
                  'url' => 'http://www.xinwuwu.com/app/index.php?i=366&c=entry&do=attention&m=feng_duobao&from=singlemessage&isappinstalled=0&wxref=mp.weixin.qq.com#wechat_redirect'
                  );
                 * 
                 */
                /**
                  $newsContent[ 1 ] = array(
                  'title' => '平江路',
                  'description' => '平江路位于苏州古城东北，是一条傍河的小路，北接拙政园，南眺双塔，全长1606米，是苏州一条历史攸久的经典水巷。宋元时候苏州又名平江，以此名路...',
                  'picUrl' => 'http://joythink.duapp.com/images/suzhouScenic/pingjianglu.jpg',
                  'url' => 'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDM0NTEyMg==&appmsgid=10000056&itemidx=1&sign=ef18a26ce78c247f3071fb553484d97a#wechat_redirect'
                  );* */
                $resultStr = $this->responseMultiNews( $postObj, $newsContent );
                echo $resultStr;
            } else {
                $msgType = "text";
                $contentStr = "欢迎来到宝身茶";
                $resultStr = sprintf( $textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr );
            }
            echo $resultStr;
        } else {
            echo "";
            exit;
        }
    }

    private function responseMultiNews( $postObj, $newsContent )
    {
        $bodyCount = count( $newsContent );
        $bodyCount = $bodyCount < 10 ? $bodyCount : 10;


        $newsTplHead = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <ArticleCount>{$bodyCount}</ArticleCount>
                <Articles>";
        $newsTplBody = "<item>
                <Title><![CDATA[%s]]></Title> 
                <Description><![CDATA[%s]]></Description>
                <PicUrl><![CDATA[%s]]></PicUrl>
                <Url><![CDATA[%s]]></Url>
                </item>";
        $newsTplFoot = "</Articles>                
                </xml>";
        $header = sprintf( $newsTplHead, $postObj->FromUserName, $postObj->ToUserName, time() );

        foreach ( $newsContent as $value ) {
            $body .= sprintf( $newsTplBody, $value[ 'title' ], $value[ 'description' ], $value[ 'picUrl' ], $value[ 'url' ] );
        }

        $FuncFlag = 0;
        $footer = sprintf( $newsTplFoot, $FuncFlag );
        return $header . $body . $footer;
    }

    private function checkSignature()
    {
        $signature = empty( $_GET[ "signature" ] ) ? '' : $_GET[ "signature" ];
        $timestamp = empty( $_GET[ "timestamp" ] ) ? '' : $_GET[ "timestamp" ];
        $nonce = empty( $_GET[ "nonce" ] ) ? '' : $_GET[ "nonce" ];

        $token = self::token;
        $tmpArr = array( $token, $timestamp, $nonce );
        sort( $tmpArr );
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if ( $tmpStr == $signature ) {
            return true;
        } else {
            return false;
        }
    }

    public function test()
    {
        $postObj = array(
            'ToUserName' => 'gh_4eccc030a8c9',
            'FromUserName' => 'omNdGt2aXTLCxSWogEba67qFtppE',
            'CreateTime' => '1457352444',
            'MsgType' => 'event',
            'Event' => 'subscribe',
            'EventKey' => 'qrscene_37',
            'Ticket' => 'gQGU8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL0FrenlKUVhsZ3NIN01fbUFEbUpjAAIEhm7dVgMEgDoJAA==',
        );
        //post_error        
        $fromUsername = $postObj[ 'FromUserName' ];
        $toUsername = $postObj[ 'ToUserName' ];
        $event = $postObj[ 'Event' ];
        $eventKey = $postObj[ 'EventKey' ];
        $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
        if ( $event === 'subscribe' ) {
            $register_model = new service_account_Register_mobile();
            $register_model->setOpenid( $fromUsername );
            $register_model->setEventKey( $eventKey );

            try {
                $register_model->mpRegister();
            } catch (TmacClassException $exc) {
                die( $exc->getMessage() );
            }
        } else {
            echo "";
        }
    }

}
