<?php
$config = [
    'base' => [
        'host' => 'localhost',
        'port' => 9205
    ],
    'manage' => [
        'adminID' => '838881690'
    ],
    'swoole' => [
        'worker_num' => 1, //worker进程设置为1，这样就能将fd与用户id进行唯一绑定，不然多进程下无法判断用户是否绑定
        'heartbeat_check_interval' => 60,
        'heartbeat_idle_time' => 125,
        'daemonize' => 1,
        'log_file' => __DIR__.'/swoole.log'
    ]
];
?>