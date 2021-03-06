<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Coupon.class.php 325 2016-05-31 10:07:35Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class service_Coupon_base extends service_Model_base
{

    const coupon_status_unused = 0;
    const coupon_status_used = 1;

    protected $errorMessage;
    protected $memberInfo;
    protected $mall_uid;

    function setMemberInfo( $memberInfo )
    {
        $this->memberInfo = $memberInfo;
    }

    function setMall_uid( $mall_uid )
    {
        $this->mall_uid = $mall_uid;
    }

    function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function __construct()
    {
        parent::__construct();
    }

}
