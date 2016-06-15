<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: MemberSeat.class.php 537 2014-12-05 17:21:08Z zhangwentao $
 * http://www.t-mac.org；
 */
class service_Message_mobile extends service_Message_base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 留言保存接口
     * @param entity_Message_base $entity_Message_base
     */
    public function createMessage( entity_Message_base $entity_Message_base )
    {
        include Tmac::findFile( 'Des', APP_MOBILE_NAME );
        $des = new Des();
        $des->setKey( service_Message_base::private_key );
        $string = substr( $entity_Message_base->mobile, 7, 4 );
        $entity_Message_base->mobile = substr( $entity_Message_base->mobile, 0, 7 ) . $des->encode( $string );
        $entity_Message_base->ip = Functions::get_client_ip();
        $entity_Message_base->message_time = $this->now;
        $check = $this->checkRepeat( $entity_Message_base->mobile );
        if ( $check == false ) {
            return FALSE;
        }
        $dao = dao_factory_base::getMessageDao();
        return $dao->insert( $entity_Message_base );
    }

    private function checkRepeat( $mobile )
    {
        $dao = dao_factory_base::getMessageDao();
        $time = $this->now - 86400;
        $where = "mobile='{$mobile}' AND message_time>={$time}";
        $dao->setWhere( $where );
        $count = $dao->getCountByWhere();
        if ( $count > 0 ) {
            $this->errorMessage = '亲，24小时内只能留言一次哟';
            return false;
        }
        return true;
    }

}
