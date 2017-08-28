<?php
namespace SimpleChatOnline;

use SimpleChatOnline\Coroutine\CoroutlineException;
use SimpleChatOnline\Coroutine\Mysql;

class Server
{
    private $server;
    private $config;

    public function __construct()
    {
        require_once "config.php";
        $this->config = $config;

        $this->server = new \Swoole\Server($this->config['server']['host'], $this->config['server']['port']);

        $this->server->set($config['server_set']);
//        $this->server->on('Start', [$this, 'onStart']);
        $this->server->on('Connect', [$this, 'onConnect']);
        $this->server->on('Receive', [$this, 'onReceive']);
        $this->server->on('Close', [$this, 'onClose']);
        $this->server->on('ManagerStart', [$this, 'onManagerStart']);

        $this->onStart();
    }

    public function onManagerStart(\Swoole\Server $server)
    {
        file_put_contents($this->config['others']['dir'].'swoole_master_pid.pid', $this->server->master_pid);
    }

    public function onStart()
    {
        $this->server->start();
    }

    public function onConnect(\Swoole\Server $server, $fd)
    {
        echo 'on client connected, fd_id = '.$fd.PHP_EOL;
    }

    public function onReceive(\Swoole\Server $server, $fd, $reactor_id, $data)
    {
        require_once $this->config['others']['dir']."Coroutine/Mysql.php";
        try{
            $db = new Mysql($this->config['mysqli']);
            $db->query("INSERT INTO `crh_train` (`train_name`, `train_color`, `train_weight`, `updated_at`, `sort_by`, `inner_sort`, `disabled`)
VALUES
	('京沪高速铁路', '#CC0033', 5, 1492097261, 1, NULL, 'false')
");
            $this->server->send($fd, 'successful');
        }catch (CoroutlineException $cor) {
            $this->server->send($fd, $cor->getMessage());
        }catch (\Exception $e){
            $this->server->send($fd, $e->getMessage());
        }


    }

    public function onClose(\Swoole\Server $server, $fd, $reactor_id)
    {
        echo 'fd = '.$fd.' closed'.PHP_EOL;
    }
}
new Server();