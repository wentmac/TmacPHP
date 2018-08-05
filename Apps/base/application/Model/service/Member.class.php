<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Member.class.php 998 2017-03-31 05:36:57Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class service_Member_base extends service_Model_base
{

    /**
     * 库存设置状态
     * 1：拍下减库存
     */
    const stock_setting_order_save = 1;

    /**
     * 库存设置状态
     * 2：付款减库存
     */
    const stock_setting_order_pay = 2;

    /**
     * 账单资金变动类型 收入
     */
    const bill_type_income = 1;

    /**
     * 账单资金变动类型 支出
     */
    const bill_type_expend = 2;

    /**
     * 当bill_type=1时（收入）
     * 自营收入
     */
    const bill_type_class_no = 0;

    /**
     * 当bill_type=1时（收入）
     * 自营收入
     */
    const bill_type_class_business = 1;

    /**
     * 当bill_type=1时（收入）
     * 代销收入
     */
    const bill_type_class_wholesale = 2;

    /**
     * 当bill_type=1时（收入）
     * 直接收款（收银台）
     */
    const bill_type_class_receivable = 3;

    /**
     * 当bill_type=1时（收入）
     * 系统佣金
     */
    const bill_type_class_system = 4;

    /**
     * 当bill_type=1时（收入）
     * 购物券
     */
    const bill_type_class_coupon = 5;

    /**
     * 当bill_type=1时（收入）
     * 富豪币兑换红包
     */
    const bill_type_class_LuckyMoney = 6;

    /**
     * 当bill_type=1时（收入）
     * 连玩8局送红包
     */
    const bill_type_class_continue_trade_give_LuckyMoney = 7;

    /**
     * 没有金额流水
     */
    const bill_expend_type_no = 0;

    /**
     * 提现
     */
    const bill_expend_type_withdrawals = 1;

    /**
     * 退款
     */
    const bill_expend_type_refund = 2;

    /**
     * 佣金退款
     */
    const bill_expend_type_commission_refund = 3;

    /**
     * 购物券退款
     */
    const bill_expend_type_coupon_refund = 4;

    /**
     * 购物券消费
     */
    const bill_expend_type_coupon_consume = 5;

    /**
     * 富豪币
     */
    const bill_expend_type_coupon_currency = 6;

    /**
     * 提现 系统手续费
     */
    const bill_expend_type_withdrawals_commission_charge = 7;

    /**
     * 账单子类型
     * 金钱
     */
    const bill_class_money = 0;

    /**
     * 账单子类型
     * 购物券
     */
    const bill_class_voucher = 1;

    /**
     * 账单子类型
     * 充值|富豪币的流水
     */
    const bill_class_fh_currency = 2;

    /**
     * 订单是否完成
     * 未完成
     * 等待买家确认收货
     */
    const order_complete_no = 0;

    /**
     * 订单是否完成
     * 已经完成
     * 买家已经确认收货
     */
    const order_complete_yes = 1;

    /**
     * 订单是否完成
     * 已经完成
     * 订单有售后问题处理中
     */
    const order_complete_refund = 2;

    /**
     * 用户类型
     * 普通用户[买家+分销商]
     */
    const member_type_seller = 1;

    /**
     * 用户类型
     * 供应商
     */
    const member_type_supplier = 2;

    /**
     * 用户类型
     * 买家身份 不能登录app和供应商后台
     */
    const member_type_buyer = 3;

    /**
     * 用户类型
     * 商城用户
     * 代理商申请审核中
     */
    const member_type_mall = 4;

    /**
     * 用户类型
     * 代理商
     */
    const member_type_dfh_agent = 5;

    /**
     * 分销商
     * 免费
     * @var type
     */
    const member_class_seller_free = 0;

    /**
     * 分销商
     * VIP
     * @var type
     */
    const member_class_seller_vip = 1;

    /**
     * 分销商
     * SVIP
     * @var type
     */
    const member_class_seller_svip = 2;

    /**
     * 供应商
     * 免费供应商
     * @var type
     */
    const member_class_supplier_free = 0;

    /**
     * 供应商
     * 铜牌
     * @var type
     */
    const member_class_supplier_copper = 1;

    /**
     * 供应商
     * 银牌
     * @var type
     */
    const member_class_supplier_silver = 2;

    /**
     * 供应商
     * 金牌
     * @var type
     */
    const member_class_supplier_gold = 3;

    /**
     * 聚店商城
     * 省代
     * @var type
     */
    const member_class_mall_province = 1;

    /**
     * 聚店商城
     * 市代
     * @var type
     */
    const member_class_mall_city = 2;

    /**
     * 聚店商城
     * 普代
     * @var type
     */
    const member_class_mall_general = 3;

    /**
     * 会员类型
     * 白金会员
     * @var type
     */
    const member_class_platinum = 1;

    /**
     * 大富豪代理
     * 总代
     * @var type
     */
    const member_class_dfh_agent_general = 1;

    /**
     * 大富豪代理
     * 省代
     * @var type
     */
    const member_class_dfh_agent_province = 2;

    /**
     * 大富豪代理
     * 市代
     * @var type
     */
    const member_class_dfh_agent_city = 3;

    /**
     * 大富豪代理
     * 区县代理
     * @var type
     */
    const member_class_dfh_agent_district = 4;

    /**
     * 订单完结状态
     * 0：未完结[未结算收入]
     */
    const order_finish_no = 0;

    /**
     * 订单完结状态
     * 已经完结[已结算收入]
     */
    const order_finish_yes = 1;

    /**
     * 用户锁定类型
     * 未锁定
     */
    const locked_type_none = 0;

    /**
     * 用户锁定类型
     * 提现锁定
     */
    const locked_type_settle = 1;

    /**
     * 提现执行状态
     * 申请提现
     */
    const is_execute_default = 0;

    /**
     * 提现执行状态
     * 成功
     */
    const is_execute_success = 1;

    /**
     * 提现执行状态
     * 申请提现失败
     */
    const is_execute_fail = 2;

    /**
     * 聚店店铺UID
     */
    const yph_uid = 46;

    /**
     * 排位赛 的粉丝额度
     * 排位赛中一个会员下面 排位粉丝的数量
     */
    const rank_limit = 3;

    /**
     * 会员级别
     * 1级
     */
    const member_level_1 = 1;

    /**
     * 会员级别
     * 1级
     */
    const member_level_2 = 2;

    /**
     * 会员级别
     * 3级
     */
    const member_level_3 = 3;

    /**
     * 会员级别
     * 4级
     */
    const member_level_4 = 4;

    /**
     * 会员级别
     * 5级
     */
    const member_level_5 = 5;

    /**
     * 会员级别
     * 6级
     */
    const member_level_6 = 6;

    /**
     * 会员级别
     * 7级
     */
    const member_level_7 = 7;

    /**
     * 会员级别
     * 8级
     */
    const member_level_8 = 8;

    /**
     * 会员级别
     * 9级
     */
    const member_level_9 = 9;

    /**
     * 东家ID更换锁(0:可以换)
     */
    const agent_lock_no = 0;

    /**
     * 东家ID更换锁
     * (不能换了,只要成功购买一次lv1后都不能再换东家ID了)
     */
    const agent_lock_yes = 1;

    /**
     * 企业付款到个人
     * 正常
     */
    const settle_status_success = 0;

    /**
     * 企业付款到个人
     * 异常
     */
    const settle_status_error = 1;

    /**
     * 扫码注册后给多少积分
     * 5个积分
     * @var type
     */
    const agent_integral_value = 5;

    /**
     * 签到多少积分
     * 1个积分
     * @var type
     */
    const checkin_integral_value = 1;

    /**
     * 扫码注册得到的积分
     * 10个积分
     * @var type
     */
    const agent_register_integral_value = 50;

    /**
     * 最大积分余额
     * 超过50分就不能再获得积分了
     */
    const available_max_integral = 50;

    /**
     * 历史总积分
     * 100个积分
     * @var type
     */
    const agent_integral_max_value = 100;

    /**
     * 每天最多的积分
     * 20个积分
     * @var type
     */
    const agent_integral_day_max_value = 50;

    /**
     * yph_member_level.member_level_type
     * 直推人
     */
    const member_level_type_recommend = 1;

    /**
     * yph_member_level.member_level_type
     * 代理商
     */
    const member_level_type_agent = 2;


    /**
     * 修改代理后重置member_setting.agent_uid_recent
     * redis队列中的key
     */
    const reset_agent_uid_recent_key = 'reset_agent_uid_recent';

    protected $uid;
    protected $errorMessage;

    function setUid( $uid )
    {
        $this->uid = $uid;
    }

    function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 通过UID取member
     * @param type $uid
     * @param type $field
     * @return type
     */
    public function getMemberInfoByUid( $uid, $field = '*' )
    {
        $dao = dao_factory_base::getMemberDao();
        $dao->setPk( $uid );
        $dao->setField( $field );
        return $dao->getInfoByPk();
    }

    /**
     * 通过UID取member
     * @param type $uid
     * @return type
     */
    public function getMemberInfoByMobile( $mobile )
    {
        $dao = dao_factory_base::getMemberDao();
        $where = "mobile='{$mobile}'";
        $dao->setWhere( $where );
        return $dao->getInfoByWhere();
    }

    /**
     * 通过UID取member
     * @param type $uid
     * @return type
     */
    public function getMemberSettingInfoByUid( $uid, $field = '*' )
    {
        $dao = dao_factory_base::getMemberSettingDao();
        return $dao->setPk( $uid )->setField( $field )->getInfoByPk();
    }

    /**
     * 更新会员信息表
     * @param \base\entity\Member $entity_Member_base
     */
    public function updateMemberInfo( \base\entity\Member $entity_Member_base )
    {
        if ( !empty( $entity_Member_base->dfh_agent_uid ) ) {
            $dfh_agent_info = $this->getMemberInfoByUid( $entity_Member_base->dfh_agent_uid );
            if ( empty( $dfh_agent_info ) || $dfh_agent_info->member_type <> self::member_type_dfh_agent ) {
                $entity_Member_base->dfh_agent_uid = 0;
            }
        }

        $dao = dao_factory_base::getMemberDao();
        $member_setting_dao = dao_factory_base::getMemberSettingDao();

        $dao->getDb()->startTrans();

        $dao->setpk( $this->uid );
        $dao->updateByPk( $entity_Member_base );

        $entity_MemberSetting_base = new \base\entity\MemberSetting();
        $entity_MemberSetting_base->member_type = $entity_Member_base->member_type;
        $entity_MemberSetting_base->member_class = $entity_Member_base->member_class;

        $member_setting_dao->setPk( $this->uid );
        $member_setting_dao->updateByPk( $entity_MemberSetting_base );

        /**
         * if ( $spec_value_map_dao->getDb()->isSuccess() && $spec_value_map_dao->getDb()->getNumRows() > 0 ) {
         */
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
     * 更新会员信息表
     * @param entity_MemberSetting_base $entity_MemberSetting_base
     */
    public function updateMemberSettingInfo( \base\entity\MemberSetting $entity_MemberSetting_base )
    {
        $dao = dao_factory_base::getMemberSettingDao();
        $dao->setpk( $this->uid );
        return $dao->updateByPk( $entity_MemberSetting_base );
    }

    /**
     * 更新用户信息
     * @param \base\entity\Member $entity_Member_base
     * @param \base\entity\MemberSetting $entity_MemberSetting_base
     */
    public function updateMember( \base\entity\Member $entity_Member_base, \base\entity\MemberSetting $entity_MemberSetting_base )
    {
        $dao = dao_factory_base::getMemberDao();
        $member_setting_dao = dao_factory_base::getMemberSettingDao();

        $dao->getDb()->startTrans();

        $dao->setpk( $this->uid );
        $dao->updateByPk( $entity_Member_base );


        $member_setting_dao->setPk( $this->uid );
        $member_setting_dao->updateByPk( $entity_MemberSetting_base );

        /**
         * if ( $spec_value_map_dao->getDb()->isSuccess() && $spec_value_map_dao->getDb()->getNumRows() > 0 ) {
         */
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
     * 抓虫用的，抓到后再删除
     * @param $member_bill_id
     */
    public function updateMemberBillCurrentMoneyByID( $uid, $member_bill_id )
    {
        $entity_MemberBill_base = new \base\entity\MemberBill();
        $entity_MemberBill_base->current_money = $this->getMemberMoneyOverage( $uid );
        $entity_MemberBill_base->ms_current_money = $this->getMemberSettingCurrentMoney( $uid );

        $dao = dao_factory_base::getMemberBillDao();
        $dao->setpk( $member_bill_id );
        return $dao->updateByPk( $entity_MemberBill_base );
    }

    /**
     * 取真实价格
     * @param type $uid
     * @return type
     */
    private function getMemberMoneyOverage( $uid )
    {
        $dao = dao_factory_base::getMemberBillDao();
        $dao->setField( 'SUM(money) AS money' );
        $where = "uid={$uid} AND order_finish=" . service_Member_base::order_finish_yes . " AND bill_class=" . service_Member_base::bill_class_money;
        $dao->setWhere( $where );
        $info = $dao->getInfoByWhere();
        return $info->money;
    }

    /**
     * 取member_setting表中的 current_money
     * @param $uid
     */
    private function getMemberSettingCurrentMoney( $uid )
    {
        $dao = dao_factory_base::getMemberSettingDao();
        $member_setting_info = $dao->setPk( $uid )->setField( 'current_money' )->getInfoByPk();
        return $member_setting_info->current_money;
    }

}
