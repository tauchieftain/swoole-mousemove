<?php

function send($code,$msg,$data) {
    $arr = array(
        'code' => $code,
        'msg' => $msg,
        'data' => $data
    );
    $json = json_encode($arr);
    return $json;
}

$server = new swoole_websocket_server("0.0.0.0", 9001);

$server->on('open', function (swoole_websocket_server $server, $request)
{
    $server->push($request->fd, send(1,'connect',$request->fd));
});

$server->on('message', function (swoole_websocket_server $server, $frame)
{
    $data = json_decode($frame->data, true);
    switch ($data['code']) {
        case 2:
            foreach ($server->connections as $fid) {
                $server->push($fid, send(2,'move',[
                    'client_id' => $frame->fd,
                    'left' => $data['left'],
                    'top' => $data['top']
                ]));
            }
            break;
    }
});

$server->on('close', function ($ser, $fd) {
    foreach ($ser->connections as $fid) {
        $ser->push($fid, send(3,'close',$fd));
    }
});

$server->start();