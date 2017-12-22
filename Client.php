<?php
namespace SimpleChatOnline;

class Client
{
    private $client;
    public $config;

    public function __construct()
    {
        require_once "config.php";
        $this->config = $config;

        $this->client = new \Swoole\Client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC);
        $this->onConnect($this->client);
    }

    public function chat($user_id, $user_msg, $type)
    {
        if($this->client->isConnected()) {
            $this->client->send(json_encode([
                'user_id' => $user_id,
                'msg' => $user_msg,
                'time' => time(),
                'type' => $type
            ]));
            $response = $this->client->recv();
            if($response && $response == 'successful') {
                sendJson(200, 'successful');
            }else{
                sendJson(400, 'send message failed');
            }
        }
    }

    public function onConnect(\Swoole\Client $client)
    {
        $this->client->connect($this->config['server']['host'], $this->config['server']['port']);
    }

    public function onReceive(\Swoole\Client $client, $data)
    {
    }

    public function onClose(\Swoole\Client $client)
    {
        echo 'client is closed'.PHP_EOL;
    }

    public function onError(\Swoole\Client $client)
    {
        echo 'error'.PHP_EOL;
    }
}
require_once "Utility.php";
$data = new Client();
if(isset($_GET['type']) && isset($_GET['user_id']) && isset($_GET['message']) && !empty($_GET['message'])) {
    $type = $_GET['type'];
    $user_id = intval($_GET['user_id']);
    $message = htmlentities($_GET['message']);
    if(!in_array($type, $data->config['others']['type'])) {
        sendJson(500, 'error');
    }
    if($user_id <= 0) {
        sendJson(500, 'error');
    }
    $data->chat($user_id, $message, $type);
}else{
    sendJson(500, 'error');
}
