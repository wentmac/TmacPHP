<?php
//Log::getInstance( 'api_post_goods_log' )->write( $errorMessage . var_export( $rs, true ) . '|' . var_export( $_GET, true ) . var_export( $_POST, true ) );

$config[ 'log' ][ 'sms_error' ] = array(
    'File' => TMAC_BASE_PATH . APP_MOBILE_NAME . DIRECTORY_SEPARATOR . VARROOT . DIRECTORY_SEPARATOR . 'Log' . DIRECTORY_SEPARATOR. 'sms_error' . DIRECTORY_SEPARATOR . 'log-[Y-m-d].log',
    'Append' => true,
    'ConversionPattern' => '[Y-m-d H:i:s]'
);
$config[ 'log' ][ 'post_error' ] = array(
    'File' => TMAC_BASE_PATH . APP_MOBILE_NAME . DIRECTORY_SEPARATOR . VARROOT . DIRECTORY_SEPARATOR . 'Log' . DIRECTORY_SEPARATOR. 'post_error' . DIRECTORY_SEPARATOR . 'log-[Y-m-d].log',
    'Append' => true,
    'ConversionPattern' => '[Y-m-d H:i:s]'
);