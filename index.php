<?php
if(!isset($_GET['id'])){
    die('请在传参出输入id=xxxxxx, xxxx为数字，如localhost/?id=838881690');
}else{
    $isAdmin = false;
    if($_GET['id'] == '838881690') {
        $isAdmin = true;
    }
}
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'HanHei SC', 'PingFang SC', 'CenturyGothic', 'Helvetica Neue', 'Helvetica', 'STHeitiSC-Light', 'SimHei', 'Arial', sans-serif;
    }

    .main-header {
        padding-top: 50px;
        font-size: 18px;
        text-align: center
    }

    .source-content {
        font-size: 18px;
        text-align: center
    }

    .demo {
        width: 100%;
        height: 100%;
        box-shadow: 2px 2px 2px #888888;
    }

    .main {
        width: 600px;
        margin: 0 auto;
    }

    .main div {
        padding-top: 5px;
    }

    #content {
        border: none;
        width: 600px;
        height: auto;
    }

    textarea,
    input,
    button {
        font-size: 18px;
        border: none;
        border-radius: 3px;
        padding: 10px;
        background-color: #f1f1f1;
    }

    button {
        padding: 6px 18px;
    }

    ul {
        padding-left: 0
    }

    ul li {
        list-style: none
    }

    .lists {
        text-align: left
    }

    .send {
        text-align: right;
    }

    .receive {
        text-align: left
    }
    #online{
        background-color: green;
        color: white;
    }
    #offline{
        background-color: red;
        color: white;
    }
    #tpointer{
        cursor: pointer;
    }
</style>

<body>
<h1 class="main-header">
    欢迎使用PHP+SWOOLE+SOCKET的SimpleChatOnline测试平台
</h1>
<p class="source-content">源码请访问<a href="https://github.com/zmisgod/SimpleChatOnline">Github</a></p>
<p class="source-content">默认id为 838881690 为管理员权限</p>
<div class="demo">

    <div class="main">
        <p>You are <span id="online">Online</span> Now!</p>
        <div>
            <textarea rows="3" cols="" id="content" placeholder="<?php if($isAdmin): ?>What do you want to broadcast<?php else: ?>What do you want to send<?php endif; ?>"></textarea>
        </div>

        <div>
            <?php if($isAdmin): ?>
                <input type="hidden" value="" id="toid">
            <?php else: ?>
                <input type="text" value="" id="toid" placeholder="to id">
            <?php endif; ?>
        </div>
        <div>
            <button onclick="sendMessage()">发送</button>
        </div>

        <ul id="lists">
            <p>发送列表：</p>
        </ul>
    </div>
</div>
</body>
<script type="text/javascript">
    var socket = new WebSocket('ws://localhost:9502?id=<?php echo $_GET['id']; ?>')
    // 打开Socket
    socket.onopen = function(event) {};
    //收到信息
    socket.onmessage = function(event) {
        var data = JSON.parse(event.data)
        var list = document.getElementById("lists")
        var toidObj = document.getElementById("toid")
        var toid = toidObj.value
        //添加 li

        var li = document.createElement("li");
        li.setAttribute('class', 'receive')
        li.innerHTML = data.content
        list.appendChild(li)
    };
    //关闭连接通知
    socket.onclose = function(event) {
        var obj = document.getElementById('online')
        obj.innerHTML = 'offline <a id="tpointer" onclick="tryAgain()">click me to try again</a>'
        obj.setAttribute('id', 'Offline')
    };

    function tryAgain() {
        history.go(0);
    }

    //发送消息
    function sendMessage() {
        var obj = document.getElementById('content')
        var content = obj.value
        var toidObj = document.getElementById("toid")
        var toid = toidObj.value
        socket.send('{"toid": "' + toid + '", "content": "' + content + '"}')
        var list = document.getElementById("lists")
        //添加 li
        var li = document.createElement("li")
        li.setAttribute('class', 'send')
        li.innerHTML = content
        list.appendChild(li)
        document.getElementById("content").value = ""
    }
</script>

</html>