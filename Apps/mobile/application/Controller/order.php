<?php

/**
 * mobile 购物车 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: order.php 793 2016-12-31 16:06:55Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class orderAction extends service_Controller_mobile
{

    public function __construct()
    {
        parent::__construct();
        Tmac::session();
        $this->checkLogin();
    }

    /**
     * 购物车删除
     * @throws ApiException
     */
    public function cart_delete()
    {
        $cart_id_string = Input::post( 'cart_id', 0 )->required( '购物车不能为空' )->intString();
        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }
        $login_status = $this->checkLoginStatus();
        if ( $login_status ) {
            $uid = $this->memberInfo->uid;
        } else {
            $uid = 0;
        }

        $model = new service_order_Cart_mobile();
        $model->setUid( $uid );
        $res = $model->deleteCartByIdString( $cart_id_string );
        $this->apiReturn( $res );
    }

    /**
     * 添加到购物车中
     */
    public function cart_save()
    {
        $item_id = Input::post( 'id', 0 )->required( '商品项目不能为空' )->int();
        $item_num = Input::post( 'num', 1 )->int();
        $goods_sku_id = Input::post( 'sid', 0 )->int();

        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }
        //显示详细信息 （标题｜sku{报价｜库存}）&&判断是否能购买
        //登录状态（收货地址）
        $login_status = $this->checkLoginStatus();
        if ( $login_status ) {
            $uid = $this->memberInfo->uid;
        } else {
            $uid = 0;
        }

        $model = new service_order_Cart_mobile();
        $model->setItem_id( $item_id );
        $model->setGoods_sku_id( $goods_sku_id );
        $model->setItem_num( $item_num );
        $model->setUid( $uid );
        $model->setMemberInfo( $this->memberInfo );

        $res = $model->saveCart();
        if ( $res ) {
            $this->apiReturn( $res );
        } else {
            throw new ApiException( $model->getErrorMessage() );
        }
    }

    /**
     * 批量
     * 添加到购物车中
     */
    public function cart_batch_save()
    {
        //登录状态（收货地址）
        $login_status = $this->checkLoginStatus();
        if ( $login_status ) {
            $uid = $this->memberInfo->uid;
        } else {
            $uid = 0;
        }

        $error_message = '';
        $success = true;

        $cart_array = file_get_contents( "php://input" );
        $cart_array = json_decode($cart_array,true);

        $model = new service_order_Cart_mobile();
        if ( empty( $cart_array ) ) {
            throw new ApiException( '参数不正确' );
        }
        foreach ( $cart_array as $cart ) {
            $item_id = $cart[ 'item_id' ];
            $item_num = empty( $cart[ 'item_num' ] ) ? 0 : $cart[ 'item_num' ];
            $goods_sku_id = empty( $cart[ 'goods_sku_id' ] ) ? 0 : $cart[ 'goods_sku_id' ];

            if ( empty( $item_id ) || empty($item_num) ) {
                continue;
            }

            //显示详细信息 （标题｜sku{报价｜库存}）&&判断是否能购买
            $model->setItem_id( $item_id );
            $model->setGoods_sku_id( $goods_sku_id );
            $model->setItem_num( $item_num );
            $model->setUid( $uid );
            $model->setMemberInfo( $this->memberInfo );

            $res = $model->saveCart();
            if ( !$res ) {
                $error_message .= '|' . $model->getErrorMessage();
                $success = false;
            }
        }
        if ( $success ) {
            $this->apiReturn();
        } else {
            throw new ApiException( $error_message );
        }


    }

    /**
     * 购物车 列表详细
     */
    public function cart()
    {
        $item_id = Input::get( 'id', 0 )->int();
        $item_num = Input::get( 'num', 1 )->int();
        $goods_sku_id = Input::get( 'sid', 0 )->int();

        //登录状态（收货地址）
        $login_status = $this->checkLoginStatus();
        if ( $login_status ) {
            //调用收货地址
            $member_info = array(
                'uid' => $this->memberInfo->uid,
                'username' => $this->memberInfo->username,
                'mobile' => $this->memberInfo->mobile
            );
            $member_model = new service_Member_base();
            $member_setting_info = $member_model->getMemberSettingInfoByUid( $this->memberInfo->uid );
            $voucher_money = $member_setting_info->voucher_money;
        } else {
            //跳到微信中登录
            $this->redirect_uri = MOBILE_URL . 'member/home';
            $this->redirectAuthorize();
            //告诉用户登录
            $member_info = array(
                'uid' => 0,
                'username' => '',
                'mobile' => Input::cookie( 'mobile', '' )->tel()
            );
            $available_integral = 0;
            $voucher_money = 0;
        }
        $model = new service_order_Cart_mobile();
        $model->setUid( $member_info[ 'uid' ] );
        if ( $login_status ) {
            $model->setAvailable_integral( $this->memberInfo->available_integral );
            $model->setVoucher_money( $voucher_money );
            $available_integral = $this->memberInfo->available_integral;
        }
        if ( !empty( $item_id ) ) {
            $model->setItem_id( $item_id );
            $model->setGoods_sku_id( $goods_sku_id );
            $model->setItem_num( $item_num );

            $res = $model->saveCart();
            if ( $res == false ) {
                throw new ApiException( $model->getErrorMessage() );
            }
        } else {
            $model->updateCartSessionIdTOUid();
        }

        $res = $model->getCartList();
        //发送手机号
        //调用手机号       
        $stock_array = $model->getGoods_stock_array();

        //写来源cookie
        $oauth_model = new service_Oauth_base();
        $oauth_model->setRefererCookie( MOBILE_URL . 'order/cart' );

        $array[ 'cart_list' ] = $res;
        $array[ 'goods_stock_array' ] = $stock_array; //商品的库存
        $array[ 'goods_stock_array_json' ] = json_encode( $stock_array, true ); //商品的库存json
        $array[ 'member_info' ] = $member_info;
        $array[ 'member_info_json' ] = json_encode( $member_info, true );
        $array[ 'available_integral' ] = $this->memberInfo->available_integral;
        $array[ 'available_voucher_money' ] = $voucher_money; //可用购物券
//        echo '<pre>';
//        print_r( $array );
//        echo '</pre>';
//        die;
        $this->assign( $array );
        $this->V( 'order_cart' );
    }

    /**
     * 给购物车批量更新数量页面 post使用的 更新购物车的商品数量
     */
    public function cart_batch_update()
    {
        $item_uid = Input::post( 'item_uid', 0 )->int(); //卖家uid        
        $cart_array = empty( $_POST[ 'cart_array' ] ) ? '' : $_POST[ 'cart_array' ];

        $login_status = $this->checkLoginStatus();
        if ( $login_status == false ) {
            throw new ApiException( '请先登录，亲' );
        }
        $model = new service_order_Cart_mobile();
        $model->setUid( $this->memberInfo->uid );
        $model->setItem_uid( $item_uid );
        $model->setCart_array( $cart_array );

        //取出购物车里的数据展示出来。做为下单时前的确认，如果没问题。下一步就提交到订单表中
        try {
            $cart_id_string = $model->updateCart();
            $this->apiReturn( $cart_id_string );
        } catch ( TmacClassException $exc ) {
            throw new ApiException( $exc->getMessage() );
        }
    }

    /**
     * 订单检出/查看 订单确认
     */
    public function confirm()
    {
        $item_uid = Input::get( 'item_uid', 0 )->required( '店铺ID不能为空' )->int(); //卖家uid
        $goods_uid = Input::get( 'goods_uid', 0 )->required( '商家ID不能为空' )->int(); //卖家uid
        $address_id = Input::get( 'address_id', 0 )->int();

        if ( Filter::getStatus() === false ) {
            parent::no( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }

        $login_status = $this->checkLoginStatus();
        if ( $login_status == false ) {
            parent::headerRedirect( MOBILE_URL . 'order/cart' );
        }
        if ( empty( $this->memberInfo->mobile ) ) {
            parent::headerRedirect( MOBILE_URL . 'order/cart' );
        }
        $member_setting_info = $this->memberSettingInfo;
        $voucher_money = $member_setting_info->voucher_money;

        $model = new service_order_Cart_mobile();
        $model->setUid( $this->memberInfo->uid );
        $model->setItem_uid( $item_uid );
        $model->setGoods_uid( $goods_uid );
        $model->setAddress_id( $address_id );
        $model->setAvailable_integral( $this->memberInfo->available_integral );
        $model->setVoucher_money( $voucher_money );

        setcookie( 'back_order_address_url', MOBILE_URL . 'order/confirm?item_uid=' . $item_uid . '&goods_uid=' . $goods_uid, $this->now + 3600, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );

        //取默认地址 如果没有地址 就跳到地址页面
        $address_info = $model->getDefaultAddress();
        if ( $address_info == false ) {
            parent::headerRedirect( MOBILE_URL . 'order/address_add' );
        }
        $cart_id_string = false;
        try {
            $array = $model->getCartListByIdString( $cart_id_string );
        } catch ( TmacClassException $exc ) {
            parent::headerRedirect( MOBILE_URL . 'order/cart' );
        }

        $weixin_id = $model->getMemberWeixinId();
//         echo '<pre>';
//         print_r( $address_info );
//         print_r( $array );
//        die;              
        $this->assign( $array );
        $this->assign( 'address_info', $address_info );
        $this->assign( 'item_uid', $item_uid );
        $this->assign( 'goods_uid', $goods_uid );
        $this->assign( 'weixin_id', $weixin_id );
        $this->assign( 'agent_lock', $this->memberInfo->agent_lock );
        $this->assign( 'agent_uid', $this->memberInfo->agent_uid );
        $this->assign( 'available_integral', $this->memberInfo->available_integral );
        $this->V( 'order_confirm' );
    }

    /**
     * 订单检出/查看 订单确认
     */
    public function exchange_check()
    {
        $goods_id = Input::get( 'goods_id', 0 )->required( '兑换商品不能为空' )->int(); //是给兑换商品下单用的                    

        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }

        $login_status = $this->checkLoginStatus();
        if ( $login_status == false ) {
            throw new ApiException( '请先登录' );
        }
        if ( empty( $this->memberInfo->mobile ) ) {
            throw new ApiException( '请先绑定手机号' );
        }

        $model = new service_order_Cart_mobile();
        $model->setUid( $this->memberInfo->uid );
        $model->setAddress_id( 0 );

        //取默认地址 如果没有地址 就跳到地址页面
        $address_info = $model->getDefaultAddress();
        setcookie( 'back_order_address_url', MOBILE_URL . 'order/exchange_confirm?goods_id=' . $goods_id, $this->now + 3600, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );
        if ( $address_info == false ) {
            throw new ApiException( '选择地址', 2 );
        }

        $member_voucher_model = \base\service\Exchange::factory( $goods_id );
        if ( $member_voucher_model === false ) {
            throw new ApiException( $member_voucher_model->getErrorMessage() );
        }
        $member_voucher_model->setMemberInfo( $this->memberInfo );
        $member_voucher_model->setMemberSetting( $this->memberSettingInfo );
        $member_voucher_model->setGoods_id( $goods_id );
        $goods_info = $member_voucher_model->getExchangeGoods();
        if ( !$goods_info ) {
            throw new ApiException( $member_voucher_model->getErrorMessage() );
        }

        $this->apiReturn();
    }

    /**
     * 订单检出/查看 订单确认
     */
    public function exchange_confirm()
    {
        $goods_id = Input::get( 'goods_id', 0 )->required( '兑换商品不能为空' )->int(); //是给兑换商品下单用的
        $address_id = Input::get( 'address_id', 0 )->int();

        if ( Filter::getStatus() === false ) {
            parent::no( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }

        $login_status = $this->checkLoginStatus();
        if ( $login_status == false ) {
            parent::headerRedirect( DFH_URL . 'dfh.php?m=member.exchange' );
        }
        if ( empty( $this->memberInfo->mobile ) ) {
            parent::headerRedirect( DFH_URL . 'dfh.php?m=member.exchange' );
        }

        $model = new service_order_Cart_mobile();
        $model->setUid( $this->memberInfo->uid );
        $model->setAddress_id( $address_id );

        setcookie( 'back_order_address_url', MOBILE_URL . 'order/exchange_confirm?goods_id=' . $goods_id, $this->now + 3600, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );

        //取默认地址 如果没有地址 就跳到地址页面
        $address_info = $model->getDefaultAddress();
        if ( $address_info == false ) {
            parent::headerRedirect( MOBILE_URL . 'order/address_add' );
        }

        $weixin_id = $model->getMemberWeixinId();

        $member_voucher_model = \base\service\Exchange::factory( $goods_id );
        if ( $member_voucher_model === false ) {
            parent::headerRedirect( DFH_URL . 'dfh.php?m=member.exchange' );
        }
        $member_voucher_model->setMemberInfo( $this->memberInfo );
        $member_voucher_model->setMemberSetting( $this->memberSettingInfo );
        $member_voucher_model->setGoods_id( $goods_id );
        $goods_info = $member_voucher_model->getExchangeGoods();
        if ( !$goods_info ) {
            parent::headerRedirect( DFH_URL . 'dfh.php?m=member.exchange' );
        }
        $goods_info->goods_image_id = $this->getImage( $goods_info->goods_image_id, 50, 'goods' );

//         echo '<pre>';
//         print_r( $address_info );
//         print_r( $array );
//        die;              

        $this->assign( 'address_info', $address_info );
        $this->assign( 'goods_info', $goods_info );
        $this->assign( 'weixin_id', $weixin_id );
        $this->assign( 'memberSettingInfo', $this->memberSettingInfo );

        $this->V( 'order_exchange_confirm' );
    }

    /**
     * 订单保存
     */
    public function exchange_save()
    {
        $goods_id = Input::post( 'goods_id', 0 )->required( '兑换商品不能为空' )->int(); //卖家uid
        $address_id = Input::post( 'address_id', 0 )->required( '收货地址不能为空' )->int();
        $postscript = Input::post( 'postscript', '' )->string(); //订单附言，由用户提交订单前填写
        $weixin_id = Input::post( 'weixin_id', 0 )->string(); //买家微信号（方便卖家与你联系）

        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }
        $login_status = $this->checkLoginStatus();
        if ( $login_status == false ) {
            throw new ApiException( '请登录', -2 ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }
        if ( empty( $this->memberInfo->mobile ) ) {
            throw new ApiException( '请先绑定手机号', -2 ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }

        $member_voucher_model = \base\service\Exchange::factory( $goods_id );
        $member_voucher_model instanceof \dfh\service\exchange\Goods;
        if ( $member_voucher_model === false ) {
            throw new ApiException( $member_voucher_model->getErrorMessage() );
        }
        $member_voucher_model->setMemberInfo( $this->memberInfo );
        $member_voucher_model->setMemberSetting( $this->memberSettingInfo );
        $member_voucher_model->setGoods_id( $goods_id );
        $member_voucher_model->setAddress_id( $address_id );
        $member_voucher_model->setPostscript( $postscript );
        $member_voucher_model->setWeixin_id( $weixin_id );

        try {
            $message = '订单保存失败';
            $order_sn = $member_voucher_model->exchangeCurrency();
            if ( $order_sn == false ) {
                $error_message = $member_voucher_model->getErrorMessage();
                $message = $error_message && !empty( $error_message );
                throw new ApiException( $message );
            }
            $this->apiReturn( $order_sn );
        } catch ( TmacClassException $exc ) {
            throw new ApiException( $exc->getMessage() );
        }
    }

    /**
     * 首次订单新增地址
     */
    public function address_add()
    {
        //登录状态（收货地址）
        $login_status = $this->checkLoginStatus();
        if ( $login_status ) {
            //调用收货地址
            $member_info = array(
                'uid' => $this->memberInfo->uid,
                'username' => $this->memberInfo->username,
                'mobile' => $this->memberInfo->mobile
            );
        } else {
            //告诉用户登录
            $member_info = array(
                'uid' => 0,
                'username' => '',
                'mobile' => Input::cookie( 'mobile', '' )->tel()
            );
        }
        $array[ 'member_info_json' ] = json_encode( $member_info, true );
        $this->assign( $array );
        $this->V( 'order_address_add' );
    }

    /**
     * 订单保存
     */
    public function save()
    {
        $item_uid = Input::post( 'item_uid', 0 )->required( '店铺ID不能为空' )->int(); //卖家uid
        $goods_uid = Input::post( 'goods_uid', 0 )->required( '商家ID不能为空' )->int(); //卖家uid
        $address_id = Input::post( 'address_id', 0 )->required( '收货地址不能为空' )->int();
        $cart_id_string = Input::post( 'cart_id_string', 0 )->required( '商品不能为空' )->intString();
        $postscript = Input::post( 'postscript', '' )->string(); //订单附言，由用户提交订单前填写
        $weixin_id = Input::post( 'weixin_id', 0 )->string(); //买家微信号（方便卖家与你联系）
        $agent_uid = Input::post( 'agent_uid', 0 )->int(); //推荐人

        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }

        $login_status = $this->checkLoginStatus();
        if ( $login_status == false ) {
            throw new ApiException( '请登录', -2 ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }

        if ( empty( $this->memberInfo->mobile ) ) {
            throw new ApiException( '请先绑定手机号', -2 ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }

        $member_model = new service_Member_base();
        $member_setting_info = $member_model->getMemberSettingInfoByUid( $this->memberInfo->uid );
        $voucher_money = $member_setting_info->voucher_money;

        $order_action_username = empty( $this->memberInfo->username ) ? $this->memberInfo->mobile : $this->memberInfo->username;

        $model = new service_order_Save_mobile();
        $model->setUid( $this->memberInfo->uid );
        $model->setGoods_uid( $goods_uid );
        $model->setItem_uid( $item_uid );
        $model->setAddress_id( $address_id );
        $model->setCart_id_string( $cart_id_string );
        $model->setPostscript( $postscript );
        $model->setWeixin_id( $weixin_id );
        $model->setOrder_action_username( $order_action_username );
        $model->setMemberInfo( $this->memberInfo );
        $model->setAgent_uid( $agent_uid );
        $model->setVoucher_money( $voucher_money );

        try {
            $message = '订单保存失败';
            $order_sn = $model->createOrder();
            if ( $order_sn == false ) {
                $error_message = $model->getErrorMessage();
                if ( !empty( $error_message ) ) {
                    $message = $error_message;
                }
                throw new ApiException( $message );
            }
            $this->apiReturn( $order_sn );
        } catch ( TmacClassException $exc ) {
            throw new ApiException( $exc->getMessage() );
        }
    }

    /**
     * 支付确认页面
     */
    public function payment()
    {
        $order_sn = Input::get( 'sn', 0 )->required( '订单ID不能为空' )->bigint(); //卖家uid        
        /**
         * $login_status = $this->checkLoginStatus();
         * if ( $login_status == false ) {
         * parent::headerRedirect( MOBILE_URL . 'order/cart' );
         * }
         *
         */
        $model = new service_order_Payment_mobile();
        $order_info = $model->getOrderInfoBySN( $order_sn );
        if ( !$order_info ) {
            $this->redirect( '订单不存在' );
        }
        $order_info instanceof entity_OrderInfo_base;
        if ( !empty( $order_info->pay_status ) ) {
            $this->redirect( '订单已经付款' );
        }
        //if ( $order_info->order_payable_amount == $order_info->order_amount + $order_info->order_integral_amount && $order_info->order_amount == 0 ) {
        if ( $order_info->order_amount == 0 ) {
            //不用付款            
            //执行付款后的操作            
            $orderInfo = $order_info;
            $model->setOrder_id( $orderInfo->order_id );
            $trade_no = '';
            $total_fee = $orderInfo->order_amount;
            $pay_time = $orderInfo->create_time;
            //记录订单支付日志        
            $entity_PayLog_base = new \base\entity\PayLog();
            $entity_PayLog_base->uid = $orderInfo->uid;
            $entity_PayLog_base->order_id = $orderInfo->order_id;
            $entity_PayLog_base->trade_no = $trade_no;
            $entity_PayLog_base->trade_vendor = service_Order_base::trade_vendor_alipay;
            $entity_PayLog_base->trade_fee = $total_fee;
            $entity_PayLog_base->pay_time = $pay_time;
            $entity_PayLog_base->pay_type = 0;
            $entity_PayLog_base->buyer_id = '';
            $entity_PayLog_base->buyer_email = '';
            $entity_PayLog_base->pay_class = service_Order_base::pay_class_wap;
            $entity_PayLog_base->order_note = '支付成功';
            $entity_PayLog_base->pay_status = service_Order_base::pay_status_success;
            $model->insertPayLog( $entity_PayLog_base );

            $model->setTrade_vendor( service_Order_base::trade_vendor_weixin );
            $model->setTrade_no( $trade_no );
            $model->setEntity_OrderInfo( $orderInfo );
            $model->setPay_time( $pay_time );
            $res = $model->orderPaySuccess();
            $url = MOBILE_URL . 'order/success?sn=' . $order_info->order_sn;
            $this->headerRedirect( $url );
            return true;
        }
        /**
         * if ( $order_info->uid <> $this->memberInfo->uid ) {
         * $this->redirect( '只能付自己的订单哟' );
         * }
         */
        $model->setOrder_id( $order_info->order_id );
        $order_subject = $model->getOrderSubject();
        $total_amount = $order_info->order_amount;

        /**
         * //TODO 生成web pay页面中扫码支付的
         * require_once Tmac::findFile( 'payment/wechatpay/lib/WxPay.Api', APP_WWW_NAME, '.php' );
         * require_once Tmac::findFile( 'payment/wechatpay/unit/WxPay.NativePay', APP_WWW_NAME, '.php' );
         * //模式一
         * try {
         * $notify = new NativePay();
         * $qrcode_url = $notify->GetPrePayUrl( $order_sn );
         * } catch (WxPayException $exc) {
         * $qrcode_url = '';
         * die( $exc->getMessage() );
         * }
         * $array[ 'qrcode_url' ] = $qrcode_url;
         * //将来挪到web 的order pay页面中
         *
         */
        $array[ 'order_subject' ] = $order_subject;
        $array[ 'total_amount' ] = $total_amount;
        $array[ 'order_sn' ] = $order_sn;
        $this->assign( $array );
//		 echo '<pre>';
//       print_r($array);
        $this->V( 'order_payment' );
    }

    /**
     * 订单成功页面
     */
    public function success()
    {
        $order_sn = Input::get( 'sn', 0 )->required( '订单号不能为空' )->bigint(); //卖家uid        
        if ( Filter::getStatus() === false ) {
            $this->redirect( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }
        $login_status = $this->checkLoginStatus();
        if ( $login_status == false ) {
            parent::headerRedirect( MOBILE_URL . 'order/cart' );
        }

        $model = new service_order_Payment_mobile();
        $order_info = $model->getOrderInfoBySN( $order_sn );
        if ( !$order_info ) {
            parent::no( '订单不存在' );
        }
        if ( $order_info->uid <> $this->memberInfo->uid ) {
            parent::headerRedirect( MOBILE_URL . 'member/home' );
        }
        $model->setOrder_id( $order_info->order_id );
        $order_subject = $model->getOrderSubject();
        $total_amount = $order_info->order_amount;

        $array[ 'order_subject' ] = $order_subject;
        $array[ 'total_amount' ] = $total_amount;
        $array[ 'order_sn' ] = $order_sn;
        $array[ 'order_info' ] = $order_info;
//      echo '<pre>';
//      print_r( $array );
//      echo '</pre>';
        $this->assign( $array );
        $this->V( 'order_success' );
    }

    /**
     * 订单失败页面
     */
    public function fail()
    {
        $order_sn = Input::get( 'sn', 0 )->required( '订单号不能为空' )->bigint(); //卖家uid        
        $error_message = Input::get( 'message', '' )->string(); //卖家uid        
        if ( Filter::getStatus() === false ) {
            $this->redirect( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }
        $login_status = $this->checkLoginStatus();
        if ( $login_status == false ) {
            parent::headerRedirect( MOBILE_URL . 'order/cart' );
        }

        $model = new service_order_Payment_mobile();
        $order_info = $model->getOrderInfoBySN( $order_sn );
        if ( !$order_info ) {
            parent::no( '订单不存在' );
        }
        if ( $order_info->uid <> $this->memberInfo->uid ) {
            parent::headerRedirect( MOBILE_URL . 'member/home' );
        }
        $model->setOrder_id( $order_info->order_id );
        $order_subject = $model->getOrderSubject();
        $total_amount = $order_info->order_amount;

        $array[ 'order_subject' ] = $order_subject;
        $array[ 'total_amount' ] = $total_amount;
        $array[ 'order_sn' ] = $order_sn;
        $array[ 'order_info' ] = $order_info;
        $array[ 'error_message' ] = $error_message;
        $this->assign( $array );
        $this->V( 'order_fail' );
    }

    /**
     * 取购物车总数
     */
    public function get_cart_count()
    {
        //登录状态（收货地址）
        $login_status = $this->checkLoginStatus();
        if ( $login_status ) {
            $uid = $this->memberInfo->uid;
        } else {
            $uid = 0;
        }
        $model = new service_order_Cart_mobile();
        $model->setUid( $uid );
        $cart_count = $model->getCartCount();
        $this->apiReturn( $cart_count );
    }

    /**
     * 检测订单是否已经支付
     * 用于用户在支付的时候。
     * 订单支付成功页面轮循环调用使用
     */
    public function get_payment_status()
    {
        $order_sn = Input::get( 'sn', 0 )->required( '订单号不能为空' )->bigint(); //卖家uid        
        if ( Filter::getStatus() === false ) {
            $this->redirect( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }
        $login_status = $this->checkLoginStatus();
        if ( $login_status == false ) {
            parent::headerRedirect( MOBILE_URL . 'order/cart' );
        }

        $model = new service_order_Payment_mobile();
        $model->setUid( $this->memberInfo->uid );
        $order_info = $model->getOrderPaymentStatus( $order_sn );
        if ( $order_info === false ) {
            $this->redirect( $model->getErrorMessage() );
        }
        $this->apiReturn( $order_info );
    }

    /**
     * 取款支付订单总数
     */
    public function get_unpay_order_count()
    {
        $login_status = $this->checkLoginStatus();
        if ( $login_status == false ) {
            throw new ApiException( '请先登录' );
        }
        //取出{待付款}
        $model = new service_member_Home_mobile();
        $model->setUid( $this->memberInfo->uid );
        $res = $model->getBuyerUnpayOrderCountArray();
        $this->apiReturn( $res );
    }

    /**
     * 登录成功后 批量清一下用户购物车中的商品重复
     */
    public function cart_clean_repeat()
    {
        $login_status = $this->checkLoginStatus();
        if ( $login_status == false ) {
            throw new ApiException( '请先登录，亲' );
        }
        $model = new service_order_Cart_mobile();
        $model->setUid( $this->memberInfo->uid );

        //取出购物车里的数据展示出来。做为下单时前的确认，如果没问题。下一步就提交到订单表中
        try {
            $model->cleanRepeat();
            $this->apiReturn();
        } catch ( TmacClassException $exc ) {
            throw new ApiException( $exc->getMessage() );
        }
    }

}
