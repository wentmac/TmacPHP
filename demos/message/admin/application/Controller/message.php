<?php

/**
 * 前台 首页 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: index.php 562 2014-12-09 15:38:31Z zhangwentao $
 * http://www.t-mac.org；
 */
class messageAction extends service_Controller_admin
{

    public function __construct()
    {
        parent::__construct();
        $this->checkLogin();
    }

    /**
     * 取订单列表
     */
    public function index()
    {
        $status = Input::get( 'status', '' )->string();
        $query_string = Input::get( 'query', '' )->string();
        $pagesize = Input::get( 'pagesize', 50 )->int();

        $message_type_array = Tmac::config( 'message.message.message_type', APP_BASE_NAME );
        $message_class_array = Tmac::config( 'message.message.message_class', APP_BASE_NAME );

        $today = date( 'Y-m-d' );
        $tomorrow = date( 'Y-m-d', $this->now + 86400 );

        $searchParameter[ 'status' ] = $status;
        $searchParameter[ 'pagesize' ] = $pagesize;
        $searchParameter[ 'query' ] = $query_string;

        $array[ 'searchParameter' ] = json_encode( $searchParameter );
        $array[ 'message_type_option' ] = Utility::Option( $message_type_array );
        $array[ 'message_class_array' ] = json_encode( $message_class_array );
        $array[ 'start_date' ] = $today;
        $array[ 'end_date' ] = $tomorrow;

        $this->assign( $array );
        $this->V( 'message/list' );
    }

    /**
     * 取订单列表
     */
    public function get_list()
    {
        $pagesize = Input::get( 'pagesize', 50 )->int();
        $start_date = Input::get( 'start_date', '' )->date();
        $end_date = Input::get( 'end_date', '' )->date();
        $message_type = Input::get( 'message_type', 0 )->int();
        $message_class = Input::get( 'message_class', 0 )->int();

        $message_model = new service_Message_admin();
        $message_model->setPagesize( $pagesize );
        $message_model->setStart_date( $start_date );
        $message_model->setEnd_date( $end_date );
        $message_model->setMessage_type( $message_type );
        $message_model->setMessage_class( $message_class );

        $rs = $message_model->getMessageList();
        $this->apiReturn( $rs );
    }

    /**
     * 导出订单列表
     */
    public function export_message_list()
    {
        $message_type = Input::get( 'message_type', 0 )->int();
        $message_class = Input::get( 'message_class', 0 )->int();
        $start_date = Input::get( 'start_date', '' )->date();
        $end_date = Input::get( 'end_date', '' )->date();
        $pagesize = Input::get( 'pagesize', 50 )->int();
        $password = Input::get( 'password', '' )->required( '密码不能为空' )->string();

        $message_model = new service_Message_admin();
        $message_model->setStart_date( $start_date );
        $message_model->setEnd_date( $end_date );
        $message_model->setMessage_type( $message_type );
        $message_model->setMessage_class( $message_class );
        $message_model->setPagesize( $pagesize );
        $message_model->setPassword( $password );
        set_time_limit( 0 );
        $res = $message_model->exportSellerOrderList();
        if ( $res == false ) {
            die( $message_model->getErrorMessage() );
        }
        exit;
    }

    /**
     * 订单详情
     */
    public function detail()
    {
        $order_id = Input::get( 'order_id', 0 )->required( '订单号不能为空' )->int();
        $image_size = Input::get( 'image_size', 110 )->imageSize();

        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() );
        }
        $model = new service_order_SellerHandle_admin();
        $model->setOrder_id( $order_id );

        $checkPurview = $model->checkPurviewByItemUid( $this->memberInfo->uid );
        if ( $checkPurview === false ) {
            throw new ApiException( $model->getErrorMessage() );
        }
        $model->setUid( $this->memberInfo->uid );
        $model->setImage_size( $image_size );
        $model->setMemberInfo( $this->memberInfo );
        $order_info = $model->getOrderInfoDetail();

        $array[ 'order_info' ] = $order_info;
        $this->assign( $array );
//		echo '<pre>';
//		print_r($array);
//		die;
        $this->V( 'message/detail' );
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
            $model = new service_Message_admin();
            $rs = $model->deleteById( $id );
            // TODO DEL该分类下的所有资讯
            if ( $rs ) {
                $this->apiReturn();
            } else {
                throw new ApiException( '删除留言失败，请重试！' );
            }
        }
    }

}
