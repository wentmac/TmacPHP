<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: MemberSeat.class.php 537 2014-12-05 17:21:08Z zhangwentao $
 * http://www.t-mac.org；
 */
class service_Message_base extends service_Model_base
{
    const private_key = 'liht8sy2b';
    /**
     * 留言类型
     * 卡
     */
    const message_type_card = 1;
    /**
     * 留言类型
     * 贷款
     */
    const message_type_loan = 2;

    protected $errorMessage;

    function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function __construct()
    {
        parent::__construct();
    }

    
}
