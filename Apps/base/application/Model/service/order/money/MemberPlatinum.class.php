<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: MemberPlatinum.class.php 439 2016-10-04 09:47:05Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class service_order_money_MemberPlatinum_base extends service_Member_base
{

    /**
     * 退款操作     
     */
    protected $agent_array;
    protected $batch_no;
    protected $refund_id;
    protected $entity_OrderRefund;
    protected $total_fee;
    /////

    private $orderInfo;
    private $goods_member_level;
    private $memberInfo;
    private $agent_vip_status;
    private $agent_rank_uid;
    protected $trade_vendor;
    protected $trade_no;

    /**
     * 当前会员uid
     * @var type 
     */
    private $member_uid;
    private $rank_uid_array;
    protected $goods_count;

    function setBatch_no( $batch_no )
    {
        $this->batch_no = $batch_no;
    }

    function setRefund_id( $refund_id )
    {
        $this->refund_id = $refund_id;
    }

    function setEntity_OrderRefund( $entity_OrderRefund )
    {
        $this->entity_OrderRefund = $entity_OrderRefund;
    }

    function setTotal_fee( $total_fee )
    {
        $this->total_fee = $total_fee;
    }

    function setTrade_vendor( $trade_vendor )
    {
        $this->trade_vendor = $trade_vendor;
    }

    function setTrade_no( $trade_no )
    {
        $this->trade_no = $trade_no;
    }

    function setOrderInfo( $orderInfo )
    {
        $this->orderInfo = $orderInfo;
    }

    function setGoods_member_level( $goods_member_level )
    {
        $this->goods_member_level = $goods_member_level;
    }

    public function __construct()
    {
        parent::__construct();
        $this->orderInfo instanceof entity_OrderInfo_base;
    }

    /**
     * 会员商品购买后.付款后
     * 付款后|更新member表中的member_level
     * ---------------------------------
      直推佣金
      --上家如果是会员
      ----直推的agent_uid给直推佣金
      --上家还不是会员
      ----不给直推佣金
     * ---------------------------------
     * 排位佣金
      --上家如果是会员
      ----直接从直接人下面开始排。上到下，左到右。|设置排位
      ----第一级的给排位佣金|付排位佣金
      --上家还不是会员
      ----直接从系统0级下面开始排，上到下，左到右。
     */
    public function init()
    {
        if ( $this->orderInfo->order_type <> service_Order_base::order_type_member_platinum ) {
            return true;
        }
        //判断上家是不是会员
        $this->checkAgentUidIsVIP();
        //付款后|更新member表中的member_level
        $this->updateMemberLevel();
        //直推佣金
        //$this->handleAgent();
        //排位佣金
        $this->handleRank();
        //更新订单的直推佣金uid和排位佣金的uid
        //$this->updateOrderInfoAgent();
        //member_setting 表中的money相关字段更新
        $this->updateMemberSettingMoney();
        //member_bill 会员账单表更新
        $this->insertMemberBill();
    }

    /**
     * 判断上家是不是会员
     */
    private function checkAgentUidIsVIP()
    {
        $dao = dao_factory_base::getMemberDao();
        $dao->setPk( $this->orderInfo->uid );
        $this->memberInfo = $dao->getInfoByPk();

        $dao->setPk( $this->memberInfo->agent_uid );
        $agent_member_info = $dao->getInfoByPk();
        $this->agent_vip_status = false;
        if ( $agent_member_info && $agent_member_info->member_level > 0 ) {
            $this->agent_vip_status = true;
        }
        return $this->agent_vip_status;
    }

    /**
     * 处理直推及佣金
     */
    private function handleAgent()
    {
        //判断上家是不是会员
        if ( $this->agent_vip_status ) {
            //是会员 给直接佣金
            //insert $this->orderInfo->commission_fee;
            //member_setting 表中的money相关字段更新
            //$this->updateMemberSettingMoney();
            //member_bill 会员账单表更新
            //$this->insertMemberBill();
            return true;
        } else {
            //不是会员.没有直推佣金喽
            return true;
        }
    }

    /**
     * 取排位奖会员的uid_string
     */
    private function handleRank()
    {
        //设层级排位
        $member_tree_model = new service_member_Tree_base();
        $member_tree_model->setMemberInfo( $this->memberInfo );
        //判断会员有没有 agent_rank_uid 时才去设置排位|有的话（不用设置排位）
        if ( empty( $this->memberInfo->agent_rank_uid ) ) {
            if ( $this->agent_vip_status ) {
                //直接从直接人下面开始排。上到下，左到右。|设置排位
                //第一级的给排位佣金|付排位佣金
                $member_tree_model->modifyAgentRankUid( $this->memberInfo->agent_uid );
            } else {
                //直接从系统0级下面开始排，上到下，左到右
                $member_tree_model->modifyAgentRankUid();
            }
            $this->memberInfo->agent_rank_uid = $member_tree_model->getAgent_rank_uid();
        }
        /**
         * 取购买普通会员上面9个人只要级别大于等于该层维即得3元购物券排位奖励， 该层级必须大于等层数 
         * 购买钻石会员上面9个人只要级别大于等于该层维即得33元购物券排位奖励.
         */
        $this->rank_uid_array = $rank_uid = array();
        $this->member_uid = $this->memberInfo->agent_rank_uid;
        for ( $i = 1; $i <= 9; $i++ ) {
            $uid = $this->getAgentMemberInfo( $this->member_uid, $i );
            if ( $uid === false ) {
                //找到顶级了跳出
                break;
            } else if ( $uid > 0 ) {
                //给券
                $rank_uid[] = $uid;
            }
        }
        if ( !empty( $rank_uid ) ) {
            $this->agent_rank_uid = implode( ',', $rank_uid );
            $this->rank_uid_array = $rank_uid;
        } else {
            $this->agent_rank_uid = false;
        }
        //todo 打钱
        return true;
    }

    /**
     * 
     * @param type $uid
     * @param type $level 当前第几层
     * @return boolean|int
     */
    private function getAgentMemberInfo( $uid, $level )
    {
        $dao = dao_factory_base::getMemberDao();
        $dao->setPk( $uid );
        $dao->setField( 'uid,agent_uid,agent_rank_uid,member_level' );
        $member_info = $dao->getInfoByPk();
        if ( $member_info && $member_info->agent_rank_uid > 0 ) {
            $this->member_uid = $member_info->agent_rank_uid;
            //上级的推荐人级别大于等于用户的级别时。给购物券。
            if ( $member_info->member_level >= $level ) {
                return $member_info->uid;
            }
            //推荐人的级别不大于当前的级别。不返购物券。
            return 0;
        } else {
            return false;
        }
    }

    /**
     * 付款后|更新member表中的member_level
     */
    private function updateMemberLevel()
    {
        //如果当前的会员级别小于会员商品级别就不用更新
        if ( $this->memberInfo->member_level > $this->goods_member_level ) {
            return true;
        }
        $dao = dao_factory_base::getMemberDao();
        $dao->setPk( $this->orderInfo->uid );

        $entity_Member_base = new \base\entity\Member();
        $entity_Member_base->member_level = $this->goods_member_level;
        $entity_Member_base->member_class = service_Member_base::member_class_platinum;                
        return $dao->updateByPk( $entity_Member_base );
    }

    /**
     * 更新订单的直推佣金uid和排位佣金的uid     
     * @return type
     */
    private function updateOrderInfoAgent()
    {
        $order_info_dao = dao_factory_base::getOrderInfoDao();
        $entity_OrderInfo_base = new \base\entity\OrderInfo();
        if ( $this->agent_vip_status ) {
            $entity_OrderInfo_base->agent_uid = $this->memberInfo->agent_uid;
        } else {
            $entity_OrderInfo_base->agent_uid = 0;
        }
        if ( $this->agent_rank_uid !== false ) {
            $entity_OrderInfo_base->rank_uid = $this->agent_rank_uid;
        }
        $order_info_dao->setPk( $this->orderInfo->order_id );
        $order_info_dao->updateByPk( $entity_OrderInfo_base );
        return true;
    }

    /**
     * member_setting 表中的money相关字段更新
     * @2015-07-08增加 判断如果有分销商。分别向分销商和供应商写入账户金额变动
     * @return type
     */
    private function updateMemberSettingMoney()
    {
        $entity_OrderInfo_base = $this->orderInfo;
        $order_goods_array = unserialize( $entity_OrderInfo_base->order_goods_detail );
        $this->goods_count = count( $order_goods_array );

        $member_setting_dao = dao_factory_base::getMemberSettingDao();
        $entity_MemberSetting_base = new \base\entity\MemberSetting();
        //判断上家是不是会员
        if ( $this->agent_vip_status ) {
            //是会员 给直接佣金
            $commission_fee = $entity_OrderInfo_base->commission_fee; //直推佣金
            $entity_MemberSetting_base->history_money = new TmacDbExpr( 'history_money+' . $commission_fee );
            $member_setting_dao->setPk( $this->memberInfo->agent_uid );
            $member_setting_dao->updateByPk( $entity_MemberSetting_base );
        } else {
            $commission_fee = 0;
        }
        //判断给不给排位佣金
        if ( $this->agent_rank_uid === false ) {
            $commission_fee_rank = 0;
        } else {
            $order_coupon_map = Tmac::config( 'order.order.coupon', APP_BASE_NAME );
            $commission_fee_rank = $order_coupon_map[ $this->orderInfo->goods_member_level ]; //排位佣金 给购物券            
        }
        $this->orderInfo->commission_fee_rank = $commission_fee_rank;
        $commission_fee_rank_amount = $entity_OrderInfo_base->order_amount - $commission_fee;
        //系统佣金更新，如果有的话
        $entity_MemberSetting_base->history_money = new TmacDbExpr( 'history_money+' . $commission_fee_rank_amount );
        //更新卖家的金钱 商品供应商UID
        $member_setting_dao->setPk( $entity_OrderInfo_base->goods_uid );
        $member_setting_dao->updateByPk( $entity_MemberSetting_base );


        //更新order_info表
        $order_info_dao = dao_factory_base::getOrderInfoDao();
        $entity_OrderInfo_base = new \base\entity\OrderInfo();
        $entity_OrderInfo_base->commission_fee = $commission_fee;
        $entity_OrderInfo_base->commission_fee_rank = $commission_fee_rank;
        $entity_OrderInfo_base->rank_uid = $this->agent_rank_uid;
        $order_info_dao->setPk( $this->orderInfo->order_id );
        $order_info_dao->updateByPk( $entity_OrderInfo_base );
        $this->orderInfo->commission_fee = $commission_fee;

        $entity_OrderGoods_base = new \base\entity\OrderGoods();
        $goods_commission_fee_rank = ceil( $commission_fee_rank / $this->goods_count );
        $entity_OrderGoods_base->commission_fee_rank = $goods_commission_fee_rank;

        $where = "order_id={$this->orderInfo->order_id}";
        $order_goods_dao = dao_factory_base::getOrderGoodsDao();
        $order_goods_dao->setWhere( $where );
        $order_goods_dao->updateByWhere( $entity_OrderGoods_base );
        //order_goods表中的
        return true;
    }

    /**
     * member_bill 会员账单表更新
     * @2015-07-08增加 判断如果有分销商。分别向分销商和供应商写入账户金额变动 历史记录
     * @return type
     */
    private function insertMemberBill()
    {
        $entity_OrderInfo_base = $this->orderInfo;
        $member_bill_dao = dao_factory_base::getMemberBillDao();

        $goods_count = $this->goods_count;
        $bill_note = "购买{$goods_count}件商品|LV{$this->goods_member_level}会员商品";
        $order_complete = service_Member_base::order_complete_no;
        $order_finish = service_Member_base::order_finish_no;
        $entity_MemberBill_base = new \base\entity\MemberBill();

        //判断上家是不是会员
        if ( $this->agent_vip_status ) {
            //是会员 给直接佣金
            $commission_fee = $entity_OrderInfo_base->commission_fee; //直推佣金            
            //分销商金额变动日志 写入 开始
            $entity_MemberBill_base = new \base\entity\MemberBill();
            $entity_MemberBill_base->uid = $this->memberInfo->agent_uid;
            $entity_MemberBill_base->order_id = $entity_OrderInfo_base->order_id;
            $entity_MemberBill_base->money = $commission_fee;
            $entity_MemberBill_base->bill_type = service_Member_base::bill_type_income;
            $entity_MemberBill_base->bill_type_class = service_Member_base::bill_type_class_wholesale;
            $entity_MemberBill_base->bill_expend_type = service_Member_base::bill_expend_type_no;
            $entity_MemberBill_base->bill_note = $bill_note . "(会员商品直推佣金)";
            $entity_MemberBill_base->bill_time = $this->now;
            $entity_MemberBill_base->trade_vendor = $this->trade_vendor;
            $entity_MemberBill_base->trade_no = $this->trade_no;
            $entity_MemberBill_base->order_complete = $order_complete;
            $entity_MemberBill_base->order_finish = $order_finish;
            $entity_MemberBill_base->bill_uid = $entity_OrderInfo_base->uid;
            $entity_MemberBill_base->bill_realname = $entity_OrderInfo_base->consignee;
            $entity_MemberBill_base->bill_image_id = '';
            $entity_MemberBill_base->bill_class = service_Member_base::bill_class_money;
            $member_bill_dao->insert( $entity_MemberBill_base );
            //分销商金额变动日志 写入 结束
        } else {
            $commission_fee = 0;
        }
        //判断给不给排位佣金
        if ( $this->agent_rank_uid === false ) {
            $commission_fee_rank = 0;
        } else {
            $commission_fee_rank = $entity_OrderInfo_base->commission_fee_rank; //排位佣金    
            //向上9级的会员写入
            foreach ( $this->rank_uid_array as $rank_uid ) {
                //供应商金额变动日志 写入 开始            
                $entity_MemberBill_base->uid = $rank_uid;
                $entity_MemberBill_base->order_id = $entity_OrderInfo_base->order_id;
                $entity_MemberBill_base->money = $commission_fee_rank;
                $entity_MemberBill_base->bill_type = service_Member_base::bill_type_income;
                $entity_MemberBill_base->bill_type_class = service_Member_base::bill_type_class_coupon;
                $entity_MemberBill_base->bill_expend_type = service_Member_base::bill_expend_type_no;
                $entity_MemberBill_base->bill_note = $bill_note . "(白金会员商品购物券)";
                $entity_MemberBill_base->bill_time = $this->now;
                $entity_MemberBill_base->trade_vendor = $this->trade_vendor;
                $entity_MemberBill_base->trade_no = $this->trade_no;
                $entity_MemberBill_base->order_complete = $order_complete;
                $entity_MemberBill_base->order_finish = $order_finish;
                $entity_MemberBill_base->bill_uid = $entity_OrderInfo_base->uid;
                $entity_MemberBill_base->bill_realname = $entity_OrderInfo_base->consignee;
                $entity_MemberBill_base->bill_image_id = '';
                $entity_MemberBill_base->bill_class = service_Member_base::bill_class_voucher;
                $member_bill_dao->insert( $entity_MemberBill_base );
                //供应商金额变动日志 写入 结束       
            }
        }
        $system_amount = $entity_OrderInfo_base->order_amount - $commission_fee;
        //供应商金额变动日志 写入 开始            
        $entity_MemberBill_base->uid = $entity_OrderInfo_base->goods_uid;
        $entity_MemberBill_base->order_id = $entity_OrderInfo_base->order_id;
        $entity_MemberBill_base->money = $system_amount;
        $entity_MemberBill_base->bill_type = service_Member_base::bill_type_income;
        $entity_MemberBill_base->bill_type_class = service_Member_base::bill_type_class_business; //自营
        $entity_MemberBill_base->bill_expend_type = service_Member_base::bill_expend_type_no;
        $entity_MemberBill_base->bill_note = $bill_note . "(订单总金额￥{$entity_OrderInfo_base->order_amount})";
        $entity_MemberBill_base->bill_time = $this->now;
        $entity_MemberBill_base->trade_vendor = $this->trade_vendor;
        $entity_MemberBill_base->trade_no = $this->trade_no;
        $entity_MemberBill_base->order_complete = $order_complete;
        $entity_MemberBill_base->order_finish = $order_finish;
        $entity_MemberBill_base->bill_uid = $entity_OrderInfo_base->uid;
        $entity_MemberBill_base->bill_realname = $entity_OrderInfo_base->consignee;
        $entity_MemberBill_base->bill_image_id = '';
        $entity_MemberBill_base->bill_class = service_Member_base::bill_class_money;
        $member_bill_dao->insert( $entity_MemberBill_base );
        return true;
    }

    /**
     * 扣款时更新金额
     * $this->batch_no;
     * $this->refund_id;
     * $this->trade_no;
     * $this->trade_vendor;
     * $this->refund();
     */
    public function refund()
    {
        $this->refundMemberSettingMoney();
        $this->refundMemberBill();
        return true;
    }

    /**
     * 退款时扣款 
     * member_setting表的money字段
     * 
     */
    private function refundMemberSettingMoney()
    {
        $member_setting_dao = dao_factory_base::getMemberSettingDao();

        $supplier_amount = $this->total_fee;
        $entity_MemberSetting_base = new \base\entity\MemberSetting();
        if ( !empty( $this->entity_OrderRefund->agent_uid ) ) {//订单有直推佣金
            $supplier_amount -= $this->entity_OrderRefund->commission_fee;
            //直推佣金的钱更新 开始
            $entity_MemberSetting_base->current_money = new TmacDbExpr( 'current_money-' . $this->entity_OrderRefund->commission_fee );
            $entity_MemberSetting_base->history_money = new TmacDbExpr( 'history_money-' . $this->entity_OrderRefund->commission_fee );
            //更新卖家的金钱 商品分销商UID
            $member_setting_dao->setPk( $this->entity_OrderRefund->agent_uid );
            $member_setting_dao->updateByPk( $entity_MemberSetting_base );
            //直推佣金的钱更新 结束
        }
        //供应商的钱更新 开始                                    
        $entity_MemberSetting_base->current_money = new TmacDbExpr( 'current_money-' . $supplier_amount );
        $entity_MemberSetting_base->history_money = new TmacDbExpr( 'history_money-' . $supplier_amount );
        //更新卖家的金钱 商品供应商UID
        $member_setting_dao->setPk( $this->entity_OrderRefund->goods_uid );
        $member_setting_dao->updateByPk( $entity_MemberSetting_base );
        //供应商的钱更新 结束            
        //系统佣金更新，如果有的话
        //$this->updateSystemMemberSettingMoney( $commission_fee_rank );

        return true;
    }

    /**
     * 退款时扣款流水记录
     * member_setting表的money字段
     * 
     */
    private function refundMemberBill()
    {
        $member_bill_dao = dao_factory_base::getMemberBillDao();
        $order_goods_array = unserialize( $this->entity_OrderRefund->order_goods_detail );

        $entity_MemberBill_base = new \base\entity\MemberBill();
        $supplier_amount = $this->total_fee;
        if ( !empty( $this->entity_OrderRefund->agent_uid ) ) {//订单有直推佣金
            $supplier_amount -= $this->entity_OrderRefund->commission_fee;
            //直推佣金变动日志 写入 开始
            $entity_MemberBill_base->uid = $this->entity_OrderRefund->agent_uid;
            $entity_MemberBill_base->order_id = $this->entity_OrderRefund->order_id;
            $entity_MemberBill_base->money = -$this->entity_OrderRefund->commission_fee;
            $entity_MemberBill_base->bill_type = service_Member_base::bill_type_expend;
            $entity_MemberBill_base->bill_type_class = service_Member_base::bill_type_class_coupon; //购物券
            $entity_MemberBill_base->bill_expend_type = service_Member_base::bill_expend_type_coupon_refund; //购物券退款
            $entity_MemberBill_base->bill_note = "申请白金会员专卖商品[直推佣金]退款";
            $entity_MemberBill_base->bill_time = $this->now;
            $entity_MemberBill_base->trade_vendor = $this->trade_vendor;
            $entity_MemberBill_base->trade_no = $this->trade_no;
            $entity_MemberBill_base->batch_no = $this->batch_no;
            $entity_MemberBill_base->refund_id = $this->refund_id;
            $entity_MemberBill_base->order_complete = service_Member_base::order_complete_yes;
            $entity_MemberBill_base->order_finish = service_Member_base::order_finish_yes;
            $entity_MemberBill_base->bill_uid = $this->entity_OrderRefund->uid;
            $entity_MemberBill_base->bill_realname = $this->entity_OrderRefund->consignee;
            $entity_MemberBill_base->bill_image_id = '';
            $entity_MemberBill_base->bill_class = service_Member_base::bill_class_money;
            $member_bill_dao->insert( $entity_MemberBill_base );
            //直推佣金变动日志 写入 结束
        }
        if ( !empty( $this->entity_OrderRefund->rank_uid ) ) {//订单有排位佣金
            $rank_uid_array = explode( ',', $this->entity_OrderRefund->rank_uid );
            foreach ( $rank_uid_array as $rank_uid ) {
                //排位佣金 写入 结束
                $entity_MemberBill_base->uid = $rank_uid;
                $entity_MemberBill_base->order_id = $this->entity_OrderRefund->order_id;
                $entity_MemberBill_base->money = -$this->entity_OrderRefund->commission_fee_rank;
                $entity_MemberBill_base->bill_type = service_Member_base::bill_type_expend;
                $entity_MemberBill_base->bill_type_class = service_Member_base::bill_type_class_coupon; //购物券
                $entity_MemberBill_base->bill_expend_type = service_Member_base::bill_expend_type_coupon_refund; //购物券退款
                $entity_MemberBill_base->bill_note = "申请白金会员专卖商品[购物券]退款";
                $entity_MemberBill_base->bill_time = $this->now;
                $entity_MemberBill_base->trade_vendor = $this->trade_vendor;
                $entity_MemberBill_base->trade_no = $this->trade_no;
                $entity_MemberBill_base->batch_no = $this->batch_no;
                $entity_MemberBill_base->refund_id = $this->refund_id;
                $entity_MemberBill_base->order_complete = service_Member_base::order_complete_yes;
                $entity_MemberBill_base->order_finish = service_Member_base::order_finish_yes;
                $entity_MemberBill_base->bill_uid = $this->entity_OrderRefund->uid;
                $entity_MemberBill_base->bill_realname = $this->entity_OrderRefund->consignee;
                $entity_MemberBill_base->bill_image_id = '';
                $entity_MemberBill_base->bill_class = service_Member_base::bill_class_voucher;
                $member_bill_dao->insert( $entity_MemberBill_base );
                //排位佣金 写入 结束
            }
        }
        $entity_MemberBill_base->uid = $this->entity_OrderRefund->goods_uid;
        $entity_MemberBill_base->order_id = $this->entity_OrderRefund->order_id;
        $entity_MemberBill_base->money = -$supplier_amount;
        $entity_MemberBill_base->bill_type = service_Member_base::bill_type_expend;
        $entity_MemberBill_base->bill_type_class = service_Member_base::bill_type_class_business;
        $entity_MemberBill_base->bill_expend_type = service_Member_base::bill_expend_type_refund;
        $entity_MemberBill_base->bill_note = "申请白金会员专卖商品退款";
        $entity_MemberBill_base->bill_time = $this->now;
        $entity_MemberBill_base->trade_vendor = $this->trade_vendor;
        $entity_MemberBill_base->trade_no = $this->trade_no;
        $entity_MemberBill_base->batch_no = $this->batch_no;
        $entity_MemberBill_base->refund_id = $this->refund_id;
        $entity_MemberBill_base->order_complete = service_Member_base::order_complete_yes;
        $entity_MemberBill_base->order_finish = service_Member_base::order_finish_yes;
        $entity_MemberBill_base->bill_uid = $this->entity_OrderRefund->uid;
        $entity_MemberBill_base->bill_realname = $this->entity_OrderRefund->consignee;
        $entity_MemberBill_base->bill_image_id = '';
        $entity_MemberBill_base->bill_class = service_Member_base::bill_class_money;
        $member_bill_dao->insert( $entity_MemberBill_base );
        //系统佣金
        //$this->insertSystemMemberBill( $this->entity_OrderRefund->commission_fee_rank, $order_goods_array[ 0 ]->goods_image_id );

        return true;
    }

}
