<?php

class WebsocketServer
{
    private $_server;

    public static $uid = [];

    public static $uidInfo = [];

    private $close;

    private $admin = false;

    public static $message = [];

    public function __construct()
    {
        $this->_server = new Swoole\Websocket\Server("127.0.0.1", 9502);
        $this->_server->set([
            'worker_num' => 1, //worker进程设置为1，这样就能将fd与用户id进行唯一绑定，不然多进程下无法判断用户是否绑定
            'heartbeat_check_interval' => 60,
            'heartbeat_idle_time' => 125
        ]);

        $this->_server->on('Open', [$this, 'onOpen']);

        $this->_server->on('Message', [$this, 'onMessage']);

        $this->_server->on('Close', [$this, 'onClose']);

        $this->_server->start();
    }

    public function showData()
    {
        echo '当前在线人数: '.count(self::$uid).PHP_EOL;
    }

    public function onOpen($server, $req)
    {
        //先绑定uid与fd
        $this->bindUid(['id' => $req->get['id'], 'name' => ''], $req->fd);
        $this->showData();
    }

    public function onMessage($server, $frame)
    {
        if (!empty($server)) {
            $result = json_decode($frame->data, true);
            if(self::$uidInfo[$this->getUid($frame->fd)]['role'] == 'admin') {
                foreach (self::$uid as $v) {
                    if($v != $frame->fd) {
                        $server->push($v, json_encode(['toid' => 'system', 'content'=> 'System Broadcast: '.$result['content']]));
                    }
                }
                $server->push($frame->fd, json_encode(['toid' => 'system', 'content'=> '广播发送成功']));
            }else{
                //根据前端传递的toid获取要发送到此toid绑定的fd
                $sendToFd = isset(self::$uid[$result['toid']]) ? self::$uid[$result['toid']] : false;
                if($sendToFd === false) {
                    $server->push($frame->fd, json_encode(['toid' => 'system', 'content'=> '对方不在线，请重试']));
                }else{
                    if($sendToFd == $frame->fd) {
                        $server->push($frame->fd, json_encode(['toid' => 'system', 'content'=> '不能给自己发送消息哦！']));
                    }else{
                        if($result['content']) {
                            //发送到此toid的fd中
                            $server->push($sendToFd, json_encode(['toid' => 'id = '. $this->getUid($frame->fd), 'content' => $result['content']]));
                        }else{
                            $server->push($frame->fd, json_encode(['toid' => 'system', 'content'=> '发送不能为空']));
                        }
                    }
                }
            }
        }
    }

    public function onClose($server, $fd)
    {
        //当断开连接时，取消fd与此uid的绑定，并将uid绑定的fd取消
        $uid = $this->getUid($fd);
        $this->unsetFd($fd);
        echo "user : " . $uid.' closed'.PHP_EOL;
        $this->showData();
    }

    public function close($fd, $message)
    {
        $this->_server->close($fd, $message);
    }

    public function bindUid($uidInfo, $fd)
    {
        $isAdmin = $this->checkAdmin($uidInfo['id']);
        self::$uid[$uidInfo['id']] = $fd;
        self::$uidInfo[$uidInfo['id']] = [
            'role' => $isAdmin,
            'name' => isset($uidInfo['name']) ? $uidInfo['name'] : rand(100000, 999999),
        ];
    }

    public function getUid($fd)
    {
        $result = array_search($fd, self::$uid);
        if($result !== false) {
            return $result;
        } else {
            return false;
        }
    }

    public function unsetFd($fd)
    {
        $result = array_search($fd, self::$uid);
        if($result !== false) {
            unset(self::$uid[$result]);
            unset(self::$uidInfo[$result]);
        }
    }

    public function checkAdmin($userId)
    {
        if($userId == '838881690') {
            echo '管理员: 838881690登录 '.PHP_EOL;
            return 'admin';
        }
        return 'user';
    }
}

new WebsocketServer();