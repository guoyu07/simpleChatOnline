<script type="text/javascript">
    // 创建一个Socket实例
    var socket = new WebSocket('ws://localhost:9502?id=<?php echo $_GET['id'] ?>');
    // 打开Socket 
    socket.onopen = function(event) {};
    //收到信息
    socket.onmessage = function(event) {
        var data =  JSON.parse(event.data);
        var list = document.getElementById("lists");
        var toidObj = document.getElementById("toid")
        var toid = toidObj.value
        //添加 li
　　　　var li = document.createElement("li");
　　　　li.innerHTML = data.toid + "："+data.content;
        list.appendChild(li);
    };
    //关闭连接通知
    socket.onclose = function(event) {
        console.log('Client has closed.\n');
    };
    //发送消息
    function sendMessage() {
        var obj = document.getElementById('content');
        var content = obj.value;
        var toidObj = document.getElementById("toid")
        var toid = toidObj.value
        socket.send('{"toid": "'+toid+'", "content": "'+content+'"}');
        var list = document.getElementById("lists");
        //添加 li
　　　　var li = document.createElement("li");
　　　　li.innerHTML = "i："+content;
        list.appendChild(li);
        document.getElementById("content").value="";
    }
</script>

<body>
    <textarea rows="3" cols="" id="content"></textarea>
    <input type="text" value="" id="toid">发送给谁
    <button onclick="sendMessage()">发送</button>

    <ul id="lists">
        
    </ul>
</body>