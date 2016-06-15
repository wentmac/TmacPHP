<?php

/**
 * api 会员账户 管理模块 Model
 * 单个订单商品退款
 * 整个订单在未发货前退款
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: MemberSeat.class.php 537 2014-12-05 17:21:08Z zhangwentao $
 * http://www.t-mac.org；
 */
class service_order_RefundDetail_admin extends service_order_RefundDetail_base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 处理
     * @return type
     */
    public function handleOrderRefundInfo()
    {
        $order_goods_array = unserialize( $this->order_refund_info->order_goods_detail );
        foreach ( $order_goods_array as $order_goods ) {
            $order_goods->goods_image_url = $this->getImage( $order_goods->goods_image_id, '110', 'goods' );
            unset( $order_goods->goods_image_id );
        }
        $this->order_refund_info->order_goods_detail = $order_goods_array;

        $refund_images_array = json_decode( $this->order_refund_info->refund_images );
        $refund_images_res = array();
        if ( $refund_images_array ) {
            foreach ( $refund_images_array as $value ) {
                $refund_images_res[] = $this->getImage( $value, '98x55', 'refund' );
            }
        }
        $this->order_refund_info->refund_images = $refund_images_res;
        $this->order_refund_info->refund_time = date( 'Y-m-d H:i:s', $this->order_refund_info->refund_time );

        $order_refund_array = Tmac::config( 'order.order.order_refund', APP_BASE_NAME );
        $this->order_refund_info->refund_service_status = $order_refund_array[ 'refund_service_status' ][ $this->order_refund_info->refund_service_status ];
        $this->order_refund_info->refund_service_reason = $order_refund_array[ 'refund_service_reason' ][ $this->order_refund_info->refund_service_reason ];
        $this->order_refund_info->supplier_status = ($this->uid == $this->order_refund_info->goods_uid) ? true : false;
        unset( $this->order_refund_info->service_level );
        return $this->order_refund_info;
    }

}
