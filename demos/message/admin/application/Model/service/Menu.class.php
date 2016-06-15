<?php

/**
 * Menu
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Menu.class.php 6 2014-09-20 15:13:57Z zhangwentao $
 * http://www.t-mac.org；
 */
class service_Menu_admin extends Model
{

    private $check_model;
    private $user_info;

    function setUser_info( $user_info )
    {
        $this->user_info = $user_info;
    }

    public function __construct()
    {
        parent::__construct();
        //连接数据库
        $this->check_model = new service_Check_admin();
    }

    /**
     * 取出菜单数组
     * @param string $menusMain
     * return array $menua
     */
    public function getMenua( $menusMain )
    {
        $this->check_model->setUserInfo( $this->user_info );
        $menuaAry = $this->MenusMakeAry( $menusMain );
        $menua = array();
        foreach ( $menuaAry AS $value ) {
            $menua[] = $value;
        }
        return $menua;
    }

    /**
     * 取出Meun里的TOP
     * @param string $menubody
     * return array
     */
    public function getMeunTop( $menubody )
    {
        preg_match_all( "/mapitem='(.*)' name='(.*)' rank='(.*)' link='(.*)' class='(.*)'/isU", $menubody, $result );
        return $result;
    }

    /**
     * 取出Meun里的Item
     * @param string innerbody
     * return array
     */
    public function getMeunItem( $innerbody )
    {
        preg_match_all( "/ name='(.*)' link='(.*)' rank='(.*)' target='(.*)' class='(.*)'/isU", $innerbody, $reitem );
        return $reitem;
    }

    /**
     * ######## 生成菜单数组函数#######   MenusMakeAry($MenusMain字符串)
     * @param array $MenusMain
     * return array
     */
    public function MenusMakeAry( $MenusMain )
    {
        $MenusAry = preg_match_all( "/<m:top(.*)>(.*)<\/m:top>/isU", $MenusMain, $result );
        $MenusArray = array();
        foreach ( $result[ 1 ] AS $k => $v ) {
            $mapitemAry = $this->getMeunTop( $v );            
            $mapitem = $mapitemAry[ 1 ][ 0 ];      //顺序 ID
            $name = $mapitemAry[ 2 ][ 0 ];       //news
            $rank = $mapitemAry[ 3 ][ 0 ];       //rank
            $inner = $result[ 2 ][ $k ];      //item
            $link = $mapitemAry[ 4 ][ 0 ];       //class
            $class = $mapitemAry[ 5 ][ 0 ];       //class
            if ( $this->check_model->CheckPurviewMenu( $rank ) != false )
                continue;
            $MenusItem = preg_match_all( "/<m:item(.*)\/>/isU", $inner, $rsinner );
            $MenusArray[ $k ][ 'id' ] = $mapitem;
            $MenusArray[ $k ][ 'title' ] = $name;
            $MenusArray[ $k ][ 'link' ] = $link;
            $MenusArray[ $k ][ 'class' ] = $class;
            $MenusArray[ $k ][ 'subname' ] = array();        //初始化数组
            foreach ( $rsinner[ 1 ] AS $vv ) {
                $linkbody = $this->getMeunItem( $vv );                                
                $nametype = $linkbody[ 1 ][ 0 ];
                $linktype = $linkbody[ 2 ][ 0 ];
                $ranktype = $linkbody[ 3 ][ 0 ];
                $targettype = $linkbody[ 4 ][ 0 ];
                $class = $linkbody[ 5 ][ 0 ];
                if ( $this->check_model->CheckPurviewMenu( $ranktype ) != false )
                    continue;
//                $MenusArray[$k]['subname'][] = "name='".$nametype."' link='".$linktype."' rank='".$ranktype."' target='".$targettype."'";
                //加上rank 权限判断后 !important;
                $menu_item_array = array(
                    'linktype' => $linktype,
                    'target' => $targettype,
                    'nametype' => $nametype,
                    'class' => $class
                );
                $MenusArray[ $k ][ 'subname' ][] = $menu_item_array;
            }
        }
        return $MenusArray;
    }

    public function gdversion()
    {
        dao_factory_base::getUserDao();
        //没启用php.ini函数的情况下如果有GD默认视作2.0以上版本
        if ( !function_exists( 'phpinfo' ) ) {
            if ( function_exists( 'imagecreate' ) )
                return '2.0';
            else
                return 0;
        }
        else {
            ob_start();
            phpinfo( 8 );
            $module_info = ob_get_contents();
            ob_end_clean();
            if ( preg_match( "/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info, $matches ) ) {
                $gdversion_h = $matches[ 1 ];
            } else {
                $gdversion_h = 0;
            }
            return $gdversion_h;
        }
    }

}
