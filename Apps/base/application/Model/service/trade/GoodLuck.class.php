<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Trade.class.php 887 2017-02-10 16:04:29Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace base\service\trade;

use base\core\traits\ErrorMessage;
use \dao_factory_base;

class GoodLuck extends Base
{

    /**
     * 【trade_type=0时 trade_source=2[好运连连]1：内圈 2：中圈 3：外圈】
     * 内圈
     */
    const trade_class_good_luck_inside = 1;

    /**
     * 【trade_type=0时 trade_source=2[好运连连]1：内圈 2：中圈 3：外圈】
     * 中圈
     */
    const trade_class_good_luck_middle = 2;

    /**
     * 【trade_type=0时 trade_source=2[好运连连]1：内圈 2：中圈 3：外圈】
     * 外圈
     */
    const trade_class_good_luck_outside = 3;


    /**
     * 【trade_type=0时 trade_source=2[好运连连]1：内圈 2：中圈 3：外圈】
     * 内圈 时的返率
     * 1.85
     */
    const trade_class_good_luck_rate_inside = 1.85;

    /**
     * 【trade_type=0时 trade_source=2[好运连连]1：内圈 2：中圈 3：外圈】
     * 中圈 时的返率
     * 4.2
     */
    const trade_class_good_luck_rate_middle = 4.2;

    /**
     * 【trade_type=0时 trade_source=2[好运连连]1：内圈 2：中圈 3：外圈】
     * 内圈 时的返率
     * 8.5
     */
    const trade_class_good_luck_rate_outside = 8.5;


    /**
     * 游戏盘最内圈盘
     * 单
     */
    const trade_trend_payment_inside_single = 1;

    /**
     * 游戏盘最内圈盘
     * 双
     */
    const trade_trend_payment_inside_double = 2;

    /**
     * 游戏盘最中圈盘
     * 0,1
     */
    const trade_trend_payment_middle_one = 1;
    /**
     * 游戏盘最中圈盘
     * 2,3
     */
    const trade_trend_payment_middle_two = 2;
    /**
     * 游戏盘最中圈盘
     * 4,5
     */
    const trade_trend_payment_middle_three = 3;
    /**
     * 游戏盘最中圈盘
     * 6,7
     */
    const trade_trend_payment_middle_four = 4;
    /**
     * 游戏盘最中圈盘
     * 8,9
     */
    const trade_trend_payment_middle_five = 5;

    /**
     * 游戏盘最外圈盘
     * 0
     */
    const trade_trend_payment_outside_zero = 0;

    /**
     * 游戏盘最外圈盘
     * 1
     */
    const trade_trend_payment_outside_one = 1;

    /**
     * 游戏盘最外圈盘
     * 2
     */
    const trade_trend_payment_outside_two = 2;

    /**
     * 游戏盘最外圈盘
     * 3
     */
    const trade_trend_payment_outside_three = 3;


    /**
     * 游戏盘最外圈盘
     * 4
     */
    const trade_trend_payment_outside_four = 4;


    /**
     * 游戏盘最外圈盘
     * 5
     */
    const trade_trend_payment_outside_five = 5;


    /**
     * 游戏盘最外圈盘
     * 6
     */
    const trade_trend_payment_outside_six = 6;


    /**
     * 游戏盘最外圈盘
     * 7
     */
    const trade_trend_payment_outside_seven = 7;


    /**
     * 游戏盘最外圈盘
     * 8
     */
    const trade_trend_payment_outside_eight = 8;


    /**
     * 游戏盘最外圈盘
     * 9
     */
    const trade_trend_payment_outside_nine = 9;

    use \base\core\traits\ErrorMessage;

    protected $memberSettingInfo;

    /**
     * @param mixed $memberSettingInfo
     */
    public function setMemberSettingInfo( $memberSettingInfo )
    {
        $this->memberSettingInfo = $memberSettingInfo;
    }


    static public $trade_trend_payment_map = array(
        self::trade_class_good_luck_inside => [
            self::trade_trend_payment_inside_single => '单',
            self::trade_trend_payment_inside_double => '双'
        ],
        self::trade_class_good_luck_middle => [
            self::trade_trend_payment_middle_one => '尾数0,1',
            self::trade_trend_payment_middle_two => '尾数2,3',
            self::trade_trend_payment_middle_three => '尾数4,5',
            self::trade_trend_payment_middle_four => '尾数6,7',
            self::trade_trend_payment_middle_five => '尾数8,9'
        ],
        self::trade_class_good_luck_outside => [
            self::trade_trend_payment_outside_zero => '零',
            self::trade_trend_payment_outside_one => '壹',
            self::trade_trend_payment_outside_two => '贰',
            self::trade_trend_payment_outside_three => '参',
            self::trade_trend_payment_outside_four => '肆',
            self::trade_trend_payment_outside_five => '伍',
            self::trade_trend_payment_outside_six => '陆',
            self::trade_trend_payment_outside_seven => '柒',
            self::trade_trend_payment_outside_eight => '捌',
            self::trade_trend_payment_outside_nine => '玖'
        ]
    );

    static public $good_luck_match_rate = array(
        self::trade_class_good_luck_inside => [
            self::trade_trend_payment_inside_single => self::trade_class_good_luck_rate_inside,
            self::trade_trend_payment_inside_double => self::trade_class_good_luck_rate_inside
        ],
        self::trade_class_good_luck_middle => [
            self::trade_trend_payment_middle_one => self::trade_class_good_luck_rate_middle,
            self::trade_trend_payment_middle_two => self::trade_class_good_luck_rate_middle,
            self::trade_trend_payment_middle_three => self::trade_class_good_luck_rate_middle,
            self::trade_trend_payment_middle_four => self::trade_class_good_luck_rate_middle,
            self::trade_trend_payment_middle_five => self::trade_class_good_luck_rate_middle
        ],
        self::trade_class_good_luck_outside => [
            self::trade_trend_payment_outside_zero => self::trade_class_good_luck_rate_outside,
            self::trade_trend_payment_outside_one => self::trade_class_good_luck_rate_outside,
            self::trade_trend_payment_outside_two => self::trade_class_good_luck_rate_outside,
            self::trade_trend_payment_outside_three => self::trade_class_good_luck_rate_outside,
            self::trade_trend_payment_outside_four => self::trade_class_good_luck_rate_outside,
            self::trade_trend_payment_outside_five => self::trade_class_good_luck_rate_outside,
            self::trade_trend_payment_outside_six => self::trade_class_good_luck_rate_outside,
            self::trade_trend_payment_outside_seven => self::trade_class_good_luck_rate_outside,
            self::trade_trend_payment_outside_eight => self::trade_class_good_luck_rate_outside,
            self::trade_trend_payment_outside_nine => self::trade_class_good_luck_rate_outside
        ]
    );

    public function __construct()
    {
        parent::__construct();
    }


}
