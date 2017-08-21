<template>
  <div id="app">
    <header>
      <p v-if="online" class="user-status">
        <span>登录</span>|<span>注册</span>
        <span></span>
      </p>
      <h2 class="project-title">欢迎使用PHP + Swoole + Websocket 的SimpleChatOnline测试平台</h2>
    </header>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: 'app',
  data () {
    return {
      online: false
    }
  }
}
</script>

<style>
body{
  margin: 0;
  padding: 0
}
p{
  margin: 0
}
#app {
  font-family: "Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
header{
  width: 100%;
  height: 60px;
  background-color: #20A0FF;
  justify-content: center;
  display: flex;
  align-items: center;
}
.user-status{
  color: #fff
}
.project-title{
  color: #fff;
  font-size: 18px;
  margin: 0
}
</style>
