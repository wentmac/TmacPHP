<?php

/**
 * 账单 
 * ============================================================================
 * @author  by time 22014-07-07
 * 
 */
class integralAction extends service_Controller_mobile
{

    //定义初始化变量        
    public function _init()
    {
        $this->checkLogin();
    }

    /**
     * 我的推荐人
     */
    public function index()
    {
        //总得分
        $array[ 'sum_integral' ] = $this->memberInfo->sum_integral;
        //剩余积分
        $array[ 'available_integral' ] = $this->memberInfo->available_integral;
        //总消费
        $array[ 'consume_integral' ] = $this->memberInfo->sum_integral - $this->memberInfo->available_integral;
        $this->assign( $array );

        $this->V( 'member/integral' );
    }

    public function check_in_status()
    {
        $integral_model = new service_member_Integral_mobile();
        $integral_model->setUid( $this->memberInfo->uid );
        $integral_model->setMemberInfo( $this->memberInfo );
        $res = $integral_model->checkInStatus();
        if ( $res ) {
            $this->apiReturn();
        } else {
            throw new ApiException( $integral_model->getErrorMessage() );
        }
    }

    public function check_in()
    {
        $integral_model = new service_member_Integral_mobile();
        $integral_model->setUid( $this->memberInfo->uid );
        $integral_model->setMemberInfo( $this->memberInfo );
        $checkin = $integral_model->checkIn();
        if ( $checkin ) {
            $this->apiReturn();
        } else {
            throw new ApiException( $integral_model->getErrorMessage() );
        }
    }

    /**
     * 取全部账单列表
     */
    public function get_list()
    {
        $pagesize = Input::get( 'pagesize', 10 )->int();

        $integral_model = new service_member_Integral_mobile();
        $integral_model->setUid( $this->memberInfo->uid );
        $integral_model->setPagesize( $pagesize );

        $rs = $integral_model->getMemberIntegralList();
        $this->apiReturn( $rs );
    }

}
