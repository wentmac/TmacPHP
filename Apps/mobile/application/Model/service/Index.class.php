<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Index.class.php 1027 2017-04-13 13:42:10Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace mobile\service;


class Index extends \service_Shop_base
{

    public function __construct()
    {
        parent::__construct();
    }

    public function testTransNoOneDb()
    {
        $member_temp_dao = \dao_factory_base::getMemberTempDao();
        $notice_dao = \dao_factory_base::getYoukuNoticeDao();

        $member_temp_dao->getDb()->startTrans();
        $notice_dao->getDb()->startTrans();

        //$member_temp_dao->getDb()->execute("INSERT INTO yph_member_temp (`city`,`province`,`content`,`email`,`mid`) VALUES('武汉','湖北','测试db transtion','天朝',7703)");
        //$notice_dao->getDb()->execute("INSERT INTO t_notice (`notice_title`,`notice_content`,`notice_image_id`,`notice_time`) VALUES('notice_title测试2','notice_content测试2','1sdsa2dffasdfdb transtion','1436539292')");//$member_temp_dao->getDb()->execute("INSERT INTO yph_member_temp (`city`,`province`,`content`,`email`,`mid`) VALUES('武汉','湖北','测试db transtion','天朝',7703)");
        $member_temp_dao->getDb()->execute( "UPDATE yph_member_temp SET citys='武汉1' WHERE uid=7706" );
        $notice_dao->getDb()->execute( "UPDATE t_notice SET notice_title='notice_title测试7' WHERE notice_id=10" );

        echo $member_temp_dao->getDb()->getNumRows() . "<br>";
        echo $notice_dao->getDb()->getNumRows() . "<br>";
        //受影响行数。如果需要update强判断的话。可以在下面事务执行条件判断时加上
        //if ( $dao->getDb()->isSuccess() && $dao->getDb()->getNumRows() > 0 ) {

        var_dump( $member_temp_dao->getDb()->isSuccess() );
        var_dump( $notice_dao->getDb()->isSuccess() );
        if ( $member_temp_dao->getDb()->isSuccess() && $notice_dao->getDb()->isSuccess() ) {
            $member_temp_dao->getDb()->commit();
            $notice_dao->getDb()->commit();
            return true;
        } else {
            $member_temp_dao->getDb()->rollback();
            $notice_dao->getDb()->rollback();
            return false;
        }
    }


    public function testTransOneDb()
    {
        $member_temp_dao = \dao_factory_base::getMemberTempDao();
        $brand_dao = \dao_factory_base::getBrandDao();

        $member_temp_dao->getDb()->startTrans();

        //$member_temp_dao->getDb()->execute("INSERT INTO yph_member_temp (`city`,`province`,`content`,`email`,`mid`) VALUES('武汉','湖北','测试db transtion','天朝',7703)");
        //$notice_dao->getDb()->execute("INSERT INTO t_notice (`notice_title`,`notice_content`,`notice_image_id`,`notice_time`) VALUES('notice_title测试2','notice_content测试2','1sdsa2dffasdfdb transtion','1436539292')");//$member_temp_dao->getDb()->execute("INSERT INTO yph_member_temp (`city`,`province`,`content`,`email`,`mid`) VALUES('武汉','湖北','测试db transtion','天朝',7703)");
        $brand_dao->getDb()->execute("INSERT INTO yph_brand (`brand_name`,`site_url`) VALUES('notice_title测试2','notice_content测试2')");//$member_temp_dao->getDb()->execute("INSERT INTO yph_member_temp (`city`,`province`,`content`,`email`,`mid`) VALUES('武汉','湖北','测试db transtion','天朝',7703)");
        $member_temp_dao->getDb()->execute( "UPDATE yph_member_temp SET city='武汉3' WHERE uid=7478" );
        echo $member_temp_dao->getDb()->getNumRows() . "<br>";
        $member_temp_dao->getDb()->execute( "UPDATE yph_member_temp SET city='齐齐哈尔3' WHERE uid=7479" );
        echo $brand_dao->getDb()->getNumRows() . "<br>";
        //$brand_dao->getDb()->execute( "UPDATE t_notice SET notice_title='notice_title测试7' WHERE notice_id=10" );

        //echo $member_temp_dao->getDb()->getNumRows() . "<br>";
        //echo $brand_dao->getDb()->getNumRows() . "<br>";
        //受影响行数。如果需要update强判断的话。可以在下面事务执行条件判断时加上
        //if ( $dao->getDb()->isSuccess() && $dao->getDb()->getNumRows() > 0 ) {

        if ( $member_temp_dao->getDb()->isSuccess() ) {
            $member_temp_dao->getDb()->commit();
            return true;
        } else {
            $member_temp_dao->getDb()->rollback();
            return false;
        }
    }
}
