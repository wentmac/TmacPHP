<?php

/**
 * 后台友情链接模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: User.class.php 6 2014-09-20 15:13:57Z zhangwentao $
 * http://www.t-mac.org；
 */
class service_User_admin extends service_Model_base
{

    private $query;
    private $pagesize;

    function setQuery( $query )
    {
        $this->query = $query;
    }

    function setPagesize( $pagesize )
    {
        $this->pagesize = $pagesize;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function createUser( entity_User_base $entity_User_base )
    {
        $dao = dao_factory_base::getUserDao();
        return $dao->insert( $entity_User_base );
    }

    public function modifyUser( entity_User_base $entity_User_base )
    {
        $dao = dao_factory_base::getUserDao();
        $dao->setPk( $entity_User_base->uid );
        return $dao->updateByPk( $entity_User_base );
    }

    /**
     * 获取一个管理员信息
     * @param int $class_id 栏目id
     * return array
     */
    public function getUserInfo( $id )
    {
        $dao = dao_factory_base::getUserDao();
        $dao->setPk( $id );
        return $rs = $dao->getInfoByPk();
    }

    public function checkUserName( $username, $id )
    {
        $dao = dao_factory_base::getUserDao();
        $where = "username='{$username}' ";
        if ( !empty( $id ) ) {
            $where .= "AND uid<>{$id}";
        }
        $dao->setWhere( $where );
        return $rs = $dao->getInfoByWhere();
    }

    /**
     * 获取所有管理员
     * return article_class,pages
     */
    public function getUserList()
    {
        $where = 'is_delete=0';
        if ( !empty( $this->query ) ) {
            $where .= " AND username LIKE '%{$this->query}%'";
        }
        $dao = dao_factory_base::getUserDao();
        $dao->setWhere( $where );
        $count = $dao->getCountByWhere();

        if ( $count === false ) {
            $retmsg = 0;
        } else {
            $retmsg = 1; //业务返回信息
        }
        $pages = $this->P( 'Pages' );
        $pages->setTotal( $count );
        $pages->setPrepage( $this->pagesize );
        $limit = $pages->getSqlLimit();

        $user_array = array();
        if ( $count > 0 ) {
            $dao->setOrderby( 'uid ASC' );
            $dao->setLimit( $limit );
            $dao->setField( 'uid,username,nicename,reg_time,last_login_time,last_login_ip,rank' );
            $res = $dao->getListByWhere();

            //取管理员类型option数组        
            $admintype_ary = $this->getAdminType();
            $admintype_array = array();
            foreach ( $admintype_ary AS $vv ) {
                $admintype_array[ $vv->rank ] = $vv->type_name;
            }

            foreach ( $res as $value ) {
                $value->typename = $admintype_array[ $value->rank ];
                $value->reg_time = date( 'Y-m-d H:i:s', $value->reg_time );
                $value->last_login_time = date( 'Y-m-d H:i:s', $value->last_login_time );
            }
            $user_array = $res;
        }

        $retHeader = array(
            'totalput' => $count,
            'totalpg' => intval( ceil( $count / $this->pagesize ) ),
            'pagesize' => $this->pagesize,
            'page' => $pages->getNowPage()
        );
        $return = array(
            'retHeader' => $retHeader,
            'retcode' => 'user_list',
            'retmsg' => $retmsg,
            'reqdata' => $user_array,
        );
        return $return;
    }

    /**
     * del
     * @param int $class_id
     */
    public function deleteByUid( $id )
    {
        $dao = dao_factory_base::getUserDao();

        $dao->getDb()->startTrans();
        $entity_User_base = new entity_User_base();
        $entity_User_base->is_delete = 1;
        if ( strpos( $id, ',' ) === false ) {
            $dao->setPk( $id );
            $dao->updateByPk( $entity_User_base );
        } else {
            $dao->setWhere( "uid IN({$id})" );
            $dao->updateByWhere( $entity_User_base );
        }

        if ( $dao->getDb()->isSuccess() ) {
            $dao->getDb()->commit();
            return true;
        } else {
            $dao->getDb()->rollback();
            return false;
        }
    }

    public function getAdminType()
    {
        $dao_user_type = dao_factory_base::getUserTypeDao();
        $dao_user_type->setOrderby( 'rank ASC' );
        return $dao_user_type->getListByWhere();
    }

}
