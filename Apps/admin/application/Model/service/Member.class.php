<?php

/**
 * 后台首页小图模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Member.class.php 904 2017-02-23 15:30:41Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class service_Member_admin extends service_Member_base
{

    /**
     * 会员导出
     * 默认｜不导出
     */
    const member_export_default = 0;

    /**
     * 会员导出
     * 导出当前列表数据
     */
    const member_export_current = 1;

    /**
     * 会员导出
     * 导出当前列表所有
     */
    const member_export_all = 2;

    public function __construct()
    {
        parent::__construct();
    }

    public function handelMemberInfo( $memberInfo, $memberSettingInfo )
    {
        $member_type_array = Tmac::config( 'member.member.member_type', APP_BASE_NAME );
        $member_class_array = Tmac::config( 'member.member.member_class', APP_BASE_NAME );
        $stock_setting_array = Tmac::config( 'member.member.stock_setting', APP_BASE_NAME );
        $locked_type_array = Tmac::config( 'member.member.locked_type', APP_BASE_NAME );

        $memberInfo->member_image_id = $this->getImage( $memberInfo->member_image_id, '110', 'avatar' );
        $memberInfo->reg_time = date( 'Y-m-d H:i:s', $memberInfo->reg_time );
        $memberInfo->last_login_time = date( 'Y-m-d H:i:s', $memberInfo->last_login_time );
        $memberInfo->member_type_text = $member_type_array[ $memberInfo->member_type ];
        $memberInfo->member_class_text = empty( $member_class_array[ $memberInfo->member_type ][ $memberInfo->member_class ] ) ? '' : $member_class_array[ $memberInfo->member_type ][ $memberInfo->member_class ];


        //取友情操作类型radiobutton数组        
        $memberInfo->member_type_option = Utility::Option( $member_type_array, $memberInfo->member_type );

        //取友情操作类型radiobutton数组        
        $memberInfo->member_class_option = '';
        if ( !empty( $member_class_array[ $memberInfo->member_type ] ) ) {
            $memberInfo->member_class_option = Utility::Option( $member_class_array[ $memberInfo->member_type ], $memberInfo->member_class );
        }


        //取友情操作类型radiobutton数组        
        $memberInfo->locked_type_option = Utility::Option( $locked_type_array, $memberInfo->locked_type );

        $memberSettingInfo->shop_image_id = $this->getImage( $memberSettingInfo->shop_image_id, '110', 'shop' );
        $memberSettingInfo->shop_signboard_image_id = $this->getImage( $memberSettingInfo->shop_signboard_image_id, '110', 'shop' );
        $memberSettingInfo->idcard_positive_image_id = $this->getImage( $memberSettingInfo->idcard_positive_image_id, '200x150', 'idcard' );
        $memberSettingInfo->idcard_negative_image_id = $this->getImage( $memberSettingInfo->idcard_negative_image_id, '200x150', 'idcard' );
        $memberSettingInfo->idcard_image_id = $this->getImage( $memberSettingInfo->idcard_image_id, '200x150', 'idcard' );

        $memberSettingInfo->stock_setting_text = $stock_setting_array[ $memberSettingInfo->stock_setting ];
        $idcard_verify_array = Tmac::config( 'member.member.idcard_verify', APP_BASE_NAME );
        $memberSettingInfo->idcard_verify_option = Utility::OptionObject( $idcard_verify_array, $memberSettingInfo->idcard_verify );


        return array(
            'memberInfo' => $memberInfo,
            'memberSettingInfo' => $memberSettingInfo,
            'member_class_json' => json_encode( $member_class_array, true )
        );
    }

    public function getMemberArray( entity_parameter_Member_admin $entity_parameter_Member_admin )
    {
        $dao = dao_factory_base::getMemberDao();
        $count = $dao->getMemberListCount( $entity_parameter_Member_admin );

        if ( $count === false ) {
            $retmsg = 0;
        } else {
            $retmsg = 1; //业务返回信息
        }
        $pages = $this->P( 'Pages' );
        $pages->setTotal( $count );
        $pages->setUrl( $dao->getUrl() );
        $pages->setPrepage( $entity_parameter_Member_admin->getPagesize() );
        $limit = $pages->getSqlLimit();

        $res = array();
        if ( $count > 0 ) {
            $dao->setOrderby( 'a.uid DESC' );
            $dao->setLimit( $limit );
            $dao->setField( 'a.uid,a.username,a.nickname,a.mobile,a.realname,a.email,a.member_type,a.member_class,a.member_image_id,a.reg_time,a.last_login_time,a.last_login_ip,a.register_source,b.shop_name,b.shop_image_id,b.current_money,b.history_money,a.member_level' );
            $res = $dao->getMemberListArray( $entity_parameter_Member_admin );

            $member_type_array = Tmac::config( 'member.member.member_type', APP_BASE_NAME );
            $member_class_array = Tmac::config( 'member.member.member_class', APP_BASE_NAME );
            $member_level_array = Tmac::config( 'goods.goods.goods_member_level', APP_BASE_NAME );
            foreach ( $res as $value ) {
                $value->member_image_id = $this->getImage( $value->member_image_id, '110', 'avatar' );
                $value->reg_time = date( 'Y-m-d H:i:s', $value->reg_time );
                $value->last_login_time = date( 'Y-m-d H:i:s', $value->last_login_time );
                $value->member_type_text = $member_type_array[ $value->member_type ];
                $value->member_class_text = isset( $member_class_array[ $value->member_type ][ $value->member_class ] ) ? $member_class_array[ $value->member_type ][ $value->member_class ] : '';
                $value->member_level = isset( $member_level_array[ $value->member_level ] ) ? $member_level_array[ $value->member_level ] : '不是会员';
            }
        }

        $ErrorMsg = '';
        if ( $count == 0 ) {
            $ErrorMsg = "暂无会员!";
        }

        $result = array(
            'rs' => $res,
            'pageCurrent' => $pages->getNowPage(),
            'page' => $pages->show(),
            'ErrorMsg' => $ErrorMsg
        );
        return $result;
    }

    public function exportMemberArray( entity_parameter_Member_admin $entity_parameter_Member_admin )
    {
        $dao = dao_factory_base::getMemberDao();
        $where = "1=1";

        if ( !empty( $entity_parameter_Member_admin->member_type ) ) {
            $where .= " AND member_type={$entity_parameter_Member_admin->member_type} ";
        }
        if ( $entity_parameter_Member_admin->member_class <> -1 ) {
            $where .= " AND member_class={$entity_parameter_Member_admin->member_class} ";
        }
        //解析$query_string
        if ( !empty( $entity_parameter_Member_admin->query_string ) ) {
            if ( preg_match( '/^1([3]|[5]|[8]|[4]|[7])[0-9]{9}$/', $entity_parameter_Member_admin->query_string ) ) {
                $where .= " AND mobile='{$entity_parameter_Member_admin->query_string}'";
            } elseif ( preg_match( '/^[0-9]{2,15}$/u', $entity_parameter_Member_admin->query_string ) ) {
                $where .= " AND uid={$entity_parameter_Member_admin->query_string}";
            }
        }
        if ( !empty( $entity_parameter_Member_admin->start_date ) ) {
            $where .= " AND reg_time>=" . strtotime( $entity_parameter_Member_admin->start_date );
        }
        if ( !empty( $entity_parameter_Member_admin->end_date ) ) {
            $where .= " AND reg_time<=" . strtotime( $entity_parameter_Member_admin->end_date );
        }

        //导出的资源排除自己人的
        $where .= " AND mobile NOT IN('15910986304','13771023935','15601178342','15011420631','13293359887','13016417050','13067870076','13718527728','18871237114','13466612906','18027658642','18372675177','15901484288','18628896185','13071298860','13269561886','13777050761','18622705725','18327611280','18883656968','18507177941','18033522669','13871903123','13135636400','18680910820','18971670906','13733526021','15712137773','13268591918','15900463470','13636074231')";
        $dao->setWhere( $where );
        if ( $entity_parameter_Member_admin->member_export == self::member_export_current ) {
            $count = $dao->getMemberListCount( $entity_parameter_Member_admin );
            $pages = $this->P( 'Pages' );
            $pages->setTotal( $count );
            $pages->setPrepage( $entity_parameter_Member_admin->getPagesize() );
            $limit = $pages->getSqlLimit();
            $dao->setLimit( $limit );
            $dao->setOrderby( 'uid DESC' );
        }

        $res = $dao->getListByWhere();
        $count = count( $res );

        if ( empty( $count ) ) {
            $this->errorMessage = '没有找到数据';
            return false;
        }

        include Tmac::findFile( 'PHPExcel', APP_ADMIN_NAME );
        $objPHPExcel = new PHPExcel();

        $title = "注册用户{$entity_parameter_Member_admin->start_date}至{$entity_parameter_Member_admin->end_date}";
        // Set document properties        
        $objPHPExcel->getProperties()->setCreator( "Maarten Balliauw" )
                ->setLastModifiedBy( "zhangwentao" )
                ->setTitle( $title );
        //设置当前的sheet   
        $objPHPExcel->setActiveSheetIndex( 0 );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 4, 1, $title );
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth( 10 );
        $xls_header = array( '会员ID', 'username', '手机号码', '用户类型', '用户级别', '用户注册时间', '上次登录时间', '上次登录IP', '推荐人ID' );
        for ( $i = 0; $i < count( $xls_header ); $i++ ) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $i, 2, $xls_header[ $i ] );
        }


        $member_type_array = Tmac::config( 'member.member.member_type', APP_BASE_NAME );
        $member_class_array = Tmac::config( 'member.member.member_class', APP_BASE_NAME );

        $row = 3;
        foreach ( $res as $value ) {
            $value->reg_time = date( 'Y-m-d H:i:s', $value->reg_time );
            $value->last_login_time = date( 'Y-m-d H:i:s', $value->last_login_time );
            $value->member_type_text = $member_type_array[ $value->member_type ];
            $value->member_class_text = $member_class_array[ $value->member_type ][ $value->member_class ];

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 0, $row, $value->uid );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 1, $row, $value->username );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 2, $row, $value->mobile );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 3, $row, $value->member_type_text );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 4, $row, $value->member_class_text );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 5, $row, $value->reg_time );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 6, $row, $value->last_login_time );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 7, $row, $value->last_login_ip );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 8, $row, $value->agent_uid );
            $row++;
        }
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 7, $row, '总数：' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 8, $row, $count );

        // Redirect output to a client’s web browser (Excel2007)
        header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
        header( 'Content-Disposition: attachment;filename=' . $title . '.xlsx' );
        header( 'Cache-Control: max-age=0' );
