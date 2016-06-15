<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $
 * $Id: factory.class.php 543 2014-12-07 07:29:21Z zhangwentao $
 */

/**
 * Description of DaoFactory
 *
 * @author Tracy McGrady
 */
final class dao_factory_base
{

    /**
     * 工厂类中数据库连接
     * @return type
     */
    public static function getDb()
    {
        $database = $GLOBALS[ 'TmacConfig' ][ 'Common' ][ 'Database' ];
//        $database = 'hotel_9tour_cn';
        return DatabaseDriver::getInstance( $database );
    }

    /**
     * 工厂类中数据库连接
     * @return type
     */
    public static function getLineSearchNoteDb()
    {
//        $database = $GLOBALS['TmacConfig']['Common']['Database'];
        $database = 'line_search_note';
        return DatabaseDriver::getInstance( $database );
    }

    /**
     * dao_factory_base::createDao('article',$this->db);
     * @param type $name
     * @param type $link_identifier
     * @throws TmacException
     */
    public static function createDao( $name )
    {
        $className = $className = 'dao_impl_' . $name . '_' . APP_BASE_NAME; //java版    Class clazz = Class.forName(className);            
        return new $className( self::getDb() );
    }

    public static function getArticleDao()
    {
        return new dao_impl_Article_base( self::getDb() );
    }

    public static function getAddonarticleDao()
    {
        return new dao_impl_Addonarticle_base( self::getDb() );
    }

    public static function getCategoryDao()
    {
        return new dao_impl_Category_base( self::getDb() );
    }

    public static function getUserDao()
    {
        return new dao_impl_User_base( self::getDb() );
    }

    public static function getUserLogDao()
    {
        return new dao_impl_UserLog_base( self::getDb() );
    }

    public static function getUserTypeDao()
    {
        return new dao_impl_UserType_base( self::getDb() );
    }

    public static function getSysconfigDao()
    {
        return new dao_impl_Sysconfig_base( self::getDb() );
    }

    public static function getMemberDao()
    {
        return new dao_impl_Member_base( self::getDb() );
    }

    public static function getMemberSettingDao()
    {
        return new dao_impl_MemberSetting_base( self::getDb() );
    }

    public static function getSmsLogDao()
    {
        return new dao_impl_SmsLog_base( self::getDb() );
    }    

    public static function getRegionDao()
    {
        return new dao_impl_Region_base( self::getDb() );
    }


    public static function getHelpCategoryDao()
    {
        return new dao_impl_HelpCategory_base( self::getDb() );
    }

    public static function getHelpArticleDao()
    {
        return new dao_impl_HelpArticle_base( self::getDb() );
    }

    public static function getPosterDao()
    {
        return new dao_impl_Poster_base( self::getDb() );
    }

    public static function getMessageDao()
    {
        return new dao_impl_Message_base( self::getDb() );
    }


}
