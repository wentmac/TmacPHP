<?php

/**
 * WEB后台 Controller父类 模块 Controller
 * 初始化系统中每个用户的agent_uid_parent
 * 并写入 yph_member_level表中
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Level.class.php 735 2016-12-08 03:40:26Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace base\service\member;

use Tmac;
use dao_factory_base;

class Level extends \service_Member_base
{

    private $memberMap;
    private $memberAgentMap;
    private $agent_parents_array;
    private $agent_dfh_parents_array;

    /**
     * 初始化变量　定义私有变量
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * $all_member_array = Tmac::getCache('all_member', array($this, 'getAllMember'), array(0), 3600); 
     * 取所有的会员
     * @return type
     */
    public function getAllMember()
    {
        $dao = dao_factory_base::getMemberDao();
        $dao->setField( 'uid,agent_uid' );
        $where = "1";
        $dao->setWhere( $where );
        $dao->setOrderby( 'uid ASC' );
        $res = $dao->getListByWhere();
        $member_map = array();
        foreach ( $res as $value ) {
            $member_map[ $value->uid ] = $value->agent_uid;
        }
        $this->memberMap = $member_map;
        return $member_map;
    }

    /**
     * 取父级直推数组
     */
    public function getAgentParentsArray( $uid )
    {
        $all_member_array = Tmac::getCache( 'all_member', array( $this, 'getAllMember' ), array( 0 ), 3600 );
        $this->memberMap = $all_member_array;
        $this->agent_parents_array = array();
        $this->getAgentParentsRecursion( $uid );
        return $this->agent_parents_array;
    }

    /**
     * 递归取数据
     */
    private function getAgentParentsRecursion( $uid )
    {
        if ( !empty( $this->memberMap[ $uid ] ) && $this->memberMap[ $uid ] <> $uid ) {
            $this->agent_parents_array[] = $this->memberMap[ $uid ];
            $this->getAgentParentsRecursion( $this->memberMap[ $uid ] );
        }
        return true;
    }

    /**
     * $cat_list = Tmac::getCache('all_agent_member', array($this, 'getCategoryList'), array(0), 3600); 
     * 取所有的代理会员
     * @return type
     */
    public function getAllAgentMember()
    {
        $dao = dao_factory_base::getMemberDao();
        $dao->setField( 'uid,dfh_agent_uid' );
        $where = "member_type=" . parent::member_type_dfh_agent;
        $dao->setWhere( $where );
        $dao->setOrderby( 'uid ASC' );
        $res = $dao->getListByWhere();
        $member_map = array();
        foreach ( $res as $value ) {
            $member_map[ $value->uid ] = $value->dfh_agent_uid;
        }
        $this->memberAgentMap = $member_map;
        return $member_map;
    }

    /**
     * 取父级代理数组
     */
    public function getDFHAgentParentsArray( $uid )
    {
        $all_dfh_agent_member_array = Tmac::getCache( 'all_agent_member', array( $this, 'getAllAgentMember' ), array( 0 ), 3600 );
        $this->memberAgentMap = $all_dfh_agent_member_array;
        $this->agent_dfh_parents_array = array();
        $this->getDFHAgentParentsRecursion( $uid );
        return $this->agent_dfh_parents_array;
    }

    /**
     * 递归取数据
     */
    private function getDFHAgentParentsRecursion( $uid )
    {
        if ( !empty( $this->memberAgentMap[ $uid ] ) && $this->memberAgentMap[ $uid ] <> $uid ) {
            $this->agent_dfh_parents_array[] = $this->memberAgentMap[ $uid ];
            $this->getDFHAgentParentsRecursion( $this->memberAgentMap[ $uid ] );
        }
        return true;
    }

    /**
     * 注册会员时写入会员的直推上级数据
     * @param \base\entity\Member $entity_Member
     * @param \base\entity\MemberSetting $entity_MemberSetting
     */
    public function createMemberLevel( \base\entity\Member $entity_Member, \base\entity\MemberSetting $entity_MemberSetting )
    {
        
    }

    public function createMemberAgentLevel( \base\entity\Member $entity_Member, \base\entity\MemberSetting $entity_MemberSetting )
    {
        
    }

}
