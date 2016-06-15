<?php

/**
 * WEB 后管理 学校模块
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhuqiang
 * $Id: School.class.php 6 2014-10-01 15:13:57Z 
 * http://www.t-mac.org；
 */
class service_Message_admin extends service_Message_base
{

    private $pagesize;
    private $export_start_page;
    private $export_end_page;
    private $start_date;
    private $end_date;
    private $message_type;
    private $message_class;
    private $password;

    function setPagesize( $pagesize )
    {
        $this->pagesize = $pagesize;
    }

    function setExport_start_page( $export_start_page )
    {
        $this->export_start_page = $export_start_page;
    }

    function setExport_end_page( $export_end_page )
    {
        $this->export_end_page = $export_end_page;
    }

    function setStart_date( $start_date )
    {
        $this->start_date = strtotime( $start_date );
    }

    function setEnd_date( $end_date )
    {
        $this->end_date = strtotime( $end_date );
    }

    function setMessage_type( $message_type )
    {
        $this->message_type = $message_type;
    }

    function setMessage_class( $message_class )
    {
        $this->message_class = $message_class;
    }

    function setPassword( $password )
    {
        $this->password = $password;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 取订单列表的where语句
     * $this->order_status;
     * $this->uid;
     * $this->getOrderListWhere();
     */
    public function getMessageWhere()
    {
        $where = 'is_delete=0';
        if ( !empty( $this->start_date ) && !empty( $this->end_date ) ) {
            $array = array( $this->start_date, $this->end_date );
            $this->start_date = min( $array );
            $this->end_date = max( $array );
        }
        if ( !empty( $this->start_date ) ) {
            $where .= ' AND message_time>=' . $this->start_date;
        }
        if ( !empty( $this->end_date ) ) {
            $where .= ' AND message_time<=' . $this->end_date;
        }
        if ( !empty( $this->message_type ) ) {
            $where .= " AND message_type={$this->message_type}";
        }
        //解析$query_string
        if ( !empty( $this->message_class ) ) {
            $where .= " AND message_class='{$this->message_class}'";
        }
        return $where;
    }

    /**
     *
      取卖家的订单列表
      $order_model->setUid( $this->memberInfo->uid );
      $order_model->setQuery_string( $query_string );
      $order_model->setPagesize( $pagesize );
      $order_model->setImage_size( $image_size );
      $order_model->setOrder_status($order_status);

      $rs = $order_model->getMessageList();
     */
    public function getMessageList()
    {
        $message_dao = dao_factory_base::getMessageDao();

        $where = $this->getMessageWhere();

        $message_dao->setWhere( $where );
        $count = $message_dao->getCountByWhere();

        if ( $count === false ) {
            $retmsg = 0;
        } else {
            $retmsg = 1; //业务返回信息
        }
        $pages = $this->P( 'Pages' );
        $pages->setTotal( $count );
        $pages->setPrepage( $this->pagesize );
        $limit = $pages->getSqlLimit();

        $message_array = array();
        if ( $count > 0 ) {
            $message_dao->setLimit( $limit );
            $message_dao->setField( '*' );
            $message_dao->setOrderby( 'message_id DESC' );
            $res = $message_dao->getListByWhere();

            $message_class_array = Tmac::config( 'message.message.message_class', APP_BASE_NAME );
            foreach ( $res as $value ) {
                $value->message_class_text = isset( $message_class_array[ $value->message_type ][ $value->message_class ] ) ? $message_class_array[ $value->message_type ][ $value->message_class ] : '';
                $value->message_time = date( 'Y-m-d H:i:s', $value->message_time );
                $value->mobile = substr( $value->mobile, 0, 7 ) . '****';
                $message_array[] = $value;
            }
        }
        $retHeader = array(
            'totalput' => $count,
            'totalpg' => intval( ceil( $count / $this->pagesize ) ),
            'pagesize' => $this->pagesize,
            'page' => $pages->getNowPage()
        );
        $return = array(
            'retHeader' => $retHeader,
            'retcode' => 'message_list',
            'retmsg' => $retmsg,
            'reqdata' => $message_array,
        );
        return $return;
    }

    /**
     *
      取卖家的订单列表
      $order_model->setUid( $this->memberInfo->uid );
      $order_model->setQuery_string( $query_string );
      $order_model->setOrder_status( $order_status );
      $order_model->setMember_type( $this->memberInfo->member_type );
      $order_model->setMemberInfo( $this->memberInfo );
      $order_model->setExport_start_page( $export_start_page );
      $order_model->setExport_end_page( $export_end_page );atus);

      $rs = $order_model->exportSellerOrderList();
     */
    public function exportSellerOrderList()
    {
        if ( $this->password <> service_Message_base::private_key ) {
            $this->errorMessage = '密码不正确';
            return false;
        }
        $message_dao = dao_factory_base::getMessageDao();

        $where = $this->getMessageWhere();

        $message_dao->setWhere( $where );
        $count = $message_dao->getCountByWhere();

        if ( $count === false ) {
            $retmsg = 0;
        } else {
            $retmsg = 1; //业务返回信息
        }
        
        /**
        $pages = $this->P( 'Pages' );
        $pages->setTotal( $count );
        $pages->setPrepage( $this->pagesize );
        $limit = $pages->getSqlLimit();         
         */

        $message_array = array();
        if ( $count > 0 ) {
            //$message_dao->setLimit( $limit );
            //$message_dao->setField( 'order_id,order_sn,order_status,refund_status,order_amount,consignee,commission_fee,shipping_fee,item_uid,shop_name,order_goods_detail,refund_status,have_return_service,order_refund_id,supplier_mobile,goods_uid,create_time,demo_order,coupon_code,coupon_money,uid' );
            $message_dao->setOrderby( 'message_id DESC' );
            $res = $message_dao->getListByWhere();

            $message_class_array = Tmac::config( 'message.message.message_class', APP_BASE_NAME );

            include Tmac::findFile( 'Des', APP_MOBILE_NAME );
            $des = new Des();
            $des->setKey( service_Message_base::private_key );

            foreach ( $res as $value ) {
                $value->message_class_text = isset( $message_class_array[ $value->message_type ][ $value->message_class ] ) ? $message_class_array[ $value->message_type ][ $value->message_class ] : '';
                $value->message_time = date( 'Y-m-d H:i:s', $value->message_time );
                $value->mobile = substr( $value->mobile, 0, 7 ) . $des->decode( substr( $value->mobile, 7 ) );
                $message_array[] = $value;
            }
//            echo '<Pre>';
//            print_r( $message_array );
//            die;

            include Tmac::findFile( 'PHPExcel', APP_ADMIN_NAME );
            $objPHPExcel = new PHPExcel();

            $start_date = date( 'Y-m-d', $this->start_date );
            $end_date = date( 'Y-m-d', $this->end_date );
            $title = "留言{$count}个{$start_date}到{$end_date}|" . date( 'Y-m-d H:i:s' );
            // Set document properties        
            $objPHPExcel->getProperties()->setCreator( "Maarten Balliauw" )
                    ->setLastModifiedBy( "zhangwentao" )
                    ->setTitle( $title );
            //设置当前的sheet   
            $objPHPExcel->setActiveSheetIndex( 0 );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 4, 1, $title );
            $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth( 10 );
            $xls_header = array( 'ID', '姓名', '手机号码', '身份证号码', '类型', '额度', 'IP', '留言时间' );
            for ( $i = 0; $i < count( $xls_header ); $i++ ) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $i, 2, $xls_header[ $i ] );
            }
            $row = 3;
            foreach ( $res as $value ) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 1, $row, $value->message_id );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 2, $row, $value->username );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 3, $row, $value->mobile );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 4, $row, ' '.$value->id_card );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 5, $row, $value->message_class_text );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 6, $row, $value->credit );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 7, $row, $value->ip );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 8, $row, $value->message_time );
                $row++;
            }
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 9, $row, '总数：' );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 10, $row, $count );

            $objPHPExcel->getActiveSheet()->getStyle( 'A3:A' . $count )->getAlignment()->setWrapText( TRUE );
            $objPHPExcel->getActiveSheet()->getStyle( 'E' . $count )->getAlignment()->setWrapText( TRUE );
            //E 列为文本
            $objPHPExcel->getActiveSheet()->getStyle( 'G' )->getNumberFormat()
                    ->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
            // Redirect output to a client’s web browser (Excel2007)
            header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
            header( 'Content-Disposition: attachment;filename=' . $title . '.xlsx' );
            header( 'Cache-Control: max-age=0' );
// If you're serving to IE 9, then the following may be needed
            header( 'Cache-Control: max-age=1' );

// If you're serving to IE over SSL, then the following may be needed
            header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' ); // Date in the past
            header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ); // always modified
            header( 'Cache-Control: cache, must-revalidate' ); // HTTP/1.1
            header( 'Pragma: public' ); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel2007' );
            $objWriter->save( 'php://output' );
        }
    }

    /**
     * del
     * @param int $class_id
     */
    public function deleteById( $id )
    {
        $dao = dao_factory_base::getMessageDao();

        $dao->getDb()->startTrans();
        $entity_Message_base = new entity_Message_base();
        $entity_Message_base->is_delete = 1;
        if ( strpos( $id, ',' ) === false ) {
            $dao->setPk( $id );
            $dao->updateByPk( $entity_Message_base );
        } else {
            $dao->setWhere( "message_id IN({$id})" );
            $dao->updateByWhere( $entity_Message_base );
        }

        if ( $dao->getDb()->isSuccess() ) {
            $dao->getDb()->commit();
            return true;
        } else {
            $dao->getDb()->rollback();
            return false;
        }
    }

}
