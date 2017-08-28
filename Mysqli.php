<?php
namespace SimpleChatOnline;

class Mysqli
{
    private $mysql_config;

    private $conn;

    public function __construct()
    {
        require_once "config.php";

        $this->mysql_config = $config['mysqli'];
    }

    public function lastInsertId()
    {
        return $this->insertId;
    }

    public function Insert_ID()
    {
        return $this->insert_id;
    }

    public function getAffectedRows()
    {
        return $this->affected_rows;
    }

    public function errno()
    {
        return $this->errno;
    }

    public function checkConnection()
    {
        if(@$this->ping()) {
            $this->close();
            return $this->connect();
        }
        return true;
    }

    public function quote($value)
    {
        return $this->tryReconnect([$this, 'escape_string'], [$value]);
    }

    public function errorMessage($sql)
    {
        $msg = $this->error . '<hr />'.$sql.'<hr />'.PHP_EOL;
        $msg .= "Server port: ".$this->mysql_config['port'].PHP_EOL;
        if($this->conect_errno) {
            $msg .= "Connect Error ".$this->connect_errno.'.'.$this->connect_error.PHP_EOL;
        }
        $msg .= "Message: ".$this->error.PHP_EOL;
        $msg .= "Errno: ".$this->errno.PHP_EOL;
        return $msg;
    }

    public function query($sql, $resultMode = MYSQLI_STORE_RESULT)
    {
        $result = $this->tryReconnect(['parent', 'query'], [$sql, $resultMode]);
        if(!$result) {
            trigger_error(__CLASS__.' SQL ERROR: '.$this->errorMessage($sql), E_USER_WARNING);
            return false;
        }
        if(is_bool($result)) {
            return $result;
        }
        return new MysqliRecord($result);
    }

    public function queryAsync($sql)
    {

    }

    public function connect()
    {
        $this->conn = mysqli_connect(
            $this->mysql_config['host'] . ':' . $this->mysql_config['port'],
            $this->mysql_config['user'],
            $this->mysql_config['password']
        );
        if ($this->conn->errno) {
            trigger_error("mysqli connect to server failed, port:" . $this->mysql_config['port'] . " message:" . mysqli_connect_error(), E_USER_ERROR);
            return false;
        }
        $this->set_charset($this->mysql_config['charset']);

        return true;
    }

    public function tryReconnect($call, $params)
    {
        $result = false;
        for ($i = 0; $i < 2; $i ++) {
            $result = @call_user_func_array($call, $params);
            if($result === false) {
                if($this->errno == 2013 || $this->errno == 2006) {
                    $r = $this->checkConnection();
                    if($r === true) {
                        continue;
                    }
                }else {
                    return false;
                }
            }
            break;
        }
        return $result;
    }
}

class MysqliRecord
{
    private $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function fetch()
    {
        return $this->result->fetch_assoc();
    }

    public function fetchall()
    {
        $data = [];
        while($record = $this->result->fetch_assoc()) {
            $data[] = $record;
        }
        return $data;
    }

    public function free()
    {
        $this->result->free_result();
    }

    public function __get($name)
    {
        return $this->result->{$name};
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return call_user_func_array([$this->result, $name], $arguments);
    }
}