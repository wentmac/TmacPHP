<?php

/**
 * 订单售后 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Index.class.php 767 2016-12-19 17:57:47Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace base\service\agent;

class Index extends \service_Member_base
{

    protected $where;
    protected $query_string;
    protected $pagesize;
    protected $uid;
    protected $agent_uid;
    protected $query;

    function setWhere( $where )
    {
        $this->where = $where;
    }

    function setQuery_string( $query_string )
    {
        $this->query_string = $query_string;
    }

    function setPagesize( $pagesize )
    {
        $this->pagesize = $pagesize;
    }

    function setUid( $uid )
    {
        $this->uid = $uid;
    }

    function setAgent_uid( $agent_uid )
    {
        $this->agent_uid = $agent_uid;
    }

    function setQuery( $query )
    {
        $this->query = $query;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 检测权限
     * @param type $uid
     * @param type $child_uid
     * @return boolean
     */
    public function checkChildAgentPurview( $uid, $child_uid )
    {
        if ( $uid == $child_uid ) {
            return true;
        }
        $child_member_setting = $this->getMemberSettingInfoByUid( $child_uid );
        if ( $child_member_setting->member_type <> parent::member_type_dfh_agent ) {
            return $this->checkChildPurview( $uid, $child_uid );
        }
        $dfh_agent_parent_uid_array = json_decode( $child_member_setting->dfh_agent_parent_uid, true );
        if ( empty( $dfh_agent_parent_uid_array ) ) {
            return false;
        }
        foreach ( $dfh_agent_parent_uid_array as $agent_uid ) {
            if ( $agent_uid == $uid ) {
                return true;
            }
        }
        return false;
    }

    /**
     * 检测下级玩家权限
     * @param type $uid
     * @param type $player_uid
     * @return boolean
     */
    public function checkChildPurview( $uid, $player_uid )
    {
        if ( $uid == $player_uid ) {
            return true;
        }
        $member_level_dao = \dao_factory_base::getMemberLevelDao();

        $where = "uid={$player_uid} AND agent_uid={$uid} AND agent_uid_recent={$uid} AND member_level_type=" . parent::member_level_type_recommend;
        $count = $member_level_dao->setWhere( $where )->getCountByWhere();     

        if ( $count ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 取下级代理的列表
     * $this->where;
     * $this->getBuyerOrderList();
     */
    public function getAgentList()
    {
        $member_level_dao = \dao_factory_base::getMemberLevelDao();

        if ( empty( $this->agent_uid ) ) {
            $where = "1";
        } else {
            $where = "agent_uid={$this->agent_uid} AND member_level_type=" . parent::member_level_type_agent;
        }
        $member_level_dao->setWhere( $where );
        $count = $member_level_dao->getCountByWhere();

        if ( $count === false ) {
            $retmsg = 0;
        } else {
            $retmsg = 1; //业务返回信息
        }

        $member_array = array();
        if ( $count > 0 ) {
            $member_level_in_sql = $member_level_dao->setField( 'uid' )->setWhere( $where )->getSqlByWhere();

            $member_dao = \dao_factory_base::getMemberDao();
            $member_setting_dao = \dao_factory_base::getMemberSettingDao();

            $member_dao->join( $member_setting_dao->getTable(), $member_dao->getTable() . '.uid=' . $member_setting_dao->getTable() . '.uid', 'INNER' );
            $member_dao->setField( "{$member_dao->getTable()}.uid,mobile,username,{$member_dao->getTable()}.nickname,{$member_dao->getTable()}.member_type,{$member_dao->getTable()}.member_class,dfh_agent_uid,current_fh_currency,history_fh_currency,voucher_money,agent_province,agent_city,agent_district,agent_address" );
            $res = $member_dao->setWhere( $member_dao->getTable() . ".uid IN({$member_level_in_sql})" )->getListByWhere();
            //echo '<Pre>';
            //print_r($res);die;
            foreach ( $res as $value ) {
                $member_array[ $value->dfh_agent_uid ][] = $value;
            }
        }
        $retHeader = array(
            'totalput' => $count,
        );
        $return = array(
            'retHeader' => $retHeader,
            'retcode' => 'buyer_order_list',
            'retmsg' => $retmsg,
            'reqdata' => $member_array,
        );
        return $return;
    }

    /**
     * 取下级玩家的列表
     * $this->where;
     * $this->getBuyerOrderList();
     */
    public function getPlayerList()
    {
        $member_dao = \dao_factory_base::getMemberDao();
        $member_setting_dao = \dao_factory_base::getMemberSettingDao();
        $member_level_dao = \dao_factory_base::getMemberLevelDao();

        if ( empty( $this->agent_uid ) ) {
            return true;
        }
        $where = "agent_uid={$this->agent_uid} AND member_level_type=" . parent::member_level_type_recommend . " AND agent_uid_recent={$this->agent_uid}";
        $member_level_in_sql = $member_level_dao->setWhere( $where )->setField( 'uid' )->getSqlByWhere();

        $user_type = parent::member_type_buyer;
        $count_where = "uid IN({$member_level_in_sql}) "
                . "AND member_type={$user_type}";
        if ( !empty( $this->query ) ) {
            $count_where .= " AND uid={$this->query}";
        }
        $count = $member_setting_dao->setWhere( $count_where )->getCountByWhere();

        if ( $count === false ) {
            $retmsg = 0;
        } else {
            $retmsg = 1; //业务返回信息
        }
        $pages = $this->P( 'Pages' );
        $pages->setTotal( $count );
        $pages->setPrepage( $this->pagesize );
        $limit = $pages->getSqlLimit();

        $member_array = array();
        if ( $count > 0 ) {
            $member_dao->join( $member_setting_dao->getTable(), $member_dao->getTable() . '.uid=' . $member_setting_dao->getTable() . '.uid', 'INNER' );
            $member_dao->setField( "{$member_dao->getTable()}.uid,{$member_dao->getTable()}.reg_time,mobile,agent_uid,username,{$member_dao->getTable()}.nickname,{$member_dao->getTable()}.member_type,{$member_dao->getTable()}.member_class,dfh_agent_uid,current_fh_currency,history_fh_currency,voucher_money,fh_exchange_amount,fh_recharge_amount" );

            $sort = 'uid DESC';
            $where = $member_dao->getTable() . ".uid IN({$member_level_in_sql}) "
                    . "AND {$member_setting_dao->getTable()}.member_type={$user_type}";
            if ( !empty( $this->query ) ) {
                $where .= " AND {$member_dao->getTable()}.uid={$this->query}";
            }
            $res = $member_dao->setWhere( $where )->setOrderby( $sort )->setLimit( $limit )->getListByWhere();
            //echo '<Pre>';
            //print_r($res);die;
            foreach ( $res as $value ) {
                $value->reg_time = date( 'Y-m-d H:i:s', $value->reg_time );
                $member_array[] = $value;
            }
        }
        $retHeader = array(
            'totalput' => $count,
            'totalpg' => intval( ceil( $count / $this->pagesize ) ),
            'pagesize' => $this->pagesize,
            'page' => $pages->getNowPage()
        );
        $return = array(
            'retHeader' => $retHeader,
            'retcode' => 'player_list',
            'retmsg' => $retmsg,
            'reqdata' => $member_array,
        );
        return $return;
    }

}
