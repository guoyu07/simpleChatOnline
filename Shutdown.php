<?php
$pid = file_get_contents(__DIR__.'/swoole_master_pid.pid');
if(!empty($pid)) {
    swoole_process::kill($pid);
    echo 'successful';
}else{
    echo 'are you start this framework?';
}