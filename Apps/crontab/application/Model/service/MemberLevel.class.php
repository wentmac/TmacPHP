<?php

/**
 * WEB后台 Controller父类 模块 Controller
 * 初始化系统中每个用户的agent_uid_parent
 * 并写入 yph_member_level表中
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: MemberLevel.class.php 905 2017-02-23 16:55:45Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace crontab\service;

use base\service\member\Level;

class MemberLevel extends Level
{

    /**
     * 初始化变量　定义私有变量
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 更新所有用户的下家
     */
    public function updateMmeberAgentUidParents()
    {
        return true;
        $member_setting_dao = \dao_factory_base::getMemberSettingDao();
        $dao = \dao_factory_base::getMemberDao();
        $dao->setField( 'uid' );
        $where = 'uid IN(63,68,7478,3489,7667,12189,15583,16279,187,56,197,1969,211,16017,394,11230,16054,9094,2277,15014,5297,10778,3400,7736,12673,14429,10415,13596,13458,16606,13190)';
        $dao->setWhere( $where );
        //$dao->setLimit( 100 );
        $res = $dao->getListByWhere();
        $this->_outpot( '开始总数：' . count( $res ) );
        foreach ( $res as $memberInfo ) {
            $uid = $memberInfo->uid;
            $agent_uid_array = $this->getAgentParentsArray( $uid );
            $this->_outpot( $uid . '|' . var_export( $agent_uid_array, true ) );
            if ( !empty( $agent_uid_array ) ) {

                $dao->getDb()->startTrans();


                $entity_MemberSetting = new \base\entity\MemberSetting();
                $entity_MemberSetting->agent_uid_parent = implode( ',', $agent_uid_array );

                $member_setting_dao->setWhere( "uid={$uid}" );
                $member_setting_dao->updateByWhere( $entity_MemberSetting );


                $this->insertMemberLevel( $uid, $agent_uid_array, parent::member_level_type_recommend );
                if ( $dao->getDb()->isSuccess() ) {
                    $dao->getDb()->commit();
                } else {
                    $dao->getDb()->rollback();
                    $this->_outpot( '失败：' . var_export( $agent_uid_array, true ) );
                }
            }
        }
    }

    /**
     * 更新所有代理的下家
     */
    public function updateMmeberDFHAgentParentsUid()
    {
        $member_setting_dao = \dao_factory_base::getMemberSettingDao();
        $dao = \dao_factory_base::getMemberDao();
        $dao->setField( 'uid' );
        $where = 'member_type=' . parent::member_type_dfh_agent;
        $dao->setWhere( $where );
        $res = $dao->getListByWhere();
        $this->_outpot( '开始总数：' . count( $res ) );
        foreach ( $res as $memberInfo ) {
            $uid = $memberInfo->uid;
            $dfh_agent_parent_uid_array = $this->getDFHAgentParentsArray( $uid );
            $this->_outpot( $uid . '|' . var_export( $dfh_agent_parent_uid_array, true ) );
            if ( !empty( $dfh_agent_parent_uid_array ) ) {
                $dao->getDb()->startTrans();


                $entity_MemberSetting = new \base\entity\MemberSetting();
                $entity_MemberSetting->dfh_agent_parent_uid = implode( ',', $dfh_agent_parent_uid_array );

                $member_setting_dao->setWhere( "uid={$uid}" );
                $member_setting_dao->updateByWhere( $entity_MemberSetting );

                $this->insertMemberLevel( $uid, $dfh_agent_parent_uid_array, parent::member_level_type_agent );
                if ( $dao->getDb()->isSuccess() ) {
                    $dao->getDb()->commit();
                } else {
                    $dao->getDb()->rollback();
                    $this->_outpot( '失败：' . var_export( $dfh_agent_parent_uid_array, true ) );
                }
            }
        }
    }

    private function insertMemberLevel( $uid, $agent_uid_array, $member_level_type )
    {
        if ( empty( $agent_uid_array ) ) {
            return true;
        }
        $dao = \dao_factory_base::getMemberLevelDao();

        $values = '';
        foreach ( $agent_uid_array AS $agent_uid ) {
            $values .= ",({$uid},{$agent_uid},{$member_level_type})";
        }
        $values = substr( $values, 1 );
        $sql = "INSERT INTO {$dao->getTable()} (`uid`,`agent_uid`,`member_level_type`) VALUES {$values};";
        return $dao->getDb()->execute( $sql );
    }

    private function _outpot( $message )
    {
        if ( empty( $message ) ) {
            return false;
        }
        $message .= "\n";
        echo date( 'Y-m-d H:i:s' ) . "\t" . $message;
        ob_flush();
    }

    /**
     * 临时更新代理的member_level和member_setting.member
     */
    public function resetAgentMember()
    {
        //查出所有的代理
        $dao = \dao_factory_base::getMemberDao();
        $where = "member_type=" . parent::member_type_dfh_agent;
        $res = $dao->setWhere( $where )->getListByWhere();

        $model = new \service_Member_admin();
        foreach ( $res as $memberInfo ) {
            $uid = $memberInfo->uid;
            $model->setUid( $uid );
            $entity_Member_base = new \base\entity\Member();
            $entity_Member_base->member_type = $memberInfo->member_type;
            $entity_Member_base->member_class = $memberInfo->member_class;
            $entity_Member_base->dfh_agent_uid = $memberInfo->dfh_agent_uid;
            $entity_MemberSetting_base = new \base\entity\MemberSetting();

            $this->_outpot( "开始执行:{$uid}" );
            $res = $model->updateMemberInfoByAdmin( $entity_Member_base, $entity_MemberSetting_base );
            $this->_outpot( "{$uid},执行结果:" . var_export( $res, true ) );
        }
    }

    /**
     * 临时更新代理的member_level表中的member_level字段
     */
    public function resetAgentMemberLevel()
    {
        //查出所有的代理
        $member_level_dao = \dao_factory_base::getMemberLevelDao();
        $dao = \dao_factory_base::getMemberSettingDao();
        $res = $dao->setField( 'uid,agent_uid_parent' )->getListByWhere();

        $entity_MemberLevel_base = new \base\entity\MemberLevel();
        foreach ( $res as $memberSettingInfo ) {
            if ( empty( $memberSettingInfo->agent_uid_parent ) ) {
                continue;
            }
            $agent_uid_array = explode( ',', $memberSettingInfo->agent_uid_parent );
            $member_level = 1;
            foreach ( $agent_uid_array as $agent_uid ) {
                $entity_MemberLevel_base->member_level = $member_level;
                $where = "uid={$memberSettingInfo->uid} AND agent_uid={$agent_uid} AND member_level_type=" . parent::member_level_type_recommend . " AND member_level=0";
                $res = $member_level_dao->setWhere( $where )->updateByWhere( $entity_MemberLevel_base );
                Model::output( "{$memberSettingInfo->uid},执行结果:" . var_export( $res, true ) );
                $member_level++;
            }
            Model::output( '----------------------------------------------------------------' );
        }
    }

    /**
     * 更新代理所有直推下级的第一级直推代理
     * @param type $agent_uid
     * @return boolean
     */
    public function resetDFHAgentChildMemberAgentRecentUid()
    {
        //查出所有直推下级
        $member_setting_dao = \dao_factory_base::getMemberSettingDao();

        $where = "member_type=" . parent::member_type_dfh_agent;
        $member_setting_array = $member_setting_dao->setWhere( $where )->setField( 'uid' )->getListByWhere();
        foreach ( $member_setting_array as $member_setting ) {
            Model::output( "开始执行：agent_uid={$member_setting->uid}----------------------------------------------------------------" );
            $this->updateDFHAgentChildMemberAgentRecentUid( $member_setting->uid );
        }
    }

    /**
     * 更新代理所有直推下级的第一级直推代理
     * @param type $agent_uid
     * @return boolean
     */
    private function updateDFHAgentChildMemberAgentRecentUid( $agent_uid )
    {
        //查出所有直推下级
        $member_setting_dao = \dao_factory_base::getMemberSettingDao();
        $member_level_dao = \dao_factory_base::getMemberLevelDao();
        $where = "agent_uid={$agent_uid} AND member_level_type=" . parent::member_level_type_recommend;
        $member_level_array = $member_level_dao->setWhere( $where )->setField( 'uid' )->getListByWhere();
        
        //加入代理本身自己的修改 取消代理时，自己的上级代理变更
        $self_member_object = new \stdClass();
        $self_member_object->uid = $agent_uid;        
        $member_level_array[] = $self_member_object;
        if ( empty( $member_level_array ) ) {
            Model::output( "uid:{$agent_uid}|没有下级，跳过" );
            return true;
        }
        foreach ( $member_level_array as $member_level ) {
            $member_setting_info = $this->getMemberSettingInfoByUid( $member_level->uid, 'member_type,agent_uid_parent,agent_uid_recent' );
            if ( $member_setting_info->member_type == parent::member_type_dfh_agent ) {
                Model::output( "uid:{$agent_uid}|{$member_level->uid} 是代理，代理是不需要设置agent_uid_recent的，跳过" );
                continue;
            }
            if ( !empty( $member_setting_info->agent_uid_recent ) ) {
                Model::output( "uid:{$agent_uid}|{$member_level->uid} 已经存在 agent_uid_recent，跳过" );
                continue;
            }

            $where = $member_setting_dao->getWhereInStatement( 'uid', $member_setting_info->agent_uid_parent ) . " AND member_type=" . parent::member_type_dfh_agent;
            $dfh_agent_uid_array = $member_setting_dao->setWhere( $where )->setField( 'uid' )->getListByWhere();
            if ( empty( $dfh_agent_uid_array ) ) {
                Model::output( "uid:{$agent_uid}|{$member_level->uid} 不存在上级，跳过" );
                continue;
            }
            $dfh_agent_uid_arr = [ ];
            foreach ( $dfh_agent_uid_array as $member ) {
                $dfh_agent_uid_arr[] = $member->uid;
            }

            //存在上级代理
            $agent_uid_parent_array = explode( ',', $member_setting_info->agent_uid_parent );
            $agent_uid_final = 0;
            foreach ( $agent_uid_parent_array as $agent_uid_parent ) {
                if ( in_array( $agent_uid_parent, $dfh_agent_uid_arr ) ) {
                    $agent_uid_final = $agent_uid_parent;
                    break;
                }
            }

            if ( empty( $agent_uid_final ) ) {
                Model::output( "不存在 agent_uid_final 跳过" );
                continue;
            }

            //需要更新 新的agent_uid排位比原来的靠前
            $entity_MemberSetting = new \base\entity\MemberSetting();
            $entity_MemberSetting->agent_uid_recent = $agent_uid_final;
            $where = "uid={$member_level->uid}";
            $member_setting_dao->setWhere( $where )->updateByWhere( $entity_MemberSetting );

            $entity_MemberLevel = new \base\entity\MemberLevel();
            $entity_MemberLevel->agent_uid_recent = $agent_uid_final;
            $member_level_where = "uid={$member_level->uid} AND member_level_type=" . parent::member_level_type_recommend;
            $member_level_dao->setWhere( $member_level_where )->updateByWhere( $entity_MemberLevel );
            Model::output( "uid:{$member_level->uid}|agent_uid_recent={$agent_uid_final}" );
        }
        return true;
    }

    /**
     * 取异常的数据
     */
    public function checkErrorAgentUid()
    {
        $member_dao = \dao_factory_base::getMemberDao();
        $member_setting_dao = \dao_factory_base::getMemberSettingDao();

        $member_dao->join( $member_setting_dao->getTable(), $member_dao->getTable() . '.uid=' . $member_setting_dao->getTable() . '.uid', 'INNER' );
        $member_dao->setField( "{$member_dao->getTable()}.uid,agent_uid,agent_uid_parent" );

        $where = "agent_uid>=1";
        $res = $member_dao->setWhere( $where )->getListByWhere();
        //echo '<Pre>';
        //print_r($res);die;
        $return = [ ];
        foreach ( $res as $value ) {
            $agent_uid_parent = explode( ',', $value->agent_uid_parent );
            if ( $agent_uid_parent[ 0 ] <> $value->agent_uid ) {
                Model::output( var_export( $value, true ) );
                $return[] = $value->uid;
            }
        }
        Model::output( implode( ',', $return ) );
    }

    /**
     * 消费 修改或取消 用户代理身份时。下级直推的member_setting.agent_uid_recent的变更
     */
    public function consumeResetAgentUidRecent()
    {
        $redis = \CacheDriver::getInstance( 'Redis', 'default' );
        $success = $fail = 0;
        while ( true ) {
            $len = $redis->getRedis()->lLen( parent::reset_agent_uid_recent_key );
            if ( empty( $len ) ) {
                if ( $success > 0 || $fail > 0 ) {
                    Model::output( "成功{$success},失败{$fail}" );
                }
                Model::output( '没有待消费的数据，退出' );
                return true;
            }
            $agent_uid = $redis->getRedis()->rPop( parent::reset_agent_uid_recent_key );
            $res = $this->updateDFHAgentChildMemberAgentRecentUid( $agent_uid );

            if ( $res ) {
                $success++;
            } else {
                $fail++;
            }
        }
    }

}
