<?php

/**
 * 用户登录注册页面
 * ============================================================================
 * @author  by time 22014-07-07
 * 
 */
class inboundAction extends service_Controller_mobile
{

    private $quality_check_file;
    private $barcode_sellerid_file;

    //定义初始化变量

    public function __construct()
    {
        parent::__construct();
        $this->quality_check_file = '/var/www/witkey/js_back_warehouse_check.csv';
        $this->barcode_sellerid_file = '/var/www/witkey/js_library_goods_sku.csv';
        include Tmac::findFile( 'PHPExcel', APP_ADMIN_NAME );
    }

    /**
     * 解析上传的excel文件
     */
    private function parseUploadXls( $file, $type = 'xls' )
    {
        if ( !file_exists( $file ) )
            return false;

        $xlstype = $type == "xls" ? 'Excel5' : 'Excel2007';
        $objReader = PHPExcel_IOFactory::createReader( $xlstype );
        $objReader->setReadDataOnly( true );
        $objPHPExcel = $objReader->load( $file );
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString( $highestColumn );
        $excelData = array();
        for ( $row = 2; $row <= $highestRow; ++$row ) {
            $has_data = false;
            $tmp_arr = array();
            for ( $col = 0; $col <= $highestColumnIndex - 1; ++$col ) {
                //对于时间的处理 ，不处理会生成float类型数据                
                if ( $col == '16' || $col == '17' || $col == '18' ) {
                    $tmp = PHPExcel_Style_NumberFormat::toFormattedString(
                                    $objWorksheet->getCellByColumnAndRow( $col, $row )->getValue(), 'yyyy-mm-dd hh:mm:ss'
                    );
                } else {
                    $tmp = $objWorksheet->getCellByColumnAndRow( $col, $row )->getValue();
                }
                if ( !$has_data && !empty( $tmp ) ) {
                    $has_data = true;
                }
                $tmp_arr[] = $tmp;
            }
            if ( $has_data ) {
                $excelData [ $row ] = $tmp_arr;
            }
        }
        return $excelData;
    }

    public function create()
    {
        //$res = $this->parseUploadXls( $this->quality_check_file, 'xlsx' );
        //$res = $this->getQCArray();
        $res = $this->getQCarrayByPHP();        
        //取barcode=>sellerid
        $barcode_seller_map = $this->getSellerId();

        $sql_array = array();
        foreach ( $res as $qc ) {
            if ( empty( $barcode_seller_map[ $qc[ 'bwc_barcode' ] ] ) ) {
                continue;
            }
            //$sql_array[] = "REPLACE INTO js_numgen_po_inbound_1004 (`x`) VALUES ('x');";
            $sql_array[] = "REPLACE INTO js_numgen_po_inbound_1002 (`x`) VALUES ('x');";
            $sql_array[] = "set @serial=(SELECT LAST_INSERT_ID());";

            $inbound_array = $inbound_detail_array = array();
            $inbound_array[ 'ib_serial' ] = new TmacDbExpr( '@serial' );
            $inbound_array[ 'ib_company' ] = isset( $barcode_seller_map[ $qc[ 'bwc_barcode' ] ] ) ? $barcode_seller_map[ $qc[ 'bwc_barcode' ] ] : '';
            $inbound_array[ 'ib_related_code2' ] = $qc[ 'bwc_no' ];
            $inbound_array[ 'ib_type' ] = '15';
//            $inbound_array[ 'ib_warehouse_id' ] = '4';
//            $inbound_array[ 'ib_warehouse_code' ] = '1004';
//            $inbound_array[ 'ib_warehouse_name' ] = '华南仓';
            $inbound_array[ 'ib_warehouse_id' ] = '2';
            $inbound_array[ 'ib_warehouse_code' ] = '1002';
            $inbound_array[ 'ib_warehouse_name' ] = '武汉仓';
            $inbound_array[ 'ib_express_code' ] = $qc[ 'bwc_delivery_code' ];
            $inbound_array[ 'ib_sync_status' ] = '1';
            $inbound_array[ 'ib_total_qty' ] = '1';
            $inbound_array[ 'ib_total_lines' ] = '1';
            $inbound_array[ 'ib_real_qty_total' ] = '1';
            $inbound_array[ 'ib_remark' ] = '无头单委托入库|' . addslashes( $qc[ 'bwc_comment' ] );
            $inbound_array[ 'ib_creater_id' ] = 0;
            $inbound_array[ 'ib_creater_name' ] = '系统';
            $inbound_array[ 'ib_delivery_time' ] = new TmacDbExpr( 'UNIX_TIMESTAMP()' );
            $inbound_array[ 'ib_predict_time' ] = new TmacDbExpr( 'UNIX_TIMESTAMP()' );
            $inbound_array[ 'ib_real_entry_time' ] = new TmacDbExpr( 'UNIX_TIMESTAMP()' );
            $inbound_array[ 'ib_add_time' ] = strtotime( $qc[ 'bwc_add_time' ] );
            $inbound_array[ 'last_modified' ] = new TmacDbExpr( 'CURRENT_TIMESTAMP' );
            $inbound_array[ 'ib_status' ] = 1;
            $sql_array[] = $this->handleSql( 'js_inbound', $inbound_array );

            $sql_array[] = 'SET @inbound=(SELECT LAST_INSERT_ID());';
            $inbound_detail_array[ 'ibd_ib_id' ] = new TmacDbExpr( '@inbound' );
            $inbound_detail_array[ 'ibd_sku_code' ] = $qc[ 'bwc_barcode' ];
            $inbound_detail_array[ 'ibd_lot' ] = '170213999999';
            $inbound_detail_array[ 'ibd_count' ] = $qc[ 'bwc_num' ];
            if ( $qc[ 'bwc_result' ] == '1' ) {
                $ibd_unavail_count = 0;
            } else {
                $ibd_unavail_count = 1;
            }
            $inbound_detail_array[ 'ibd_real_count' ] = 0;
            $inbound_detail_array[ 'ibd_unavail_count' ] = $ibd_unavail_count;
            $inbound_detail_array[ 'last_modified' ] = new TmacDbExpr( 'CURRENT_TIMESTAMP' );
            $inbound_detail_array[ 'ibd_type' ] = 1;
            $sql_array[] = $this->handleSql( 'js_inbound_detail', $inbound_detail_array );
        }

        $filename = '/var/www/witkey/inbound.sql';
        foreach ( $sql_array as $value ) {
            file_put_contents( $filename, $value . "\r", FILE_APPEND );
        }
    }

