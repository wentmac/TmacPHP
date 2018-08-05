<?php

/**
 * 账单  代理
 * ============================================================================
 * @author  by time 22014-07-07
 *
 */
class agentAction extends service_Controller_mobile
{

    //定义初始化变量

    public function _init()
    {
        $this->checkLogin();
    }

    private function checkApplyPurview( $return = 'json' )
    {
        if ( $this->memberInfo->member_type == service_Member_base::member_type_dfh_agent ) {
            $error_message = '您已经是代理商';
            if ( $return == 'json' ) {
                throw new ApiException( $error_message );
            } else {
                $this->redirect( $error_message );
                return false;
            }
        }
        return true;
    }

    /**
     * 代理申请
     */
    public function apply()
    {
        //是否未申请过。
        $this->checkApplyPurview( 'html' );

        $backurl = Input::get( 'backurl', '' )->string();

        //取城市联运信息
        $region_model = Tmac::model( 'Region', APP_BASE_NAME );
        $region_model instanceof service_Region_base;
        $province_array = $region_model->getRegionListByPid( 1 );
        $city_option = '';
        $district_option = '';

        $province_option = Utility::OptionObject( $province_array, $this->memberSettingInfo->agent_province, 'region_id,region_name' );
        if ( $this->memberSettingInfo->agent_city > 0 ) {
            $city_array = $region_model->getRegionListByPid( $this->memberSettingInfo->agent_province );
            $city_option = Utility::OptionObject( $city_array, $this->memberSettingInfo->agent_province, 'region_id,region_name' );
        }
        if ( $this->memberSettingInfo->agent_district > 0 ) {
            $district_array = $region_model->getRegionListByPid( $this->memberSettingInfo->agent_city );
            $district_option = Utility::OptionObject( $district_array, $this->memberSettingInfo->agent_city, 'region_id,region_name' );
        }
        $array = array();
        $array[ 'province_option' ] = $province_option;
        $array[ 'city_option' ] = $city_option;
        $array[ 'district_option' ] = $district_option;

        $array[ 'memberInfo' ] = $this->memberInfo;
        $array[ 'memberSettingInfo' ] = $this->memberSettingInfo;
        $array[ 'editinfo_json' ] = json_encode( $this->memberSettingInfo, true );
        $array[ 'backurl' ] = $backurl;

        // echo '<pre>';
        // print_r( $array );
        // echo '</pre>';
        //die;
        $this->assign( $array );
        $this->V( 'member/agent_apply' );
    }

    /**
     * 代理申请保存
     */
    public function apply_save()
    {
        //是否未申请过。
        //是否不是代理
        $this->checkApplyPurview();

        $uid = $this->memberInfo->uid;
        $realname = Input::post( 'realname', '' )->required( '姓名不能为空' )->string();
        $member_contact = Input::post( 'member_contact', 0 )->required( '电话号码不能为空' )->tel();
        $country = Input::post( 'country', 1 )->int();
        $province = Input::post( 'province', 0 )->required( '省不能为空' )->int();
        $city = Input::post( 'city', 0 )->required( '城市不能为空' )->int();
        $district = Input::post( 'district', 0 )->required( '行政区不能为空' )->int();

        $agent_address = Input::post( 'agent_address', '' )->required( '收货人详细地址不能为空' )->string();
        $id_card = Input::post( 'idcard', 0 )->required( '身份证号码不能为空' )->bigint();
        $remark = Input::post( 'remark', '' )->string();

        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() );
        }

        $entity_MemberSetting_base = new \base\entity\MemberSetting();
        $entity_MemberSetting_base->member_contact = $member_contact;
        $entity_MemberSetting_base->agent_province = $province;
        $entity_MemberSetting_base->agent_city = $city;
        $entity_MemberSetting_base->agent_district = $district;
        $entity_MemberSetting_base->agent_address = $agent_address;
        $entity_MemberSetting_base->remark = $remark;
        $entity_MemberSetting_base->member_type = service_Member_base::member_type_mall;
        $entity_MemberSetting_base->idcard = $id_card;

        $entity_Member_base = new \base\entity\Member();
        $entity_Member_base->realname = $realname;

        $member_model = new service_Member_base();
        $member_model->setUid( $uid );
        $res = $member_model->updateMember( $entity_Member_base, $entity_MemberSetting_base );


        if ( $res ) {
            $this->apiReturn();
        } else {
            throw new ApiException( '申请代理商失败' );
        }
    }

    /**
     * 我的推荐人
     */
    public function detail()
    {
        $model = new service_member_Agent_mobile();
        $model->setAgent_uid( $this->memberInfo->agent_uid );
        $member_agent_info = $model->getAgentMemberInfo();

        $array[ 'member_agent_info' ] = $member_agent_info;

        $this->assign( $array );

        $this->V( 'member/agent_detail' );
    }

    /**
     * 推荐关系层级排位
     */
    public function level()
    {
        $member_tree_show_model = new service_member_TreeShow_base();
        $member_tree_show_model->setRank_level( service_member_TreeShow_base::tree_level_count );
//        $tree_array = $member_tree_show_model->showAgentRankTree( $this->memberInfo->uid );
//        echo '<pre>';
//        print_r($tree_array);die;
//        $member_info_map = $member_tree_show_model->getMemberInfoMap();
        //取出所有的粉丝
        $member_agent_model = new service_member_Agent_mobile();
        $agent_array = $member_agent_model->getAgentAll( $this->memberInfo->uid );

//        $array[ 'tree_array' ] = $tree_array;
//        $array[ 'member_info_map' ] = $member_info_map;
        $array[ 'agent_array' ] = $agent_array;
        $array[ 'memberInfo' ] = $this->memberInfo;

        //echo '<Pre>';
        //print_r($array);die;
        $this->assign( $array );

        $this->V( 'member/agent_level' );
    }

    /**
     * 取全部账单列表
     */
    public function get_bill_list()
    {
        $status = Input::get( 'status', '' )->string();
        $pagesize = Input::get( 'pagesize', 10 )->int();

        $order_model = new service_bill_List_manage();
        $order_model->setUid( $this->memberInfo->uid );
        $order_model->setPagesize( $pagesize );
        $order_model->setStatus( $status );

        $order_model->getBillWhere();
        $rs = $order_model->getBillList();
        $this->apiReturn( $rs );
    }

}
