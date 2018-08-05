<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: DFHAgent.class.php 735 2016-12-08 03:40:26Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace base\service\member;

class DFHAgent extends \service_Member_base
{

    protected $memberInfo;
    protected $memberArray;
    protected $memberMap;
    
    protected $children_uid_array = array();
    protected $children_agent_uid_array = array();

    function setMemberInfo( $memberInfo )
    {
        $this->memberInfo = $memberInfo;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 递归 
     * 找出一个代理uid的上级代理的方法，用来分润
     * 
     * $this->setMemberInfo($memberInfo);
     * $this->getPlayersParentsAgentUid();
     * 
     * distint city provice
     * 存在member_setting.dfh_agent_parent_uid中序列化了     
     * @param $uid 要查找的uid
     * 
     */
    public function getPlayersParentsAgentUid()
    {
        //第一级代理
        $first_dfh_agent_uid = $this->memberInfo->dfh_agent_uid;
        if ( empty( $first_dfh_agent_uid ) && $this->memberInfo->member_type <> parent::member_type_dfh_agent ) {
            return $this->dfh_agent_parent_array;
        }
        $this->getPlayersParentsAgentUidRecursion( $first_dfh_agent_uid );
        return $this->dfh_agent_parent_array;
    }

    /**
     * 递归取数据
     */
    private function getPlayersParentsAgentUidRecursion( $dfh_agent_uid )
    {
        //getMemberInfo方法 以后用Redis替换优化
        $agent_member_info = $this->getMemberInfoByUid( $dfh_agent_uid, 'uid,member_type,member_class,dfh_agent_uid' );
        if ( !empty( $agent_member_info ) && $agent_member_info->member_type == parent::member_type_dfh_agent ) {            
            $this->dfh_agent_parent_array[ $agent_member_info->member_class ] = $agent_member_info->uid;
            $this->getPlayersParentsAgentUidRecursion( $agent_member_info->dfh_agent_uid );
        }
        return true;
    }

    /**
     * 递归 
     * 找代理的所有下级玩家的方法  存在redis中备用，代理商后台中查询用得到。
     * 纯玩家。非代理
     * 只计算 代理的所有下级的下级玩家
     */
    public function getPlayersOfAgentUidArray()
    {
        $this->getPlayersOfAgentRecursion( $this->memberInfo->uid );
        return $this->children_uid_array;
    }

    /**
     * 递归取
     * 找代理的所有下级玩家的方法  存在redis中备用，代理商后台中查询用得到。
     * 纯玩家。非代理
     * 只计算 代理的所有下级的下级玩家
     */
    public function getPlayersOfAgentRecursion( $children_uid )
    {
        //getMemberInfo方法 以后用Redis替换优化
        $children_member_array = $this->getMemberChildrenArray( $children_uid );
        if ( !empty( $children_member_array ) ) {
            foreach ( $children_member_array as $memberInfo ) {
                $this->children_uid_array[ $memberInfo->uid ] = $memberInfo->uid;
                $this->getPlayersOfAgentRecursion( $memberInfo->uid );
            }
        }
        return true;
    }

    private function getMemberChildrenArray( $agent_Uid )
    {
        $dao = \dao_factory_base::getMemberDao();
        $where = "agent_uid={$agent_Uid}";
        $field = 'uid';
        $dao->setWhere( $where );
        $dao->setField( $field );
        return $dao->getListByWhere();
    }
    
    /**
     * 递归 
     * 找代理的所有下级代理
     * 纯玩家。非代理
     * 只计算 代理的所有下级的下级玩家
     */
    public function getChildrenAgentUidArray()
    {
        $this->getChildrenAgentRecursion( $this->memberInfo->uid );
        return $this->children_agent_uid_array;
    }

    /**
     * 递归取
     * 找代理的所有下级玩家的方法  存在redis中备用，代理商后台中查询用得到。
     * 纯玩家。非代理
     * 只计算 代理的所有下级代理
     */
    public function getChildrenAgentRecursion( $dfh_agent_uid )
    {
        //getMemberInfo方法 以后用Redis替换优化
        $children_member_array = $this->getMemberChildrenAgentArray( $dfh_agent_uid );
        if ( !empty( $children_member_array ) ) {
            foreach ( $children_member_array as $memberInfo ) {
                $this->children_agent_uid_array[ $memberInfo->uid ] = $memberInfo->uid;
                $this->getChildrenAgentRecursion( $memberInfo->uid );
            }
        }
        return true;
    }

    private function getMemberChildrenAgentArray( $dfh_agent_uid )
    {
        $dao = \dao_factory_base::getMemberDao();
        $where = "member_type=".parent::member_type_dfh_agent." AND dfh_agent_uid={$dfh_agent_uid}";
        $field = 'uid';
        $dao->setWhere( $where );
        $dao->setField( $field );
        return $dao->getListByWhere();
    }

}
