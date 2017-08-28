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

    public function query($sql)
    {
        $db = $this->mysql;
        $db->connect($this->config, function ($db, $result) use($sql) {
            echo $sql;
            $db->query($sql, function (\Swoole\MySQL $db, $result) {
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
}