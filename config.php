<?php
$config = [
    //mysql config
    'mysqli' => [
        'host' => 'localhost',
        'port' => 3306,
        'user' => 'root',
        'password' => '111111',
        'charset' => 'utf8',
        'database' => 'mytest'
    ],
    //websocket
    'websocket' => [
        'host' => 'localhost',
        'port' => 9205
    ],
    //swoole server
    'server' => [
        'host' => 'localhost',
        'port' => 9204
    ],
    // client config
    'client_set' => [
        'log_file' => __DIR__.'/swoole_client.log'
    ],

    //swoole server config
    'server_set' => [
        'worker_num' => 1,
        'reactor_num' => 2,
        'daemonize' => 1,
        'log_file' => __DIR__.'/swoole_server.log'
    ],
    'manage' => [
        'adminID' => '838881690'
    ],
    //websocket config
    'websocket_set' => [
        'worker_num' => 1, //worker进程设置为1，这样就能将fd与用户id进行唯一绑定，不然多进程下无法判断用户是否绑定
        'heartbeat_check_interval' => 60,
        'heartbeat_idle_time' => 125,
        'daemonize' => 1,
        'log_file' => __DIR__.'/swoole.log'
    ],
    //others config
    'others' => [
        'type' => [
            'chat', //聊天
            'groupChat',  //群聊
            'broadcast' // 广播
        ],
        'dir' => __DIR__.'/'
    ],
];
?>