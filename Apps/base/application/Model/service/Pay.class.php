<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Pay.class.php 1013 2017-04-09 20:09:59Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace base\service;

use \service_Model_base;
use \dao_factory_base;
use \service_Member_base;
use \service_Member_mobile;
use \TmacDbExpr;
use base\entity\MemberSetting;
use base\service\trade\Base as Trade;

class Pay extends service_Model_base
{

    /**
     * 人民币兑换富豪币的汇率
     */
    const exchange_rate = 100;

    /**
     * 支付单类型
     * 订单支付
     */
    const order_type_order = 0;

    /**
     * 支付单类型
     * 充值支付
     */
    const order_type_pay = 1;

    /**
     * 充值单状态
     * 待付款
     */
    const pay_status_unpay = 1;

    /**
     * 充值单状态
     * 付款失败|取消充值单
     */
    const pay_status_pay_close = 2;

    /**
     * 充值单状态
     * 付款成功
     */
    const pay_status_pay_success = 3;

    protected $errorMessage;
    protected $trade_vendor;
    protected $trade_no;
    protected $entity_PayInfo;
    protected $memberInfo;

    function getErrorMessage()
    {
        return $this->errorMessage;
    }

    function setTrade_vendor( $trade_vendor )
    {
        $this->trade_vendor = $trade_vendor;
    }

    function setTrade_no( $trade_no )
    {
        $this->trade_no = $trade_no;
    }

