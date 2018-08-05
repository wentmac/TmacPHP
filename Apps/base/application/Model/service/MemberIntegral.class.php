<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: MemberIntegral.class.php 439 2016-10-04 09:47:05Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class service_MemberIntegral_base extends service_Model_base
{

    /**
     * 积分类型
     * 积分增加
     */
    const integral_type_increase = 1;

    /**
     * 积分类型
     * 积分消耗
     */
    const integral_type_consume = 2;

    /**
     * 积分类型
     * 积分增加
     * 注册送的积分
     */
    const integral_class_increase_register = 1;

    /**
     * 积分类型
     * 积分增加
     * 推广积分
     */
    const integral_class_increase_agent = 2;

    /**
     * 积分类型
     * 积分增加
     * 赠送积分
     */
    const integral_class_increase_give = 3;

    /**
     * 积分类型
     * 积分增加
     * 签到送积分
     */
    const integral_class_increase_checkin = 4;

    /**
     * 积分类型
     * 积分消耗
     * 购物抵扣
     */
    const integral_class_consume_shopping = 1;

    /**
     * 无积分签到
     * yes     
     */
    const integral_zero_yes = 1;

    /**
     * 无积分签到
     * no
     */
    const integral_zero_no = 0;

    protected $errorMessage;

    function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 更新旧的数据
     */
    public function updateMemberHistoryIntegral()
    {
        $member_dao = dao_factory_base::getMemberDao();
        $member_dao->setField( 'uid,available_integral,sum_integral,agent_integral,reg_time' );
        $where = 'sum_integral>=1';
        $member_dao->setWhere( $where );
        $member_array = $member_dao->getListByWhere();

        $integral_dao = dao_factory_base::getIntegralDao();

        foreach ( $member_array as $value ) {
            $entity_Integral_base = new \base\entity\Integral();
            $entity_Integral_base->integral = $value->sum_integral;
            $entity_Integral_base->uid = $value->uid;
            $entity_Integral_base->integral_type = service_MemberIntegral_base::integral_type_increase;
            $entity_Integral_base->integral_class = service_MemberIntegral_base::integral_class_increase_give;
            $entity_Integral_base->integral_time = $value->reg_time;
            $integral_dao->insert( $entity_Integral_base );

            $consume_integral = $value->sum_integral - $value->available_integral;
            if ( empty( $consume_integral ) ) {
                continue;
            }
            $entity_Integral_base->integral = -$consume_integral;
            $entity_Integral_base->uid = $value->uid;
            $entity_Integral_base->integral_type = service_MemberIntegral_base::integral_type_consume;
            $entity_Integral_base->integral_class = service_MemberIntegral_base::integral_class_consume_shopping;
            $entity_Integral_base->integral_time = $value->reg_time;
            $integral_dao->insert( $entity_Integral_base );
        }
    }

    /**
     * 帐户额度不足 50的全部送到 50
     */
    public function giveMemberIntegral()
    {
        $member_dao = dao_factory_base::getMemberDao();
        $member_dao->setField( 'uid,available_integral,sum_integral,agent_integral,reg_time' );
        $where = 'available_integral<' . service_Member_base::available_max_integral;
        $member_dao->setWhere( $where );
        $member_array = $member_dao->getListByWhere();

        $integral_dao = dao_factory_base::getIntegralDao();

        foreach ( $member_array as $value ) {
            $integral = service_Member_base::agent_register_integral_value - $value->available_integral;

            $entity_Integral_base = new \base\entity\Integral();
            $entity_Integral_base->integral = $integral;
            $entity_Integral_base->uid = $value->uid;
            $entity_Integral_base->integral_type = service_MemberIntegral_base::integral_type_increase;
            $entity_Integral_base->integral_class = service_MemberIntegral_base::integral_class_increase_give;
            $entity_Integral_base->integral_time = $this->now;
            $integral_dao->insert( $entity_Integral_base );

            $entity_Member_base = new \base\entity\Member();
            $entity_Member_base->available_integral = new TmacDbExpr( 'available_integral+' . $integral );
            $entity_Member_base->sum_integral = new TmacDbExpr( 'sum_integral+' . $integral );
            $member_dao->setPk( $value->uid );
            $member_dao->updateByPk( $entity_Member_base );
        }
    }
 
}