    /**
     * "Smart" Escape String
     *
     * Escapes data based on type
     * Sets boolean and null types
     *
     * @access	public
     * @param	string
     * @return	mixed
     */
    protected function escape( $str )
    {
        $str = "'" . $str . "'";
        return $str;
    }

    private function handleSql( $table, $array )
    {
        $fields = $values = array();
        foreach ( $array as $key => $value ) {
            $fields[] = '`' . $key . '`';
            if ( $value instanceof TmacDbExpr ) {
                $values[] = $value;
            } else {
                $values[] = $this->escape( $value );
            }
        }
        $sql = 'INSERT INTO ' . $table . ' (' . implode( ', ', $fields ) . ') VALUES (' . implode( ', ', $values ) . ');';
        return $sql;
    }

    private function getQCarrayByPHP()
    {
        $array = $key_array = [];
        $row = 1;
        if ( ($handle = fopen( $this->quality_check_file, "r" )) !== FALSE ) {
            while ( ($data = fgetcsv( $handle, 1000, "," )) !== FALSE ) {
                if ( $row == 1 ) {
                    $key_array = $data;
                    $row++;
                    continue;
                }
                $num = count( $data );
                //echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ( $c = 0; $c < $num; $c++ ) {
                    //echo $data[ $c ] . "<br />\n";                    
                    $temp_array[$key_array[$c]] = $data[$c];                    
                }
                $array[] = $temp_array;
            }
            fclose( $handle );
        }
        return $array;
    }

    private function getQCArray()
    {
        $PHPReader = new PHPExcel_Reader_CSV();
        if ( !$PHPReader->load( $this->quality_check_file ) ) {
            echo 'no cvs';
            return true;
        }
        /*
          $PHPReader = new PHPExcel_Reader_Excel2007();
          if ( !$PHPReader->canRead( $this->quality_check_file ) ) {
          $PHPReader = new PHPExcel_Reader_Excel5();
          if ( !$PHPReader->canRead( $this->quality_check_file ) ) {
          echo 'no Excel';
          return true;
          }
          }
         * 
          //设置A3单元格为文本
          $objPHPExcel->getActiveSheet()->getStyle('A3')->getNumberFormat()
          ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
          //也可以设置整行或整列的style
          /*
          //E 列为文本
          $objPHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()
          ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
          //第三行为文本
          $objPHPExcel->getActiveSheet()->getStyle('3')->getNumberFormat()
          ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
         * 
         */

        //建立excel对象，此时你即可以通过excel对象读取文件，也可以通过它写入文件
        $PHPExcel = $PHPReader->load( $this->quality_check_file );
        /*         * 读取excel文件中的第一个工作表 */
        $currentSheet = $PHPExcel->getSheet( 0 );
        /*         * 取得最大的列号 */
        $allColumn = $currentSheet->getHighestColumn();
        /*         * 取得一共有多少行 */
        $allRow = $currentSheet->getHighestRow();

        $array = [ ];
        //循环读取每个单元格的内容。注意行从1开始，列从A开始
        for ( $rowIndex = 2; $rowIndex <= $allRow; $rowIndex++ ) {
            $member_info = array();
            for ( $colIndex = 'A'; $colIndex <= $allColumn; $colIndex++ ) {
                $addr = $colIndex . $rowIndex;
                $cell = $currentSheet->getCell( $addr )->getValue();
                if ( $cell instanceof PHPExcel_RichText ) {   //富文本转换字符串
                    $cell = '' . $cell->__toString();
                }
                //$member_info[ $colIndex ] = $cell;
                $key_name = $colIndex . 1;
                $key = $currentSheet->getCell( $key_name )->getValue();
                $member_info[ $key ] = $cell;
            }
            //$member_info[ 'F' ] = number_format( $member_info[ 'F' ], 0, '', '' );
            //$member_info[ 'I' ] = number_format( $member_info[ 'I' ], 0, '', '' );
            //$member_info[ 'J' ] = number_format( $member_info[ 'J' ], 0, '', '' );
            //echo $rowIndex.'<br>';
            /*
              $barcode = $member_info[ 'A' ];//质检ID
              $seller_id = $member_info[ 'B' ];//质检类型
              $seller_id = $member_info[ 'C' ];//仓库代码
              $seller_id = $member_info[ 'D' ];//订单号
              $seller_id = $member_info[ 'E' ];//质检不通过原因
              $seller_id = $member_info[ 'F' ];//快递单号
              $seller_id = $member_info[ 'G' ];//质检数量
              $seller_id = $member_info[ 'H' ];//质检结果
              $seller_id = $member_info[ 'I' ];//商品条形码
              $seller_id = $member_info[ 'J' ];//质检单号
              $seller_id = $member_info[ 'K' ];//质检人
              $seller_id = $member_info[ 'L' ];//质检备注
              $seller_id = $member_info[ 'M' ];//质检时间
             * 
             */
            $array[] = $member_info;
        }
        return $array;
    }

    private function getSellerId()
    {
        $PHPReader = new PHPExcel_Reader_CSV();
        if ( !$PHPReader->load( $this->barcode_sellerid_file ) ) {
            echo 'no cvs';
            return true;
        }

        //建立excel对象，此时你即可以通过excel对象读取文件，也可以通过它写入文件
        $PHPExcel = $PHPReader->load( $this->barcode_sellerid_file );
        /*         * 读取excel文件中的第一个工作表 */
        $currentSheet = $PHPExcel->getSheet( 0 );
        /*         * 取得最大的列号 */
        $allColumn = $currentSheet->getHighestColumn();
        /*         * 取得一共有多少行 */
        $allRow = $currentSheet->getHighestRow();


        $array = array();
        //循环读取每个单元格的内容。注意行从1开始，列从A开始
        for ( $rowIndex = 2; $rowIndex <= $allRow; $rowIndex++ ) {
            $member_info = array();
            for ( $colIndex = 'A'; $colIndex <= $allColumn; $colIndex++ ) {
                $addr = $colIndex . $rowIndex;
                $cell = $currentSheet->getCell( $addr )->getFormattedValue();
                if ( $cell instanceof PHPExcel_RichText ) {   //富文本转换字符串
                    $cell = $cell->__toString();
                }
                //echo $cell . '{|}';
                $member_info[ $colIndex ] = $cell;
            }
            //echo $rowIndex.'<br>';
            $barcode = $member_info[ 'A' ];
            $seller_id = $member_info[ 'B' ];
            $array[ $barcode ] = $seller_id;
        }
        return $array;
    }

}
