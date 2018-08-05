<?php

/**
 * 用户登录注册页面
 * ============================================================================
 * @author  by time 22014-07-07
 * 
 */
use crontab\service\Controller;
use crontab\service\trade\FE as Trade;

class tradeAction extends Controller
{

    public function __construct()
    {
        set_time_limit(0);
        parent::__construct();
    }

    /**
     * 富豪币交易结算
     * 一分钟一次
     * /usr/local/php-5.4.20/bin/php -f /var/www/yph/release_1.0/crontab/wwwroot/cli.php "m=trade.settle"     
     */
    public function settle()
    {
        $model = new Trade();
        $res = $model->handelTrade();
        print_r( $res );
    }

    /**
     * 伦敦银 行情 数据
     * 一分钟一次把最新的写入数据库
     * /usr/local/php-5.4.20/bin/php -f /var/www/yph/release_1.0/crontab/wwwroot/cli.php "m=trade.insert_trend"     
     */
    public function insert_trend()
    {
        $xag_model = new \crontab\service\Trend();
        $xag_model->batchInsertTrend();
    }

}
