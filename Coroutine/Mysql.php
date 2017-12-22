<?php
namespace SimpleChatOnline\Coroutine;

class Mysql
{
    private $mysql;
    private $config;

    public function __construct($config)
    {
        $this->mysql = new \Swoole\MySQL;
        $this->config = $config;
    }

    /**
     * @param $sql
     */
    public function query($sql)
    {
        $db = $this->mysql;
        $db->connect($this->config, function ($db, $result) use ($sql) {
            $db->query($sql, function (\Swoole\Coroutine\MySQL $db, $result) {
                if ($result === false) {
                    return ['code' => $db->errno, 'msg' => $db->error];
                } elseif ($result === true) {
                    return ['code' => 200, 'data' => $db->affected_rows, 'insert_id' => $db->insert_id];
                } else {
                    $db->close();
                    return ['code' => 200, 'data' => $result];
                }
            });
        });
    }

    public function asyncQuery($sql)
    {
        $db = new \mysqli();
        $db->connect('127.0.0.1', 'root', '111111', 'mytest');
        $db->query('show tables', MYSQLI_ASYNC);
        \swoole_event_add(\swoole_get_mysqli_sock($db), function($db_sock){
            global $db;
            $res = $db->reap_async_query();
            return $res->fetch_all(MYSQLI_ASSOC);
        });
    }
}