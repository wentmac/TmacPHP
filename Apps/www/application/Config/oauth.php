<?php

/**
  const APPID = 'wx7bf2888c2d9d1446';
  const MCHID = '1242720702';
  const KEY = 'd2bfs5s0f3sccb65ce7750cab463931e';
  const APPSECRET = 'd2bf6590f3ccb65ce77250c85463931e';
 */
//mp.weixin.qq.com admin@090.cn
$config[ 'oauth' ][ 'wechat' ] = array(
    'appid' => 'wx64e3d127ad250698',
    'mchid' => '1310300301',
    'key' => '0d884dc164a470918e6ed23b813eeb0d',//非唯 
    'appsecret' => 'fc5c28fdbd390763919bc5b61dcf68e1'
);
//open.weixin.qq.com  18610247767@qq.com 移动应用
$config[ 'oauth' ][ 'wechat_open_app' ] = array(
    'appid' => 'wx909d21a07b88772d',
    'mchid' => '1254451301',
    'key' => '0d884dc164a470918e6ed23b813eeb0d',
    'appsecret' => '55369c8b6b66b5792b70544e8ac3c806'
);
//open.weixin.qq.com  18610247767@qq.com web应用
$config[ 'oauth' ][ 'wechat_open_web' ] = array(
    'appid' => 'wx27d95194a07c031a',    
    'appsecret' => '63cec23831f341a3008298be9c000d75'
);
$config[ 'oauth' ][ 'weibo' ] = array(
    'appkey' => '1069051434',    
    'appsecret' => 'deecda50c976fbbde449e0c07cb6c72b'
);
$config[ 'oauth' ][ 'qq' ] = array(
    'appid' => '101227632',
    'appkey' => '0048387fc67adb5d7d8e89a2b8741d03'    
);
$config[ 'oauth' ][ 'qq_app' ] = array(
    'appid' => '1104646609',
    'appkey' => 'afEd74We6rxLcDuw'    
);
//mp.weixin.qq.com admin@090.cn 企业给用户付款专用
/**
$config[ 'oauth' ][ 'weixin_transfers' ] = array(
    'appid' => 'wx4b7516107a4ebebc',
    'mchid' => '1454799402',
    'key' => '427661d531ae4a10f573444eaeac6afe',
    'appsecret' => 'e73f05d9fc418d41f52171d95dcb7da5'
);
*/
$config[ 'oauth' ][ 'weixin_transfers' ] = array(
    'appid' => 'wx64e3d127ad250698',
    'mchid' => '1310300301',
    'key' => '0d884dc164a470918e6ed23b813eeb0d',
    'appsecret' => 'fc5c28fdbd390763919bc5b61dcf68e1'
);