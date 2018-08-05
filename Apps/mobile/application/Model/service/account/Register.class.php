<?php

/**
 * 前台 用户登录注册相关 模块 Model
 * ============================================================================
 * @author zhuqiang by time 2014-07-07 
 */
class service_account_Register_mobile extends service_account_Register_base
{

    private $openid;
    private $eventKey;
    private $oauth_array;
    private $access_token;

    function setOpenid( $openid )
    {
        $this->openid = $openid;
    }

    function setEventKey( $eventKey )
    {
        $this->eventKey = $eventKey;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 更新access_token之类 数据
     * @param type $uid
     */
    public function updateMemberOauthAvatar( $uid )
    {
        $member_oauth_dao = dao_factory_base::getMemberOauthDao();
        $where = "uid={$uid} AND oauth_type=" . service_Oauth_base::oauth_type_wechat;
        $member_oauth_dao->setWhere( $where );
        $member_oauth_info = $member_oauth_dao->getInfoByWhere();
        if ( empty( $member_oauth_info ) ) {
            return true;
        }
        $this->openid = $member_oauth_info->openid;
        $weixin_member_info = $this->getMemberInfoFromWeixin();

        $member_image_id = $this->getMemberImageIdFromURL( $weixin_member_info->headimgurl );
        if ( $member_image_id == false ) {
            $member_image_id = '';
        }
        $nickname = $weixin_member_info->nickname;

        $dao = dao_factory_base::getMemberDao();
        $dao->getDb()->startTrans();

        $entity_Member_base = new \base\entity\Member();
        $entity_Member_base->member_image_id = $member_image_id;
        if ( !empty( $nickname ) ) {
            $entity_Member_base->nickname = $nickname;
        }
        $dao->setPk( $uid );
        $dao->updateByPk( $entity_Member_base );

        //member_oauth表        
        $entity_MemberOauth_base = new \base\entity\MemberOauth();
        $entity_MemberOauth_base->avatar_imgurl = $weixin_member_info->headimgurl;
        $member_oauth_dao->updateByWhere( $entity_MemberOauth_base );

        if ( $dao->getDb()->isSuccess() ) {
            $dao->getDb()->commit();
            return $weixin_member_info->headimgurl;
        } else {
            $dao->getDb()->rollback();
            return false;
        }
    }

    /**
     * 公众号关注时注册
     * $this->openid;
     * $this->eventKey;
     * $this->mpRegister();
     */
    public function mpRegister()
    {
        //判断是否已经注册过
        $register_status = $this->isOpenidRegister();
        if ( $register_status == true ) {
            return true;
        }
        //如果没有就执行注册              
        //取用户信息
        $weixin_member_info = $this->getMemberInfoFromWeixin();

        $agent_uid = 0;
        if ( !empty( $this->eventKey ) && substr( $this->eventKey, 0, 8 ) === 'qrscene_' ) {
            $agent_id = str_replace( 'qrscene_', '', $this->eventKey );
            $agent_info = $this->getUidByUid( $agent_id );
            $agent_uid = $agent_info[ 'agent_uid' ];
        }
        $member_image_id = $this->getMemberImageIdFromURL( $weixin_member_info->headimgurl );
        if ( $member_image_id == false ) {
            $member_image_id = '';
        }
        // 开始存储事务
        // member表
        //开始注册用户        
        $entity_member = new \base\entity\Member ();
        //MD5(pass+salt)
        $entity_member->nickname = empty( $weixin_member_info->nickname ) ? '宝身茶_' . date( 'YmdHis' ) . rand( 10000, 99999 ) : $weixin_member_info->nickname;
        $entity_member->password = md5( rand( 10000000, 990000000 ) );
        $entity_member->mobile = '';
        $entity_member->email = '';
        $entity_member->member_type = service_Member_base::member_type_buyer;
        $entity_member->member_class = 0;
        $entity_member->member_image_id = $member_image_id;
        $entity_member->reg_time = $this->now;
        $entity_member->salt = rand( 100000, 999999 );
        $entity_member->last_login_time = $this->now;
        $entity_member->last_login_ip = Functions::get_client_ip();
        $entity_member->login_fail_count = 0;
        $entity_member->agent_uid = $agent_uid;
        $entity_member->register_source = service_Account_base::register_source_wechat;
        $entity_member->sex = $weixin_member_info->sex;
        $address_info = array(
            'country' => $weixin_member_info->country,
            'province' => $weixin_member_info->province,
            'city' => $weixin_member_info->city
        );
        $entity_member->address_info = serialize( $address_info );
        $entity_member->available_integral = service_Member_base::agent_register_integral_value;
        $entity_member->sum_integral = $entity_member->available_integral;
        $entity_member->agent_integral = service_Member_base::agent_integral_value; //东家应得1分        

        $dao = dao_factory_base::getMemberDao();
        $integral_dao = dao_factory_base::getIntegralDao();
        $dao->getDb()->startTrans();

        $uid = $dao->insert( $entity_member );
        $entity_member->uid = $uid;

        //更新东家的积分
        $this->updateAgentIntegral( $agent_uid );

        //会员设置表插入记录
        $member_setting_dao = dao_factory_base::getMemberSettingDao();
        $entity_MemberSetting_base = new \base\entity\MemberSetting();
        $entity_MemberSetting_base->uid = $uid;
        $entity_MemberSetting_base->shop_name = '宝身茶_' . date( 'YmdHis' ) . rand( 10000, 99999 );
        $entity_MemberSetting_base->member_type = service_Member_base::member_type_buyer;
        $entity_MemberSetting_base->reg_time = $this->now;
        $member_setting_agent_info = $this->insertMemberLevel( $uid, $agent_uid );
        $entity_MemberSetting_base->agent_uid_parent = $member_setting_agent_info[ 'agent_uid_parent' ];
        $entity_MemberSetting_base->agent_uid_recent = $member_setting_agent_info[ 'agent_uid_recent' ];
        $member_setting_dao->insert( $entity_MemberSetting_base );

        //member_oauth表
        $entity_MemberOauth_base = new \base\entity\MemberOauth();
        $entity_MemberOauth_base->uid = $uid;
        $entity_MemberOauth_base->oauth_type = service_Oauth_base::oauth_type_wechat;
        $entity_MemberOauth_base->openid = $weixin_member_info->openid;
        $entity_MemberOauth_base->unionid = isset( $weixin_member_info->unionid ) ? $weixin_member_info->unionid : '';
        $entity_MemberOauth_base->access_token = '';
        $entity_MemberOauth_base->expires_in = 0;
        $entity_MemberOauth_base->refresh_token = '';
        $entity_MemberOauth_base->nickname = $weixin_member_info->nickname;
        $entity_MemberOauth_base->avatar_imgurl = $weixin_member_info->headimgurl;
        $entity_MemberOauth_base->oauth_time = $this->now;
        $member_oauth_dao = dao_factory_base::getMemberOauthDao();
        $member_oauth_dao->insert( $entity_MemberOauth_base );

        //自己的integral表
        $entity_Integral_base = new \base\entity\Integral();
        $entity_Integral_base->integral = $entity_member->available_integral;
        $entity_Integral_base->uid = $uid;
        $entity_Integral_base->integral_type = service_MemberIntegral_base::integral_type_increase;
        $entity_Integral_base->integral_class = service_MemberIntegral_base::integral_class_increase_register;
        $entity_Integral_base->integral_time = $this->now;
        $integral_dao->insert( $entity_Integral_base );

        //trade表记富豪币变动记录
        $this->insertTradeFromRegisterPromotion( $entity_member );

        if ( $dao->getDb()->isSuccess() ) {
            $dao->getDb()->commit();
            $entity_member->uid = $uid;
            parent::updateMemberCookieCheck( $entity_member, 1 );
            return true;
        } else {
            $dao->getDb()->rollback();
            return false;
        }
        //注册的时候判断有没有推荐人,如果有写上
    }

    /**
     * 注册的时候写入member_level表
     * 更新member_setting.agent_uid_parent 字段
     * 
     * @param type $uid     
     * @param type $agent_uid          
     * @return boolean
     */
    public function insertMemberLevel( $uid, $agent_uid )
    {
        if ( empty( $agent_uid ) ) {
            return '';
        }
        //$agent_uid_array, $member_level_type
        $member_model = new service_Member_base();
        $agent_member_setting_info = $member_model->getMemberSettingInfoByUid( $agent_uid );

        $agent_uid_parent = '';
        if ( empty( $agent_member_setting_info->agent_uid_parent ) ) {
            $agent_uid_parent = $agent_uid;
        } else {
            $agent_uid_parent = $agent_uid . ',' . $agent_member_setting_info->agent_uid_parent;
        }
        
        if ( $agent_member_setting_info->member_type == \service_Member_base::member_type_dfh_agent ) {
            //如果直推人是代理
            $agent_uid_recent = $agent_uid;
        } else {
            $agent_uid_recent = $agent_member_setting_info->agent_uid_recent;
        }
        $agent_uid_array = explode( ',', $agent_uid_parent );
        $member_level_type = service_Member_base::member_level_type_recommend;
        $dao = dao_factory_base::getMemberLevelDao();

        $values = '';
        $member_level = 1;
        foreach ( $agent_uid_array AS $agent_uid ) {
            $values .= ",({$uid},{$agent_uid},{$member_level_type},{$member_level},{$agent_uid_recent})";
            $member_level++;
        }
        $value = substr( $values, 1 );
        $sql = "INSERT INTO {$dao->getTable()} (`uid`,`agent_uid`,`member_level_type`,`member_level`,`agent_uid_recent`) VALUES {$value};";
        $dao->getDb()->execute( $sql );

        $return = ['agent_uid_parent' => $agent_uid_parent, 'agent_uid_recent' => $agent_uid_recent ];
        return $return;
    }

    /**
     * member_setting 表中的money相关字段更新
     * @2015-07-08增加 判断如果有分销商。分别向分销商和供应商写入账户金额变动
     * @return type
     */
    private function updateMemberSettingMoney( $uid, $fh_currency )
    {
        $member_setting_dao = dao_factory_base::getMemberSettingDao();

        $entity_MemberSetting_base = new \base\entity\MemberSetting();

        $entity_MemberSetting_base->current_fh_currency = new TmacDbExpr( 'current_fh_currency+' . $fh_currency );
        //$entity_MemberSetting_base->history_fh_currency = new TmacDbExpr( 'history_fh_currency+' . $fh_currency );
        //更新卖家的金钱 商品供应商UID
        $member_setting_dao->setPk( $uid );
        $member_setting_dao->updateByPk( $entity_MemberSetting_base );

        return true;
    }

    /**
     * 检测 推广二维码注册 注册，赠送富豪币的次数
     * @param type $uid
     */
    private function checkFhCurrencyFromRegisterPromotionCount( $uid )
    {
        $trade_dao = dao_factory_base::getTradeDao();
        $where = "uid={$uid} AND trade_type=" . \base\service\trade\Base::trade_type_register_promotion;
        $trade_dao->setWhere( $where );
        $count = $trade_dao->getCountByWhere();
        if ( $count > \base\service\trade\Base::register_handsel_fh_currency_limit ) {
            return false;
        }
        return true;
    }

    /**
     * 写入推广富豪币赠送日志
     */
    public function insertTradeFromRegisterPromotion( $memberInfo )
    {
        if ( empty( $memberInfo->agent_uid ) ) {
            return true;
        }
        $handsel_res = $this->checkFhCurrencyFromRegisterPromotionCount( $memberInfo->agent_uid );
        if ( $handsel_res == false ) {
            //已经超标了不送了
            return true;
        }

        $fh_currency = \base\service\trade\Base::register_handsel_fh_currency;
        $this->updateMemberSettingMoney( $memberInfo->agent_uid, $fh_currency );
        $trade_dao = dao_factory_base::getTradeDao();

        $agentMemberInfo = $this->getMemberInfoById( $memberInfo->agent_uid, 'nickname' );

        $bill_note = "邀请({$memberInfo->nickname})[{$memberInfo->uid}]注册会员赠送" . \base\service\trade\Base::register_handsel_fh_currency . "个富豪币";

        $entity_Trade = new \base\entity\Trade();
        $entity_Trade->uid = $memberInfo->agent_uid;
        $entity_Trade->nickname = $agentMemberInfo->nickname;
        $entity_Trade->trade_status = \base\service\trade\Base::trade_status_settle;
        $entity_Trade->trade_type = \base\service\trade\Base::trade_type_register_promotion;
        $entity_Trade->trade_source = \base\service\trade\Base::trade_source_london_silver;
        $entity_Trade->fh_currency = $fh_currency;
        $entity_Trade->create_time = $this->now;
        $entity_Trade->settle_time = $this->now;
        $entity_Trade->execute_settle_time = $this->now;
        $entity_Trade->trade_note = $bill_note;
        $entity_Trade->voucher_money = 0;
        $entity_Trade->pay_amount = 0;
        return $trade_dao->insert( $entity_Trade );
    }

    /**
     * 更新东家的积分    
     */
    private function updateAgentIntegral( $agent_uid )
    {
        //检测当前积分余额是否大于50分
        $available_integral = $this->checkAvailableIntegral( $agent_uid );
        if ( $available_integral === false ) {
            return FALSE;
        }
        $entity_Member_base = new \base\entity\Member();
        $entity_Member_base->sum_integral = new TmacDbExpr( 'sum_integral+' . service_Member_base::agent_integral_value );
        $entity_Member_base->available_integral = new TmacDbExpr( 'available_integral+' . service_Member_base::agent_integral_value );

        $dao = dao_factory_base::getMemberDao();
        $dao->setPk( $agent_uid );
        $dao->updateByPk( $entity_Member_base );

        $integral_dao = dao_factory_base::getIntegralDao();
        //东家的积分记录表中增加
        $entity_Integral_base = new \base\entity\Integral();
        $entity_Integral_base->integral = service_Member_base::agent_integral_value;
        $entity_Integral_base->uid = $agent_uid;
        $entity_Integral_base->integral_type = service_MemberIntegral_base::integral_type_increase;
        $entity_Integral_base->integral_class = service_MemberIntegral_base::integral_class_increase_agent;
        $entity_Integral_base->integral_time = $this->now;
        return $integral_dao->insert( $entity_Integral_base );
    }

    /**
     * 检测今天有没有超过日限制积分
     * @param type $uid
     */
    private function checkAgentTodayIntegral( $agent_uid )
    {
        $today_start = strtotime( date( 'Y-m-d' ) );
        $dao = dao_factory_base::getMemberDao();
        $dao->setField( 'SUM(agent_integral) AS today_integral' );
        $where = "agent_uid={$agent_uid} AND reg_time>=$today_start";
        $dao->setWhere( $where );
        $memberInfo = $dao->getInfoByWhere();
        if ( $memberInfo->today_integral >= service_Member_base::agent_integral_day_max_value ) {
            return false;
        }
        return true;
    }

    /**
     * 检测当前积分余额是否超过50分
     * @param type $uid
     */
    private function checkAvailableIntegral( $uid )
    {
        $dao = dao_factory_base::getMemberDao();
        $dao->setField( 'available_integral' );
        $dao->setPk( $uid );
        $memberInfo = $dao->getInfoByPk();
        if ( $memberInfo->available_integral >= service_Member_base::available_max_integral ) {
            return false;
        }
        return $memberInfo->available_integral;
    }

    private function getMemberInfoFromWeixin( $update = false )
    {

        $this->oauth_array = Tmac::config( 'oauth.oauth.wechat', APP_WWW_NAME );
        $weixin_token_model = new service_utils_WeixinToken_base();
        $weixin_token_model->setAppid( $this->oauth_array[ 'appid' ] );
        $weixin_token_model->setSecret( $this->oauth_array[ 'appsecret' ] );
        if ( $update ) {
            $weixin_token_model->setExpired_time( 0 );
        }
        try {
            $this->access_token = $weixin_token_model->getAccessToken();
        } catch (TmacClassException $exc) {
            throw new TmacClassException( $exc->getMessage() );
        }

        $userinfo = $this->getUserInfo();
        return $userinfo;
    }

    /**
     * 取第三方用户信息
     * @param type $entity_MemberOauth_base
     * @return boolean
     */
    private function getUserInfo()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $this->access_token . '&openid=' . $this->openid . '&lang=zh_CN';
        $userinfo_json = Functions::curl_file_get_contents( $url, 30, $ssl = true );
        $userinfo_result = json_decode( $userinfo_json );
        if ( !empty( $userinfo_result->errcode ) ) {
            $access_token_error_array = array( '40001', '40014', '41001', '42001' );
            if ( in_array( $userinfo_result->errcode, $access_token_error_array ) ) {
                return $this->getMemberInfoFromWeixin( true );
            }
            throw new TmacClassException( $userinfo_result->errcode . '|' . $userinfo_result->errmsg );
        }
        if ( !$userinfo_result ) {
            throw new TmacClassException( '取用户数据失败' );
        }

        return $userinfo_result;
    }

    private function isOpenidRegister()
    {
        $dao = dao_factory_base::getMemberOauthDao();
        $where = "oauth_type=" . service_Oauth_base::oauth_type_wechat . " AND openid='{$this->openid}'";
        $dao->setWhere( $where );
        $member_oauth_info = $dao->getInfoByWhere();
        if ( !$member_oauth_info ) {
            return false;
        }
        //如果注册过 种下验证cookie
        $member_dao = dao_factory_base::getMemberDao();
        $member_dao->setPk( $member_oauth_info->uid );
        $member_info = $member_dao->getInfoByPk();
        parent::updateMemberCookieCheck( $member_info, 1 );
        return TRUE;
    }

}
