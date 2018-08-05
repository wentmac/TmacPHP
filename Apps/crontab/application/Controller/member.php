<?php

/**
 * 前台 首页 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: member.php 756 2016-12-15 10:27:24Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class memberAction extends \crontab\service\Controller
{

    public function __construct()
    {
        parent::__construct();
        set_time_limit( 0 );
        $this->checkProccess();
    }

    public function index()
    {
        //todo 运行完了要删除pid吗
        $this->checkProccess( 2 );
        for ( $i = 0; $i < 100; $i++ ) {
            echo $i . "\r\n";
            ob_flush();
            sleep( 1 );
        }
    }

    /**
     * 
     * /usr/local/php-5.4.20/bin/php -f /var/www/shop.weixinshow.com/release_1.0/crontab/wwwroot/cli.php "m=member.update_seller_count"
     * 更新供应商店铺的多少个在卖     
     */
    public function update_seller_count()
    {
        $model = new service_shop_Variable_crontab();
        $res = $model->updateShopSellerCount();
        print_r( $res );
    }

    /**
     * 
     * /usr/local/php-5.4.20/bin/php -f /var/www/shop.weixinshow.com/release_1.0/crontab/wwwroot/cli.php "m=member.update_collect_count"
     * 更新供应商店铺的多少个在卖     
     */
    public function update_collect_count()
    {
        $model = new service_shop_Variable_crontab();
        $res = $model->updateShopCollectCount();
        print_r( $res );
    }

    /**
     * 
     * /usr/local/php-5.4.20/bin/php -f /var/www/yph/release_1.0/crontab/wwwroot/cli.php "m=member.get_member_temp"
     * 抓取老会员     
     */
    public function get_member_temp()
    {
        $model = new service_MemberTemp_crontab();
        $res = $model->getMemberTempArray();
        print_r( $res );
    }

    /**
     * 
     * /usr/local/php-5.4.20/bin/php -f /var/www/yph/release_1.0/crontab/wwwroot/cli.php "m=member.update_member_temp"
     * 抓取老会员     
     */
    public function update_member_temp()
    {
        ini_set( 'memory_limit', '2048M' );


        $model = new service_MemberImport_crontab();
        //从member_temp表中导入到member表
        //$model->importMember();
        //excel中导入原来的级别和第一次购买会员的时间
        //$res = $model->updateMemberTemp();
        //更新member表中的AgentUid
        //$model->updateMemberAgentUid();
        //die;
        /**
         * 开始设置排位
         * 根据第一次购买lv1的时间来设置Rank的排位
         */
        $model->setMemberAgentRank();
        print_r( $res );
    }

    /**
     * 
     * /usr/local/php-5.4.20/bin/php -f /var/www/yph/release_1.0/crontab/wwwroot/cli.php "m=member.update_member_integral"
     * 抓取老会员     
     */
    public function update_member_integral()
    {
        ini_set( 'memory_limit', '2048M' );
        die;
        $model = new service_MemberIntegral_base();
        $model->updateMemberHistoryIntegral();
    }

    /**
     * 
     * /usr/local/php-5.4.20/bin/php -f /var/www/yph/release_1.0/crontab/wwwroot/cli.php "m=member.give_member_integral"
     * 抓取老会员     
     */
    public function give_member_integral()
    {
        $model = new service_MemberIntegral_base();
        $model->giveMemberIntegral();
    }

    /**
     * 
     * /usr/local/php-5.4.20/bin/php -f /var/www/yph/release_1.0/crontab/wwwroot/cli.php "m=member.update_member_parents_uid" >/tmp/1.log&
     * 更新用户的直推上级     
     */
    public function update_member_parents_uid()
    {
        ini_set( 'memory_limit', '2048M' );

        $model = new \crontab\service\MemberLevel();
        $model->updateMmeberAgentUidParents();
    }

    /**
     * 
     * /usr/local/php-5.4.20/bin/php -f /var/www/yph/release_1.0/crontab/wwwroot/cli.php "m=member.update_dfh_agent_parent_uid"
     * 更新代理的上级     
     */
    public function update_dfh_agent_parent_uid()
    {
        ini_set( 'memory_limit', '2048M' );

        $model = new \crontab\service\MemberLevel();
        $model->updateMmeberDFHAgentParentsUid();
    }

    /**
     * 
     * /usr/local/php-5.4.20/bin/php -f /var/www/yph/release_1.0/crontab/wwwroot/cli.php "m=member.reset_member_type_agent"
     * 重写代理的设置
     */
    public function reset_member_type_agent()
    {
        ini_set( 'memory_limit', '2048M' );

        $model = new \crontab\service\MemberLevel();
        $model->resetAgentMember();
    }

    /**
     * 
     * /usr/local/php-5.4.20/bin/php -f /var/www/yph/release_1.0/crontab/wwwroot/cli.php "m=member.reset_member_level_agent"
     * 重写代理的member_level表中注册的level字段
     */
    public function reset_member_level_agent()
    {
        ini_set( 'memory_limit', '2048M' );

        $model = new \crontab\service\MemberLevel();
        $model->resetAgentMemberLevel();
    }

    /**
     * 
     * /usr/local/php-5.4.20/bin/php -f /var/www/yph/release_1.0/crontab/wwwroot/cli.php "m=member.reset_member_level_recent"
     * 重写代理的member_level表中注册的level字段
     */
    public function reset_member_level_recent()
    {
        ini_set( 'memory_limit', '2048M' );

        $model = new \crontab\service\MemberLevel();
        $model->resetDFHAgentChildMemberAgentRecentUid();
    }

    /**
     * 
     * /usr/local/php-5.4.20/bin/php -f /var/www/yph/release_1.0/crontab/wwwroot/cli.php "m=member.get_member_agent_error"
     * 重写代理的member_level表中注册的level字段
     */
    public function get_member_agent_error()
    {
        ini_set( 'memory_limit', '2048M' );

        $model = new \crontab\service\MemberLevel();
        $model->checkErrorAgentUid();
    }

    /**
     * 
     * /usr/local/php-5.4.20/bin/php -f /var/www/yph/release_1.0/crontab/wwwroot/cli.php "m=member.consume_member_agent_uid_recent"
     * 重写代理的member_level表中注册的level字段
     */
    public function consume_member_agent_uid_recent()
    {
        ini_set( 'memory_limit', '2048M' );

        $model = new \crontab\service\MemberLevel();
        $model->consumeResetAgentUidRecent();
    }

}
