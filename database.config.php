<?php

$db[ 'default' ][ 'hostname' ] = '127.0.0.1';           //数据库主机地址 冒号后面是端口
$db[ 'default' ][ 'port' ] = '3306';                    //数据库主机地址 冒号后面是端口
$db[ 'default' ][ 'username' ] = 'root';                //数据库连接账户名
$db[ 'default' ][ 'password' ] = '9SUxplD2owUVu';       //数据库连接密码
$db[ 'default' ][ 'database' ] = "hello";               //数据库名
$db[ 'default' ][ 'char_set' ] = "utf8";                //SET NAMES 编码
$db[ 'default' ][ 'pconnect' ] = FALSE;                 //是否打开长连接
$db[ 'default' ][ 'dbdriver' ] = "MySQLi";              //数据库类型 可以为 MySQl  MySQLi

$db[ 'message' ][ 'hostname' ] = '127.0.0.1';           //数据库主机地址 冒号后面是端口
$db[ 'message' ][ 'port' ] = '3306';                    //数据库主机地址 冒号后面是端口
$db[ 'message' ][ 'username' ] = 'root';                //数据库连接账户名
$db[ 'message' ][ 'password' ] = '9SUxplD2owUVu';       //数据库连接密码
$db[ 'message' ][ 'database' ] = "message";             //数据库名
$db[ 'message' ][ 'char_set' ] = "utf8";                //SET NAMES 编码
$db[ 'message' ][ 'pconnect' ] = FALSE;                 //是否打开长连接
$db[ 'message' ][ 'dbdriver' ] = "MySQLi";              //数据库类型 可以为 MySQl  MySQLi
define( 'DB_PREFIX', 't_' );                            //数据表前缀
define( 'DB_WS_PREFIX', 't_' );                         //数据表前缀


define( 'APP_WWW_NAME', 'www' );      //wwwroot app name
define( 'APP_MANAGE_NAME', 'manage' );      //wwwroot manage name
define( 'APP_CRONTAB_NAME', 'crontab' );      //wwwroot manage name
define( 'APP_BASE_NAME', 'base' );    //公共基础类库项目
define( 'APP_ADMIN_NAME', 'admin' ); //主域名
define( 'APP_MOBILE_NAME', 'mobile' ); //wwwroot 主域名
define( 'INDEX_URL', 'http://shop.weixinshow.com/' ); //主域名
define( 'MOBILE_URL', 'http://m.t-mac.org/' ); //主域名
define( 'STATIC_URL', 'http://public.t-mac.org/' ); //static url
define( 'UPLOAD_URL', 'http://img.t-mac.org/' ); //图片上传的host
define( 'IMAGE_URL', UPLOAD_URL . 'uploads/' ); //图片主域名
define( 'THUMB_URL', UPLOAD_URL . 'thumb/' ); //图片主域名
define( 'UPAPI_KEY', 'key_upfile_api_001@' ); //图片上传接收的私钥
define( "PRODUCTION_MODE", TRUE ); //true production_model|false dev_mode
define( "STATIC_VERSION", 6 ); //静态文件version

/**
 * +------------------------------------------------------------------------------
 * 不清楚下面配置有什么用的请不要修改：－）
 * +------------------------------------------------------------------------------
 */
define( 'APPS_PATH', TMAC_BASE_PATH . 'Apps' . DIRECTORY_SEPARATOR );                         //apps的根目录
//app项目路径根目录 /www/
define( 'APP_PATH', APPS_PATH . APP_NAME . DIRECTORY_SEPARATOR );
//application目录
define( 'APPLICATION_ROOT', APP_PATH . APPLICATION . DIRECTORY_SEPARATOR );
//var目录
define( 'VAR_ROOT', APP_PATH . VARROOT . DIRECTORY_SEPARATOR );
//Webroot Htdocs 网站目录
define( 'WEB_ROOT', str_replace( '\\', '/', dirname( __FILE__ ) . DIRECTORY_SEPARATOR ) );
//static root
define( 'STATIC_ROOT', TMAC_BASE_PATH . 'Public' . DIRECTORY_SEPARATOR );
//BASE Public目录
define( 'BASE', STATIC_URL );
//BASE_V 前台公共文件目录
define( 'BASE_V', STATIC_URL . APP_NAME . '/' );
?>