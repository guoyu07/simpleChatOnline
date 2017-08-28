<?php
namespace SimpleChatOnline;

class Shutdown
{
    public function shoudown()
    {
        require_once "config.php";
        exec('kill -USR1 '.file_get_contents($config['others']['dir'].'swoole_master_pid.pid'));
    }
}

$s = new Shutdown();
$s->shoudown();