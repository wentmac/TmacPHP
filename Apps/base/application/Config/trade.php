<?php

$config[ 'trade' ][ 'trade_type' ] = array(
    '0' => '交易', //白银
    '1' => '兑换', //美元日元
    '2' => '充值', //美元日元
    '3' => '免费领取', //美元日元
    '4' => '推广赠送'
);

$config[ 'trade' ][ 'trade_status' ] = array(
    '0' => '未确认', //白银
    '1' => '已下单', //美元日元
    '2' => '已经成单', //美元日元
    '3' => '订单完成'
);

$config[ 'trade' ][ 'trade_exchange_type' ] = array(
    '0' => '',
    '1' => '富豪币=>购物券',
    '2' => '购物券=>富豪币'
);
$config[ 'trade' ][ 'trade_trend_payment' ] = array(
    '-1' => '买跌',
    '0' => '',
    '1' => '买涨'
);
$config[ 'trade' ][ 'trade_trend_result' ] = array(
    '-1' => '亏',
    '0' => '',
    '1' => '赢'
);
$config[ 'trade' ][ 'trade_uid_type' ] = array(
    '0' => '所有下级用户',
    '1' => '只看自己'
);