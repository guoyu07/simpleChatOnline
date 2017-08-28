<?php
namespace SimpleChatOnline;

class WebsocketServer
{
    private $_server;

    public static $uid = [];

    public static $uidInfo = [];

    private $close;

    private $admin = false;

    private $config = [];

    public static $message = [];

    public function __construct()
    {
        require_once __DIR__.'/config.php';
        $this->config = $config;

        $this->_server = new Swoole\Websocket\Server($this->config['websocket']['host'], $this->config['websocket']['port']);
        $this->_server->set($this->config['websocket_set']);

        $this->_server->on('Open', [$this, 'onOpen']);

        $this->_server->on('Message', [$this, 'onMessage']);

        $this->_server->on('Close', [$this, 'onClose']);

        $this->_server->start();
    }

    /**
     * 显示在线人数
     */
    public function showData()
    {
        echo '当前在线人数: '.count(self::$uid).PHP_EOL;
    }

    /**
     * 连接
     *
     * @param $server
     * @param $req
     */
    public function onOpen($server, $req)
    {
        //判断是否为统计数据的链接
        if(isset($req->get['type']) && $req->get['type'] == 'count') {
            echo 'show count';
        }else{
            //先绑定uid与fd
            if(!$this->bindUid(['id' => $req->get['id'], 'name' => ''], $req->fd)){
                $this->sendToFd($server, $req->fd, 'system', '', 1);
            }
            $this->showData();
        }
    }

    /**
     * error code
     *
     * @var array
     */
    public static $error_no = [
        0 => 'OK',
        1 => 'Bind id is online now',
        2 => 'Your chatter is Offline',
        3 => 'Your message is empty',
        4 => 'Your send message is empty',
        5 => 'Null people is online',
        6 => 'Do not send message to yourself'
    ];

    /**
     * 发送给id
     *
     * @param $server
     * @param $fd
     * @param $fromId
     * @param $message
     * @param int $errorCode
     * @return mixed
     */
    public function sendToFd($server, $fd, $fromId, $message, $errorCode = 0)
    {
        return $server->push($fd, json_encode(
            [
                'toid' => $fromId,
                'content' => $message,
                'code' => $errorCode,
                'msg' => self::$error_no[$errorCode],
                'time' => date('Y-m-d H:i:s')
            ]
        ));
    }

    /**
     * 发送给所有人
     *
     * @param $server
     * @param $message
     * @param $nowFd
     */
    public function sendToAllFd($server, $message, $nowFd)
    {
        $i = 0;
        if (count(self::$uid) > 0) {
            foreach (self::$uid as $v) {
                if ($v != $nowFd) {
                    $i++;
                    $this->sendToFd($server, $v, 'system', '[System Broadcast]: ' . $message);
                }
            }
            $this->sendToFd($server, $nowFd, 'system', '[System]: 广播发送成功, 成功广播到' . $i . '个用户');
        }else{
            $this->sendToFd($server, $nowFd, 'system', '', 5);
        }
    }

    public function onMessage($server, $frame)
    {
        if (!empty($server)) {
            $result = json_decode($frame->data, true);
            if(isset($result['type'])) {
                switch ($result['type']){
                    case 'chat':
                        if(empty($result['content'])) {
                            $this->sendToFd($server, $frame->fd, 'system', '', 3);
                        }
                        //根据前端传递的toid获取要发送到此toid绑定的fd
                        $sendToFd = isset(self::$uid[$result['toid']]) ? self::$uid[$result['toid']] : false;
                        if(self::$uidInfo[$this->getUid($frame->fd)]['role'] == 'admin' && $sendToFd === false) {
                            $this->sendToAllFd($server, $result['content'], $frame->fd);
                        }else {
                            if ($sendToFd === false) {
                                $this->sendToFd($server, $frame->fd, 'system', '', 2);
                            } else {
                                if ($sendToFd == $frame->fd) {
                                    $this->sendToFd($server, $frame->fd, 'system', '', 6);
                                } else {
                                    //发送到此toid的fd中
                                    $this->sendToFd($server, $frame->fd, $this->getUid($frame->fd), $result['content']);
                                }
                            }
                        }
                        break;
                    case 'count':
                        $this->sendToFd($server, $frame->fd, 'system', array_values(array_keys(self::$uid)));
                        break;
                }
            }
        }
    }

    /**
     * 关闭操作
     *
     * @param $server
     * @param $fd
     */
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
        if(isset(self::$uid[$uidInfo['id']])) {
            return false;
        }
        self::$uid[$uidInfo['id']] = $fd;
        self::$uidInfo[$uidInfo['id']] = [
            'role' => $isAdmin,
            'name' => isset($uidInfo['name']) ? $uidInfo['name'] : rand(100000, 999999),
        ];
        return true;
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
        if($userId == $this->config['manage']['adminID']) {
            echo '管理员: '.$this->config['manage']['adminID'].'登录 '.PHP_EOL;
            return 'admin';
        }
        return 'user';
    }
}

new WebsocketServer();