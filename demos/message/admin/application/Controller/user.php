<?php

/**
 * 后台 管理员 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: index.php 562 2014-12-09 15:38:31Z zhangwentao $
 * http://www.t-mac.org；
 */
class userAction extends service_Controller_admin
{

    public function __construct()
    {
        parent::__construct();
        $this->checkLogin();
        $this->CheckPurview( 'tb_admin,tb_editer' );
    }

    /**
     * 取订单列表
     */
    public function index()
    {
        $status = Input::get( 'status', '' )->string();
        $query_string = Input::get( 'query', '' )->string();
        $pagesize = Input::get( 'pagesize', 10 )->int();

        $searchParameter[ 'status' ] = $status;
        $searchParameter[ 'pagesize' ] = $pagesize;
        $searchParameter[ 'query' ] = $query_string;

        $array[ 'searchParameter' ] = json_encode( $searchParameter );
        $this->assign( $array );
        $this->V( 'user/list' );
    }

    /**
     * 取订单列表
     */
    public function get_list()
    {
        //（待付款：waiting_payment｜待发货：wating_seller_delivery｜已发货：wating_receiving｜已完成：complete｜已关闭：close｜退款中：refund）        
        $query_string = Input::get( 'query', '' )->string();
        $pagesize = Input::get( 'pagesize', 10 )->int();
        $image_size = Input::get( 'image_size', 110 )->imageSize();


        $user_model = new service_User_admin();
        $user_model->setPagesize( $pagesize );
        $user_model->setQuery( $query_string );

        $rs = $user_model->getUserList();
        $this->apiReturn( $rs );
    }

    /**
     * 订单详情
     */
    public function detail()
    {
        $id = Input::get( 'id', 0 )->int();        
        $model = new service_User_admin();
        $entity_User_base = new entity_User_base();
        if ( $id > 0 ) {
            $entity_User_base = $model->getUserInfo( $id );
        }

        //取管理员类型option数组
        $admintype_ary = $model->getAdminType();
        $admin_type_option = Utility::OptionObject( $admintype_ary, $entity_User_base->rank, 'rank,type_name' );

        $this->assign( 'uid', $id );
        $this->assign( 'editinfo', $entity_User_base );
        $this->assign( 'admin_type_option', $admin_type_option );
        $this->V( 'user/detail' );
    }

    /**
     * 新增/修改管理员页面　保存　
     */
    public function save()
    {
        if ( empty( $_POST ) || count( $_POST ) < 3 ) {
            throw new ApiException( 'don\'t be evil' );
        }

        /* 初始化变量 */
        $uid = Input::post( 'uid', 0 )->int();
        $username = Input::post( 'username', '' )->required( '管理员登录不能为空' )->string();
        $nicename = Input::post( 'nicename', '' )->required( '管理员真实姓名不能为空' )->string();
        $password = Input::post( 'password', '' )->password();
        $email = Input::post( 'email', '' )->email();
        $rank = Input::post( 'rank', 0 )->int();

        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() );
        }

        $model = new service_User_admin();
        // TODO goon to verify
        //验证id和admin不能重复
        $userinfo = $model->checkUserName( $username, $uid );
        if ( $userinfo ) {
            throw new ApiException( '管理员名称重复了' );
        }

        $entity_User_base = new entity_User_base();
        $entity_User_base->username = $username;
        $entity_User_base->nicename = $nicename;
        $entity_User_base->email = $email;
        $entity_User_base->rank = $rank;
        $entity_User_base->reg_ip = Functions::get_client_ip();
        $entity_User_base->reg_time = $this->now;

        if ( $uid > 0 ) {
            $entity_User_base->uid = $uid;
            if ( !empty( $password ) ) {
                $entity_User_base->salt = rand( 100000, 999999 );
                $entity_User_base->password = md5( $password . $entity_User_base->salt ); //hash( hash( password ) + salt )    
            }
            //update save article            
            $rs = $model->modifyUser( $entity_User_base );
            if ( $rs ) {
                $this->apiReturn();
            } else {
                throw new ApiException( '修改管理员失败' );
            }
        } else {
            $entity_User_base->salt = rand( 100000, 999999 );
            $entity_User_base->password = md5( $password . $entity_User_base->salt ); //hash( hash( password ) + salt )
            //insert save article_class
            $rs = $model->createUser( $entity_User_base );
            if ( $rs ) {
                $this->apiReturn();
            } else {
                throw new ApiException( '添加管理员失败' );
            }
        }
    }

    /**
     * 批量操作
     */
    public function batch()
    {
        $act = Input::get( 'action', '' )->string();
        $aid = Input::get( 'id', 0 )->int();

        $do = Input::post( 'do', '' )->string();
        $id_a = Input::post( 'id_a', '' )->intString();

        if ( strpos( $id_a, ',' ) !== false ) {
            $id = $id_a;
        } elseif ( !empty( $aid ) ) {
            $id = $aid;
        } else {
            throw new ApiException( '请选择要操作的...' );
        }

        if ( $do == 'del' || $act == 'del' ) {
            $model = new service_User_admin();
            $rs = $model->deleteByUid( $id );
            // TODO DEL该分类下的所有资讯
            if ( $rs ) {
                $this->apiReturn();
            } else {
                throw new ApiException( '删除管理员失败，请重试！' );
            }
        }
    }

}