// If you're serving to IE 9, then the following may be needed
        header( 'Cache-Control: max-age=1' );

// If you're serving to IE over SSL, then the following may be needed
        header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' ); // Date in the past
        header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ); // always modified
        header( 'Cache-Control: cache, must-revalidate' ); // HTTP/1.1
        header( 'Pragma: public' ); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel2007' );
        $objWriter->save( 'php://output' );
    }

    public function updateMemberInfoByAdmin( \base\entity\Member $entity_Member_base, \base\entity\MemberSetting $entity_MemberSetting_base )
    {
        $dao = \dao_factory_base::getMemberDao();
        $source_entity_Member = $dao->setPk( $this->uid )->setField( 'dfh_agent_uid,member_type,member_class' )->getInfoByPk();
        $dao->getDb()->startTrans();

        if ( !empty( $entity_Member_base->member_type ) ) {
            parent::updateMemberInfo( $entity_Member_base );
            if ( $entity_Member_base->member_type == service_Member_base::member_type_supplier ) {
                $goods_save_model = new service_goods_Save_admin();
                $goods_save_model->updateGoodsSupplier( $this->uid );
            } else if ( $entity_Member_base->member_type == service_Member_base::member_type_dfh_agent ) {

                /**
                if ( ($entity_Member_base->member_type == $source_entity_Member->member_type 
                        && $entity_Member_base->member_class == $source_entity_Member->member_class 
                        && $entity_Member_base->dfh_agent_uid == $source_entity_Member->dfh_agent_uid) == false ) {
                    $agent_member_info = $this->getMemberInfoByUid( $entity_Member_base->dfh_agent_uid, 'member_type,member_class' );
                    if ( $agent_member_info && $agent_member_info->member_type == service_Member_base::member_type_dfh_agent && $agent_member_info->member_class < $entity_Member_base->member_class ) {
                        $entity_Member_base->uid = $this->uid;
                        //更新玩家自己的上级
                        $entity_MemberSetting_base->dfh_agent_parent_uid = $this->getDfhAgentParentUid( $entity_Member_base->dfh_agent_uid, $agent_member_info );
                        //更新上下级代理的 member_level和member_setting.dfh_agent_parent_uid
                        //更新上级
                        $this->updateDFHAgentParentMemberLevel( $entity_Member_base, $entity_MemberSetting_base->dfh_agent_parent_uid );
                        //更新下级
                        $this->updateDFHAgentChildMemberLevel( $entity_Member_base, $entity_MemberSetting_base->dfh_agent_parent_uid, $agent_member_info );
                    }
                    //更新代理下级直推member_setting.agent_uid_recent
                    $this->updateDFHAgentChildMemberAgentRecentUid( $entity_Member_base->uid );
                }
                */

            }
        }
        $entity_MemberSetting_base->member_type = $entity_Member_base->member_type;
        $entity_MemberSetting_base->member_class = $entity_Member_base->member_class;
        $this->updateMemberSettingInfo( $entity_MemberSetting_base );

        //取消代理资格
        $entity_Member_base->uid = $this->uid;
        $this->cancelMemberAgent( $source_entity_Member, $entity_Member_base );

        if ( $dao->getDb()->isSuccess() ) {
            $dao->getDb()->commit();
            //TODO 发短信
            return true;
        } else {
            $dao->getDb()->rollback();
            return false;
        }
    }

    /**
     * 更新代理所有直推下级的第一级直推代理
     * @param type $agent_uid
     * @return boolean
     */
    private function updateDFHAgentChildMemberAgentRecentUid( $agent_uid )
    {
        //修改代理也放在异步中去处理
        return $this->resetChildAgentUidRecent();
        //查出所有直推下级
        $member_setting_dao = \dao_factory_base::getMemberSettingDao();
        $member_level_dao = \dao_factory_base::getMemberLevelDao();
        $where = "agent_uid={$agent_uid} AND member_level_type=" . parent::member_level_type_recommend;
        $member_level_array = $member_level_dao->setWhere( $where )->setField( 'uid' )->getListByWhere();
        if ( empty( $member_level_array ) ) {
            return true;
        }
        foreach ( $member_level_array as $member_level ) {
            $member_setting_info = $this->getMemberSettingInfoByUid( $member_level->uid, 'agent_uid_parent,agent_uid_recent' );
            $entity_MemberSetting = new \base\entity\MemberSetting();
            if ( empty( $member_setting_info->agent_uid_recent ) ) {
                //不存在 重新比所有 
                $entity_MemberSetting->agent_uid_recent = $agent_uid;
            } else {
                //存在上级代理
                $agent_uid_parent_array = explode( ',', $member_setting_info->agent_uid_parent );
                $source_key = $new_key = 0;
                foreach ( $agent_uid_parent_array as $key => $agent_uid_parent ) {
                    if ( $agent_uid_parent == $member_setting_info->agent_uid_recent ) {
                        $source_key = $key;
                    } else if ( $agent_uid_parent == $agent_uid ) {
                        $new_key = $key;
                    }
                }
                if ( $new_key >= $source_key ) {
                    continue;
                }
                //需要更新 新的agent_uid排位比原来的靠前
                $entity_MemberSetting->agent_uid_recent = $agent_uid;
            }
            $where = "uid={$member_level->uid}";
            $member_setting_dao->setWhere( $where )->updateByWhere( $entity_MemberSetting );

            $entity_MemberLevel = new \base\entity\MemberLevel();
            $entity_MemberLevel->agent_uid_recent = $agent_uid;
            $member_level_where = "uid={$member_level->uid} AND member_level_type=" . parent::member_level_type_recommend;
            $member_level_dao->setWhere( $member_level_where )->updateByWhere( $entity_MemberLevel );
        }
        return true;
    }

    /**
     * 取消代理资格
     * @param type $source_entity_Member
     * @param \base\entity\Member $entity_Member_base
     * @return boolean
     */
    private function cancelMemberAgent( $source_entity_Member, \base\entity\Member $entity_Member_base )
    {
        if ( $source_entity_Member->member_type <> parent::member_type_dfh_agent ) {//原来非代理
            return true;
        }
        if ( $entity_Member_base->member_type == parent::member_type_dfh_agent ) {//现在非代理
            return true;
        }
        //初始化直推下级的agent_uid_recent
        $this->resetChildAgentUidRecent();

        //清空自己的member_setting.dfh_agent_parent_uid
        $entity_MemberSetting = new \base\entity\MemberSetting();
        $entity_MemberSetting->dfh_agent_parent_uid = '';
        $member_setting_dao = \dao_factory_base::getMemberSettingDao();
        $member_setting_dao->setWhere( "uid={$this->uid}" )->updateByWhere( $entity_MemberSetting );

        $member_dao = \dao_factory_base::getMemberDao();
        $update_entity_Member = new \base\entity\Member();
        $update_entity_Member->dfh_agent_uid = 0;
        $update_where = "dfh_agent_uid={$entity_Member_base->uid}";
        $member_dao->setWhere( $update_where )->updateByWhere( $update_entity_Member );

        //删除下级中的自己
        $child_uid_array = $this->getChildUidArray( $entity_Member_base );
        if ( !empty( $child_uid_array ) ) {
            $dao = dao_factory_base::getMemberSettingDao();
            $where = $dao->getWhereInStatement( 'uid', implode( ',', $child_uid_array ) );
            $dao->setWhere( $where );
            $dao->setField( 'uid,dfh_agent_parent_uid' );
            $child_member_setting_array = $dao->getListByWhere();

            foreach ( $child_member_setting_array as $member_setting ) {
                //下级代理原来的 parent_uid_array
                $child_dfh_agent_parent_uid_array = json_decode( $member_setting->dfh_agent_parent_uid, true );
                unset( $child_dfh_agent_parent_uid_array[ $source_entity_Member->member_class ] );
                $entity_MemberSetting = new \base\entity\MemberSetting();
                $entity_MemberSetting->dfh_agent_parent_uid = json_encode( $child_dfh_agent_parent_uid_array );
                $where = "uid={$member_setting->uid}";
                $dao->setWhere( $where )->updateByWhere( $entity_MemberSetting );
            }
        }

        //删除自己的member_level
        $member_level_dao = \dao_factory_base::getMemberLevelDao();
        $delete_where = "uid={$this->uid}"
                . ' AND member_level_type=' . parent::member_level_type_agent;
        $member_level_dao->setWhere( $delete_where )->deleteByWhere();
        $delete_where = "agent_uid={$this->uid}"
                . ' AND member_level_type=' . parent::member_level_type_agent;
        $member_level_dao->setWhere( $delete_where )->deleteByWhere();
        return true;
    }

    /**
     * 初始化直推下级的agent_uid_recent
     * 修改或取消后都要初始化。扔redis队列中去修改
     */
    private function resetChildAgentUidRecent()
    {
        $member_setting_dao = \dao_factory_base::getMemberSettingDao();
        $member_level_dao = \dao_factory_base::getMemberLevelDao();

        //取消下级玩家的agent_uid_recent|最近的一个直推上级中是代理的uid 非代理类型专用 agent_uid_parent字段中的直推人，由左到右最近的一个代理uid。如果都不是代理则为0                
        $update_recent_where = "agent_uid={$this->uid} AND member_level_type=" . parent::member_level_type_recommend;
        $update_recent_sql_in = $member_level_dao->setWhere( $update_recent_where )->setField( 'uid' )->getSqlByWhere();

        $entity_MemberSetting = new \base\entity\MemberSetting();
        $entity_MemberSetting->agent_uid_recent = 0;

        $member_setting_dao->setWhere( "uid IN({$update_recent_sql_in})" )->updateByWhere( $entity_MemberSetting );
        //初始化自己的agnet_uid_recent=0
        $member_setting_dao->setWhere( "uid={$this->uid}" )->updateByWhere( $entity_MemberSetting );

        $entity_MemberLevel = new \base\entity\MemberLevel();
        $entity_MemberLevel->agent_uid_recent = 0;
        $member_level_where = "uid IN(SELECT uid FROM ({$update_recent_sql_in}) as member_temp) AND member_level_type=" . parent::member_level_type_recommend;
        $member_level_dao->setWhere( $member_level_where )->updateByWhere( $entity_MemberLevel );
        //初始化自己的agnet_uid_recent=0
        $member_level_where = "uid={$this->uid} AND member_level_type=" . parent::member_level_type_recommend;
        $member_level_dao->setWhere( $member_level_where )->updateByWhere( $entity_MemberLevel );
        //加入redis中去变更新的
        $redis = \CacheDriver::getInstance( 'Redis', 'default' );
        return $redis->getRedis()->lPush( parent::reset_agent_uid_recent_key, $this->uid );
    }

    /**
     * 更新member_level表中的数据
     * 及下级代理的member_setting.dfh_agent_parent_uid

     * [ ] 修改自己下级代理的 member_setting.dfh_agent_parent_uid  和 下级的member_level
     * 
     * * [] 通过member_level中agent_uid=$agent_uid and member_level_type=1 找到所有下级
     * * [] 更新自己下级的父类（member_level) member_class的数据

     * [] 修改自己上级代理的 member_level表（删除，再新插）
     * 
     * * [] 删除member_level表中 uid=$uid and agent_uid=$agnet_uid and member_level_type=1的所有数据
     * * [] 写入新的member_level上家数据
     */
    private function updateDFHAgentParentMemberLevel( \base\entity\Member $entity_Member, $dfh_agent_parent_uid )
    {
        $dfh_agent_parent_uid_array = json_decode( $dfh_agent_parent_uid, true );
        if ( empty( $dfh_agent_parent_uid_array ) ) {
            return true;
        }
        $member_level_dao = dao_factory_base::getMemberLevelDao();
        //删除当前设置的原来所有上级
        $agent_uid_not_in = implode( ',', array_values( $dfh_agent_parent_uid_array ) );
        $delete_where = 'uid=' . $entity_Member->uid
                . ' AND member_level_type=' . parent::member_level_type_agent
                . " AND agent_uid NOT IN({$agent_uid_not_in})";
        $member_level_dao->setWhere( $delete_where )->deleteByWhere();
        //写入/更新最新的当前代理上级
        foreach ( $dfh_agent_parent_uid_array as $member_class => $dfh_agent_uid ) {
            //查出当前设置代理的所有上级
            $where = "uid={$entity_Member->uid} AND agent_uid={$dfh_agent_uid} AND member_level={$member_class} AND member_level_type=" . parent::member_level_type_agent;
            $memberLevelInfo = $member_level_dao->setWhere( $where )->getInfoByWhere();
            if ( empty( $memberLevelInfo ) ) {
                //写入自己的新的
                $entity_MemberLevel = new \base\entity\MemberLevel();
                $entity_MemberLevel->uid = $entity_Member->uid;
                $entity_MemberLevel->agent_uid = $dfh_agent_uid;
                $entity_MemberLevel->member_level_type = parent::member_level_type_agent;
                $entity_MemberLevel->member_level = $member_class;
                $member_level_dao->insert( $entity_MemberLevel );
                continue;
            }
            //更新
            $entity_MemberLevel = new \base\entity\MemberLevel();
            $entity_MemberLevel->uid = $entity_Member->uid;
            $member_level_dao->setPk( $memberLevelInfo->member_level_id )->updateByPk( $entity_MemberLevel );
        }
        return true;
    }

    /**
     * 取下级uid Array
     * @param \base\entity\Member $entity_Member
     * @return boolean
     */
    private function getChildUidArray( \base\entity\Member $entity_Member )
    {
        $member_level_dao = dao_factory_base::getMemberLevelDao();
        //查出当前设置代理的所有下级
        $where = "agent_uid={$entity_Member->uid} AND member_level_type=" . parent::member_level_type_agent;
        $res = $member_level_dao->setWhere( $where )->getListByWhere();
        if ( empty( $res ) ) {
            return [ ];
        }
        //$member_child[$uid][$member_level] = $agent_uid;
        $child_uid_array = [ ]; //所有下有的uid array
        foreach ( $res as $member_level ) {
            $child_uid_array[] = $member_level->uid;
        }
        return $child_uid_array;
    }

    /**
     * [ ] 修改自己下级代理的 member_setting.dfh_agent_parent_uid  和 下级的member_level
     * 通过member_level中agent_uid=$agent_uid and member_level_type=1 找到所有下级
     * 更新自己下级的父类（member_level) member_class的数据
     * @param \base\entity\Member $entity_Member
     * @param type $agent_member_info
     * @return boolean
     */
    private function updateDFHAgentChildMemberLevel( \base\entity\Member $entity_Member, $dfh_agent_parent_uid, $agent_member_info )
    {
        $child_uid_array = $this->getChildUidArray( $entity_Member );
        if ( empty( $child_uid_array ) ) {
            return true;
        }
        //查出下级uid的所有大于设置级别的member_level全删掉。
        $child_uid_string = implode( ',', $child_uid_array );
        $this->deleteOldAgentMemberLevel( $child_uid_string, $agent_member_info );
        //写入新的member_level
        $this->insertNewAgentMemberLevel( $child_uid_array, $dfh_agent_parent_uid, $agent_member_info );
        //写入新的member_setting.dfh_agent_parent_uid
        $this->updateChildAgentParentUid( $child_uid_array, $dfh_agent_parent_uid );
        return true;
    }

    /**
     * 删除下级代理无用的旧member_level流水记录
     * @param type $child_uid_string
     * @param type $agent_member_info
     * @return type
     */
    private function deleteOldAgentMemberLevel( $child_uid_string, $agent_member_info )
    {
        $member_level_dao = dao_factory_base::getMemberLevelDao();
        $delete_where = $member_level_dao->getWhereInStatement( 'uid', $child_uid_string )
                . ' AND member_level_type=' . parent::member_level_type_agent
                . ' AND member_level<=' . $agent_member_info->member_class; //删除下级代理的上级
        return $member_level_dao->setWhere( $delete_where )->deleteByWhere();
    }

    /**
     * 更新下级代理的member_setting.dfh_agent_parent_uid
     * @param type $child_uid_array
     * @param type $dfh_agent_parent_uid
     * @return boolean
     */
    private function updateChildAgentParentUid( $child_uid_array, $dfh_agent_parent_uid )
    {
        $dfh_agent_parent_uid_array = json_decode( $dfh_agent_parent_uid, true );

        $dao = dao_factory_base::getMemberSettingDao();
        $where = $dao->getWhereInStatement( 'uid', implode( ',', $child_uid_array ) );
        $dao->setWhere( $where );
        $dao->setField( 'uid,dfh_agent_parent_uid' );
        $child_member_setting_array = $dao->getListByWhere();
        foreach ( $child_member_setting_array as $member_setting ) {
            //下级代理原来的 parent_uid_array
            $child_dfh_agent_parent_uid_array = json_decode( $member_setting->dfh_agent_parent_uid, true );
            foreach ( $dfh_agent_parent_uid_array as $member_class => $dfh_agent_uid ) {
                $child_dfh_agent_parent_uid_array[ $member_class ] = $dfh_agent_uid;
            }
            $entity_MemberSetting = new \base\entity\MemberSetting();
            $entity_MemberSetting->dfh_agent_parent_uid = json_encode( $child_dfh_agent_parent_uid_array );
            $where = "uid={$member_setting->uid}";
            $dao->setWhere( $where )->updateByWhere( $entity_MemberSetting );
        }
        return true;
    }

    /**
     * 设置代理的时候写入下级代理的member_level表     
     * 更新member_setting.agent_uid_parent 字段
     * -------------------------------------------
     * 如果是非代理这种类型。无限线的。一样用，直接全写删下级的原上级，然后重写下级的上级
     * 
     * @param type $child_uid_array     
     * @param type $dfh_agent_parent_uid 上级代理的
     * @return boolean
     */
    private function insertNewAgentMemberLevel( $child_uid_array, $dfh_agent_parent_uid, $agent_member_info )
    {
        //$agent_uid_array, $member_level_type
        $dfh_agent_parent_uid_array = json_decode( $dfh_agent_parent_uid, true );
        $member_level_type = service_Member_base::member_level_type_agent;
        $dao = dao_factory_base::getMemberLevelDao();

        $value = '';
        foreach ( $child_uid_array AS $child_uid ) {
            foreach ( $dfh_agent_parent_uid_array as $member_class => $dfh_agent_uid ) {
                if ( $member_class > $agent_member_info->member_class ) {
                    //下级代理的下级不再写新的
                    continue;
                }
                $value .= ",({$child_uid},{$dfh_agent_uid},{$member_level_type},{$member_class})";
            }
        }
        $values = substr( $value, 1 );
        if ( empty( $values ) ) {
            return true;
        }
        $sql = "INSERT INTO {$dao->getTable()} (`uid`,`agent_uid`,`member_level_type`,`member_level`) VALUES {$values};";
        return $dao->getDb()->execute( $sql );
    }

    /**
     * 取父级代理 从上级代理的继承     
     * @param \base\entity\MemberSetting $entity_MemberSetting_base
     */
    private function getDfhAgentParentUid( $dfh_agent_uid, $agent_member_info )
    {
        $agent_member_setting_info = $this->getMemberSettingInfoByUid( $dfh_agent_uid );
        $dfh_agent_parent_uid_array = json_decode( $agent_member_setting_info->dfh_agent_parent_uid, true );
        $dfh_agent_parent_uid_array[ $agent_member_info->member_class ] = $dfh_agent_uid;
        return json_encode( $dfh_agent_parent_uid_array );
    }

    /**
     * delete 暂时废掉
     * 更新代理商所有下级的member_setting.dfh_agent_parent_uid
     * @param \base\entity\Member $entity_Member_base
     */
    private function updateMemberDFHAgentParentUid( \base\entity\Member $entity_Member_base )
    {
        $DFHAgentModel = new \base\service\member\DFHAgent();
        $DFHAgentModel->setMemberInfo( $entity_Member_base );
        //所有下级代理
        $dfh_agent_uid_array = $DFHAgentModel->getChildrenAgentUidArray();
        $dao = \dao_factory_base::getMemberSettingDao();
        if ( !empty( $dfh_agent_uid_array ) ) {
            foreach ( $dfh_agent_uid_array as $uid ) {
                $memberInfo = $this->getMemberInfoByUid( $uid, 'uid,member_type,member_class,dfh_agent_uid' );
                $DFHAgentModel->setMemberInfo( $memberInfo );
                $dfh_agent_parent_uid = $DFHAgentModel->getPlayersParentsAgentUid();
                if ( empty( $dfh_agent_parent_uid ) ) {
                    continue;
                }
                $entity_MemberSetting = new \base\entity\MemberSetting();
                $entity_MemberSetting->dfh_agent_parent_uid = json_encode( $DFHAgentModel->getPlayersParentsAgentUid() );

                $dao->setPk( $uid );
                $dao->updateByPk( $entity_MemberSetting );
            }
        }
        //所有下级玩家
        $agent_uid_array = $DFHAgentModel->getPlayersOfAgentUidArray();
        if ( !empty( $agent_uid_array ) ) {
            $member_dao = \dao_factory_base::getMemberDao();
            foreach ( $agent_uid_array as $uid ) {
                $entity_Member = new \base\entity\Member();
                $entity_Member->dfh_agent_uid = $this->uid;
                $member_dao->setPk( $uid );
                $member_dao->updateByPk( $entity_Member );
            }
        }
    }

}
