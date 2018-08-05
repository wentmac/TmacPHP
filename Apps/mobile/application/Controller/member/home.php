<?php

/**
 * 前台 首页 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: home.php 408 2016-08-08 06:44:47Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class homeAction extends service_Controller_mobile
{

    public function __construct()
    {
        parent::__construct();
        $this->checkLogin();
        $this->assign( 'member_info', $this->memberInfo );
//		echo '<pre>';
//      print_r( $this->memberInfo );
//         echo '</pre>';
    }

    /**
     * 会员中心首页
     */
    public function index()
    {
        //取出{待付款｜待发货｜待收货｜已完成}
        $model = new service_member_Home_mobile();
        $model->setUid( $this->memberInfo->uid );
        $res = $model->getBuyerOrderCountArray();

        /**
        $member_level_array = Tmac::config( 'goods.goods.goods_member_level', APP_BASE_NAME );
        $platinum_member_level_array = Tmac::config( 'goods.goods.goods_platinum_member', APP_BASE_NAME );

        if ( empty( $member_level_array[ $this->memberInfo->member_level ] ) ) {
            $member_level = '还不是会员哟';
        } else {
            if ( $this->memberInfo->member_class == service_Member_base::member_class_platinum ) {
                $member_level = empty( $platinum_member_level_array[ $this->memberInfo->member_level ] ) ? $this->memberInfo->member_level : $platinum_member_level_array[ $this->memberInfo->member_level ];
            } else {
                $member_level = $member_level_array[ $this->memberInfo->member_level ];
            }
        }
         */
        $member_level = '';
        $is_agent = false;
        if ( $this->memberInfo->member_type == service_Member_base::member_type_dfh_agent ) {
            $member_level = '[代理商]';
            $is_agent = true;
        }

        $memberInfo = array(
            'uid' => $this->memberInfo->uid,
            'username' => empty( $this->memberInfo->nickname ) ? '暂无' : $this->memberInfo->nickname,
            'member_avatar_url' => empty( $this->memberInfo->member_image_id ) ? STATIC_URL . APP_MOBILE_NAME . '/default/image/photo.jpg' : $this->memberInfo->member_image_id,
            'mobile' => $this->memberInfo->mobile,
            'member_level' => $member_level
        );
        $array[ 'homeinfo' ] = $res;
        $array[ 'memberInfo' ] = $memberInfo;
        $array[ 'is_agent' ] = $is_agent;
//       echo '<pre>';
//       print_r( $array );
//       echo '</pre>';
//      die;                
        $this->assign( $array );
        $this->V( 'member/home_index' );
    }

}
