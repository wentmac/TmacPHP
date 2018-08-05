<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Integral.class.php 439 2016-10-04 09:47:05Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class service_member_Integral_mobile extends service_MemberIntegral_base
{

    protected $uid;
    protected $memberInfo;
    protected $pagesize;

    function setUid( $uid )
    {
        $this->uid = $uid;
    }

    function setMemberInfo( $memberInfo )
    {
        $this->memberInfo = $memberInfo;
    }

    function setPagesize( $pagesize )
    {
        $this->pagesize = $pagesize;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 判断上次签到是否连续
     * @return boolean
     */
    private function checkDateContinuous()
    {
        $start_time = strtotime( date( 'Y-m-d', $this->memberInfo->checkin_last_time ) );
        $end_time = strtotime( date( 'Y-m-d' ) );
        /**
          $start_time = strtotime( date( 'Y-m-d', 1467683029 ) );
          $end_time = strtotime( date( 'Y-m-d', 1467881436 ) );
         * 
         */
        $diff_time = abs( $end_time - $start_time );
        if ( $diff_time != 86400 ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 签到
     * 连续签 几天奖励几个。中止就重头来。
     */
    public function checkIn()
    {
        //积分余额已经满了，可以签到，但是积分为0.只是为了记录一个连续
        if ( $this->memberInfo->available_integral >= service_Member_base::available_max_integral ) {
            $integral = 0;
            $integral_zero = service_MemberIntegral_base::integral_zero_yes;
        } else {
            $integral = service_Member_base::checkin_integral_value;
            $integral_zero = service_MemberIntegral_base::integral_zero_no;
        }
        $check = $this->checkInStatus();
        if ( $check === false ) {
            return false;
        }
        //判断是否连续签到
        $entity_Member_base = new \base\entity\Member();
        $checkin_continuous = $this->checkDateContinuous();
        if ( $checkin_continuous ) {
            $entity_Member_base->checkin_count = new TmacDbExpr( 'checkin_count+1' );
        } else {
            $entity_Member_base->checkin_count = 1;
        }

        //给积分。没有积满&&并且连续
        if ( $checkin_continuous && $this->memberInfo->available_integral < service_Member_base::available_max_integral ) {
            //连续签 几天奖励几个。中止就重头来。
            $integral = empty( $this->memberInfo->checkin_count ) ? service_Member_base::checkin_integral_value : $this->memberInfo->checkin_count + 1;            
            //单次最大可得积分
            $available_max_integral = $this->memberInfo->available_integral + $integral;                        
            if ( $available_max_integral > service_Member_base::available_max_integral ) {
                //最大可得积分减去积分余额
                $integral = service_Member_base::available_max_integral - $this->memberInfo->available_integral;                
            }                                  
        }

        $entity_Member_base->sum_integral = new TmacDbExpr( 'sum_integral+' . $integral );
        $entity_Member_base->available_integral = new TmacDbExpr( 'available_integral+' . $integral );
        $entity_Member_base->checkin_last_time = $this->now;

        $integral_dao = dao_factory_base::getIntegralDao();
        $dao = dao_factory_base::getMemberDao();
        $dao->getDb()->startTrans();

        $dao->setPk( $this->uid );
        $dao->updateByPk( $entity_Member_base );
        //东家的积分记录表中增加
        $entity_Integral_base = new \base\entity\Integral();
        $entity_Integral_base->integral = $integral;
        $entity_Integral_base->uid = $this->uid;
        $entity_Integral_base->integral_type = service_MemberIntegral_base::integral_type_increase;
        $entity_Integral_base->integral_class = service_MemberIntegral_base::integral_class_increase_checkin;
        $entity_Integral_base->integral_zero = $integral_zero;
        $entity_Integral_base->integral_time = $this->now;                
        $integral_dao->insert( $entity_Integral_base );
        if ( $dao->getDb()->isSuccess() ) {
            $dao->getDb()->commit();
            return true;
        } else {
            $dao->getDb()->rollback();
            return false;
        }
    }

    /**
     * 检测是否已经签到
     * 判断余额积分是否大于50分？
     * --如果大于50分就return false;
     * 判断今天是否已经签到过
     */
    public function checkInStatus()
    {
        //判断今天是否签到过
        $today_start = strtotime( date( 'Y-m-d' ) );
        $dao = dao_factory_base::getIntegralDao();
        $dao->setField( 'integral_id' );
        $where = "uid={$this->uid} AND integral_type=" . service_MemberIntegral_base::integral_type_increase
                . " AND integral_class=" . service_MemberIntegral_base::integral_class_increase_checkin
                . " AND integral_time>=$today_start";
        $dao->setWhere( $where );
        $integral_count = $dao->getCountByWhere();
        if ( $integral_count > 0 ) {
            $this->errorMessage = '您今天已经签到过了';
            return false;
        }
        return true;
    }

    /**
     * 取买家的订单列表
     * $this->where;
     * $this->getMemberIntegralList();
     */
    public function getMemberIntegralList()
    {
        $integral_dao = dao_factory_base::getIntegralDao();

        $where = "uid={$this->uid}";
        $integral_dao->setWhere( $where );
        $count = $integral_dao->getCountByWhere();

        if ( $count === false ) {
            $retmsg = 0;
        } else {
            $retmsg = 1; //业务返回信息
        }
        $pages = $this->P( 'Pages' );
        $pages->setTotal( $count );
        $pages->setPrepage( $this->pagesize );
        $limit = $pages->getSqlLimit();

        $order_info_array = array();
        if ( $count > 0 ) {
            $integral_dao->setLimit( $limit );
            $integral_dao->setOrderby( 'integral_id DESC' );
            $res = $integral_dao->getListByWhere();

            $integral_type_config = Tmac::config( 'integral.integral.integral_type', APP_BASE_NAME );
            $integral_class_config = Tmac::config( 'integral.integral.integral_class', APP_BASE_NAME );
            foreach ( $res as $value ) {
                $value->integral_time = date( 'Y-m-d H:i:s', $value->integral_time );
                $value->integral_type_text = $integral_type_config[ $value->integral_type ];
                $value->integral_class_text = $integral_class_config[ $value->integral_type ][ $value->integral_class ];
                $order_info_array[] = $value;
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
            'retcode' => 'integral_list',
            'retmsg' => $retmsg,
            'reqdata' => $order_info_array,
        );
        return $return;
    }

}
