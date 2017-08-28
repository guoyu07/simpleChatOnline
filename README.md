# SimpleChat
PHP + Swoole + Websocket demo

## 介绍
本次是我用swoole自己写的一个在线聊天的功能，我将慢慢完善功能。
<a href="http://im.zmis.me">在线演示地址</a>


## 环境
1. PHP
2. swoole extention

## 功能
1. 用户之间消息互发
2. 管理员发送广播（群发）
3. 查看自己是否在线，断线后可以点击重连
4. 查看在线用户列表

## 使用

1. 在cli模式下运行php WebsocketServer.php
2. 打开链接：localhost/?id=yourchatid1
3. 再打开链接：localhost/?id=yourchatid2
4. 当id=`838881690`时为管理员身份(可在配置文件中进行相关的配置)
5. 在文本框中输入你要发送的信息, 在id框中输入你要聊天的用户id，点击发送即可。

***

#### 正在开发
1. 前端界面优化,使用vue
2. 在线列表想发给谁点击就发送给谁
3. 消息保存至服务端，可查看聊天记录
4. 用户登录

#### 待开发
2. 群聊
3. 断线自动重连
