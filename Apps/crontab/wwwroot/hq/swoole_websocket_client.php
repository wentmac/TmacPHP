<?php
$cli = new swoole_http_client('192.168.31.135', 9501);

$cli->on('message', function ($_cli, $frame) {
    var_dump($frame);
});

$cli->upgrade('/', function ($cli) {
    echo $cli->body;
    $cli->push("hello world");
});

exit;

$i = 0;
$cli = new Swoole\Async\WebSocket('192.168.1.135', 9501);
$cli->on('open', function(Swoole\Async\WebSocket $o, $header){
    $_send = str_repeat('A', rand(700, 900));
    $n = $o->send($_send);
    echo "sent: " . strlen($_send) . ' bytes, ' . "n=$n\n";
});
$cli->on('message', function(Swoole\Async\WebSocket $o, $frame){
    global $i;
    echo "$i\trecv: ".strlen($frame->data)." bytes\n";
    $i++;
});
$cli->connect();