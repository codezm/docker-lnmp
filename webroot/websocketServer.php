<?php
$server = new swoole_websocket_server("0.0.0.0", 9501);

$server->on('request', function (swoole_http_request $request, swoole_http_response $response) {
    echo '<pre>'; var_dump($request); echo '</pre>';
    $response->end('This is websocketServer!');
});

$server->on('open', function($server, $req) {
    echo "connection open: {$req->fd}\n";
});

$server->on('message', function($server, $frame) {
    echo "received message: {$frame->data}\n";
    $server->push($frame->fd, json_encode(["hello", "world"]));
});

$server->on('close', function($server, $fd) {
    echo "connection close: {$fd}\n";
});

$server->start();