    function setEntity_PayInfo( $entity_PayInfo )
    {
        $this->entity_PayInfo = $entity_PayInfo;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getPayInfoById( $pay_id )
    {
        $dao = dao_factory_base::getPayDao();
        $dao->setPk( $pay_id );
        return $dao->getInfoByPk();
    }

    /**
     * 充值订单支付成功，需要处理的事务｜商品库存减少，订单状态修改
     * $this->trade_vendor;
     * $this->trade_no;
     * $this->orderPaySuccess($entity_Pay_base);
     */
    public function orderPaySuccess()
    {
        $pay_dao = dao_factory_base::getPayDao();
        $pay_dao->getDb()->startTrans();

        //修改订单状态和支付状态 order_info表        
        $entity_Pay_base = new \base\entity\Pay();

        $entity_Pay_base->pay_status = self::pay_status_pay_success;
        $entity_Pay_base->trade_vendor = $this->trade_vendor;
        $entity_Pay_base->trade_no = $this->trade_no;
        $entity_Pay_base->pay_time = empty( $this->pay_time ) ? $this->now : $this->pay_time;

        $pay_dao->setPk( $this->entity_PayInfo->pay_id );
        $pay_dao->updateByPk( $entity_Pay_base );

        //member_setting 表中的money相关字段更新
        $this->updateMemberSettingMoney();
        //member_bill 会员账单表更新 写入购物券的变动日志
        $this->insertMemberBill();
        //tarde 写入富豪币的变动日志
        $this->insertTradeExchange();
        /**
         * if ( $spec_value_map_dao->getDb()->isSuccess() && $spec_value_map_dao->getDb()->getNumRows() > 0 ) {
         */
        if ( $pay_dao->getDb()->isSuccess() ) {
            $pay_dao->getDb()->commit();
            return true;
        } else {
            $pay_dao->getDb()->rollback();
            return false;
        }
    }

    /**
     * member_setting 表中的money相关字段更新
     * @2015-07-08增加 判断如果有分销商。分别向分销商和供应商写入账户金额变动
     * @return type
     */
    private function updateMemberSettingMoney()
    {
        $entity_Pay_base = $this->entity_PayInfo;
        $member_setting_dao = dao_factory_base::getMemberSettingDao();

        $entity_MemberSetting_base = new MemberSetting();
        $fh_currency = ($entity_Pay_base->pay_amount + $entity_Pay_base->voucher_money) * self::exchange_rate;
        $history_fh_currency = $entity_Pay_base->pay_amount * self::exchange_rate;

        $entity_MemberSetting_base->current_fh_currency = new TmacDbExpr( 'current_fh_currency+' . $fh_currency );
        $entity_MemberSetting_base->history_fh_currency = new TmacDbExpr( 'history_fh_currency+' . $history_fh_currency );
        //富豪币充值=单位（富豪币）人民币充值的富豪币【购物券，人民币充值=> 富豪币】
        $entity_MemberSetting_base->fh_recharge_amount = new TmacDbExpr( 'fh_recharge_amount+' . $fh_currency );
        if ( $entity_Pay_base->voucher_money > 0 ) {
            $entity_MemberSetting_base->voucher_money = new TmacDbExpr( 'voucher_money-' . $entity_Pay_base->voucher_money );
        }
        //更新卖家的金钱 商品供应商UID
        $member_setting_dao->setPk( $entity_Pay_base->uid );
        $member_setting_dao->updateByPk( $entity_MemberSetting_base );

        return true;
    }

    /**
     * member_bill 会员账单表更新
     * @2015-07-08增加 判断如果有分销商。分别向分销商和供应商写入账户金额变动 历史记录
     * @return type
     */
    private function insertMemberBill()
    {
        $entity_Pay_base = $this->entity_PayInfo;
        $member_bill_dao = dao_factory_base::getMemberBillDao();

        $order_complete = service_Member_base::order_complete_no;
        $order_finish = service_Member_base::order_finish_no;
        $confirm_time = $this->now;

        $member_model = new service_Member_mobile();
        $memberInfo = $this->memberInfo = $member_model->getMemberInfoByUid( $entity_Pay_base->uid );

        $entity_MemberBill_base = new \base\entity\MemberBill();
        if ( $entity_Pay_base->pay_amount > 0 ) {
            $fh_currency = ($entity_Pay_base->pay_amount * self::exchange_rate);
            $bill_note = "充值魅力值[{$fh_currency}个][￥{$entity_Pay_base->pay_amount}]";
            //富豪币写入            
            $entity_MemberBill_base->uid = $entity_Pay_base->uid;
            $entity_MemberBill_base->order_id = 0;
            $entity_MemberBill_base->money = $entity_Pay_base->pay_amount;
            $entity_MemberBill_base->bill_type = service_Member_base::bill_type_income;
            $entity_MemberBill_base->bill_type_class = service_Member_base::bill_type_class_business; //自营 或 收银台
            $entity_MemberBill_base->bill_expend_type = service_Member_base::bill_expend_type_no;
            $entity_MemberBill_base->bill_note = $bill_note;
            $entity_MemberBill_base->bill_time = $this->now;
            $entity_MemberBill_base->trade_vendor = $this->trade_vendor;
            $entity_MemberBill_base->trade_no = $this->trade_no;
            $entity_MemberBill_base->order_complete = $order_complete;
            $entity_MemberBill_base->order_finish = $order_finish;
            $entity_MemberBill_base->bill_uid = $entity_Pay_base->uid;
            $entity_MemberBill_base->bill_realname = $memberInfo->nickname;
            $entity_MemberBill_base->bill_image_id = $memberInfo->member_image_id;
            $entity_MemberBill_base->confirm_time = $confirm_time;
            $entity_MemberBill_base->pay_id = $entity_Pay_base->pay_id;
            $entity_MemberBill_base->bill_class = service_Member_base::bill_class_fh_currency;
            $entity_MemberBill_base->fh_currency = $fh_currency;
            $member_bill_dao->insert( $entity_MemberBill_base );
        }
        if ( $entity_Pay_base->voucher_money == 0 ) {
            return true;
        }
        $fh_currency = $entity_Pay_base->voucher_money * self::exchange_rate;
        $bill_note = "充值魅力值[{$fh_currency}个][使用购物券{$entity_Pay_base->voucher_money}]";
        //购物券      
        $entity_MemberBill_base->uid = $entity_Pay_base->uid;
        $entity_MemberBill_base->order_id = 0;
        $entity_MemberBill_base->money = -$entity_Pay_base->voucher_money; //消费的购物券
        $entity_MemberBill_base->bill_type = service_Member_base::bill_type_expend;
        $entity_MemberBill_base->bill_type_class = service_Member_base::bill_type_class_no; //自营 或 收银台
        $entity_MemberBill_base->bill_expend_type = service_Member_base::bill_expend_type_coupon_consume;
        $entity_MemberBill_base->bill_note = $bill_note;
        $entity_MemberBill_base->bill_time = $this->now;
        $entity_MemberBill_base->trade_vendor = $this->trade_vendor;
        $entity_MemberBill_base->trade_no = $this->trade_no;
        $entity_MemberBill_base->order_complete = $order_complete;
        $entity_MemberBill_base->order_finish = $order_finish;
        $entity_MemberBill_base->bill_uid = $entity_Pay_base->uid;
        $entity_MemberBill_base->bill_realname = $memberInfo->nickname;
        $entity_MemberBill_base->bill_image_id = $memberInfo->member_image_id;
        $entity_MemberBill_base->confirm_time = $confirm_time;
        $entity_MemberBill_base->pay_id = $entity_Pay_base->pay_id;
        $entity_MemberBill_base->bill_class = service_Member_base::bill_class_voucher;
        $entity_MemberBill_base->fh_currency = $fh_currency;
        $member_bill_dao->insert( $entity_MemberBill_base );
        return true;
    }

    /**
     * 写入富豪币兑换日志
     */
    public function insertTradeExchange()
    {
        $trade_dao = \dao_factory_base::getTradeDao();
        $entity_Pay_base = $this->entity_PayInfo;
        if ( $entity_Pay_base->pay_amount > 0 ) {
            $fh_currency = ($entity_Pay_base->pay_amount * self::exchange_rate);
            $bill_note = "充值魅力值[{$fh_currency}个][￥{$entity_Pay_base->pay_amount}]";

            $entity_Trade = new \base\entity\Trade();
            $entity_Trade->uid = $this->memberInfo->uid;
            $entity_Trade->nickname = $this->memberInfo->nickname;
            $entity_Trade->trade_status = Trade::trade_status_settle;
            $entity_Trade->trade_type = Trade::trade_type_recharge;
            $entity_Trade->trade_class = Trade::trade_class_recharge_by_pay;
            $entity_Trade->trade_source = Trade::trade_source_default;
            $entity_Trade->fh_currency = $fh_currency;
            $entity_Trade->create_time = $this->now;
            $entity_Trade->settle_time = $this->now;
            $entity_Trade->execute_settle_time = $this->now;
            $entity_Trade->trade_note = $bill_note;
            $entity_Trade->voucher_money = 0;
            $entity_Trade->pay_amount = $entity_Pay_base->pay_amount;
            $trade_dao->insert( $entity_Trade );
        }
        if ( $entity_Pay_base->voucher_money == 0 ) {
            return true;
        }

        $fh_currency = ($entity_Pay_base->voucher_money * self::exchange_rate);
        $bill_note = "充值魅力值[{$fh_currency}个][使用购物券{$entity_Pay_base->voucher_money}]";

        $entity_Trade = new \base\entity\Trade();
        $entity_Trade->uid = $this->memberInfo->uid;
        $entity_Trade->nickname = $this->memberInfo->nickname;
        $entity_Trade->trade_status = Trade::trade_status_settle;
        $entity_Trade->trade_type = Trade::trade_type_recharge;
        $entity_Trade->trade_class = Trade::trade_class_recharge_by_VoucherMoney;
        $entity_Trade->trade_source = Trade::trade_source_default;
        $entity_Trade->fh_currency = $fh_currency;
        $entity_Trade->create_time = $this->now;
        $entity_Trade->settle_time = $this->now;
        $entity_Trade->execute_settle_time = $this->now;
        $entity_Trade->trade_note = $bill_note;
        $entity_Trade->voucher_money = -$entity_Pay_base->voucher_money;
        return $trade_dao->insert( $entity_Trade );
    }

}
