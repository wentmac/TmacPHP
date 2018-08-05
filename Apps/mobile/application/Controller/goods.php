<?php

/**
 * mobile 购物车 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: goods.php 874 2017-02-03 03:20:03Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class goodsAction extends service_Controller_mobile
{

    public function __construct()
    {
        parent::__construct();
        $this->checkLogin();
    }

    public function index()
    {
        $goods_id = Input::get( 'id', 0 )->required( '商品不能为空' )->int();
        $agent_id = Input::get( 'agent', 0 )->int();

        if ( Filter::getStatus() === false ) {
            $this->redirect( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }

        if ( !empty( $agent_id ) ) {
            $expire_time = 86400 * 7;
            setcookie( 'agent_id', $agent_id, $this->now + $expire_time, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );
        }

        $login_status = $this->checkLoginStatus();
        if ( $login_status == true ) {
            $member_level = $this->memberInfo->member_level;
        } else {
            $member_level = 0;
        }

        $model = new service_Goods_mobile();
        $model->setGoods_id( $goods_id );
        $model->setMember_level( $member_level );
        $goods_info = $model->getGoodsInfo();

        if ( !$goods_info ) {
            parent::no( '商品不存在！' );
        } else {
            if ( empty( $goods_info->is_supplier ) ) {
                parent::no( '商品不是云端商品库产品' );
            }
            if ( $goods_info->is_delete == 1 ) {
                parent::no( '商品已经删除' );
            }
        }
        //兑换类 商品。（兑换红包|兑换购物券）不能访问购买页面|（兑换商品）才能访问
        if ( $goods_info->goods_type == service_Goods_base::goods_type_exchange && $goods_info->goods_class <> service_Goods_base::goods_class_exchange_goods ) {
            parent::no( '亲，出错了。您访问的是非兑换商品' );
        }
        $goods_info->goods_image_id = $this->getImage( $goods_info->goods_image_id, '640x0', 'goods' );
        $goods_info->goods_desc = preg_replace( '/(width|height)="(\d+)"/', '', $goods_info->goods_desc );
        $goods_info->goods_desc = str_replace( 'width: 750px;', '', $goods_info->goods_desc );
        $item_info = $model->getItemId( $goods_info );
        $goods_info->item_id = $item_info->item_id;

        $goods_spec_array = $model->getGoodsSpecArray();
        $goods_sku_array = $model->getGoodsSkuArray();

        $goods_image_array = $model->getGoodsImageArray( $goods_id );

        if ( $goods_info->goods_type == service_Goods_base::goods_type_exchange && $goods_info->goods_class == service_Goods_base::goods_class_exchange_goods ) {
            $exchange_goods = true;
            $buy_now_button = '立即抽奖';
        } else {
            $exchange_goods = false;
            $buy_now_button = '立即购买';
        }

        //商品规格
        $array[ 'goods_spec_array' ] = json_encode( $goods_spec_array[ 'result_object' ], true );
        //商品规格对应的sku 价格/库存
        $array[ 'goods_sku_array' ] = json_encode( $goods_sku_array, true );
        $array[ 'goods_info' ] = $goods_info;
        $array[ 'goods_image_array' ] = $goods_image_array;
        $array[ 'exchange_goods' ] = $exchange_goods;
        $array[ 'buy_now_button' ] = $buy_now_button;

//           echo '<pre>';
//           print_r( $array );
//         echo "</pre>";
//        die;

        $this->assign( $array );
        if ( $exchange_goods ) {
            $this->V( 'goods_exchange' );
        } else {
            $this->V( 'goods' );
        }
    }

}
