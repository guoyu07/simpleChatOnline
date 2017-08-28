<?php
function sendJson($code = 200, $msg = '', $data = [])
{
    header('Content-type: text/json; charset = utf-8');
    echo json_encode(['code' => $code, 'msg' => $msg, 'data' => $data]);
}