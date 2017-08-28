<template>
    <div>
      <el-carousel :interval="5000" arrow="always">
      <el-carousel-item height="400" v-for="item in items" :key="item">
        <img :src="item" alt="" height="">
      </el-carousel-item>
    </el-carousel>
        <el-button class="letchat" @click="visible = true">let's chat</el-button>
        <el-dialog v-model="visible" class="dochat">
          <el-input class="uid" v-model="uid" placeholder="请输入您的qq号码"></el-input>
          <el-input class="username" v-model="username" placeholder="请输入您的昵称"></el-input>
          <div class="usex">
             <el-radio class="radio" v-model="sex" label="b">男</el-radio>
             <el-radio class="radio" v-model="sex" label="g">女</el-radio>
          </div>
          <el-button class="dochatbutton" type="primary"  v-on:click="doChat()">确定</el-button>
        </el-dialog>
    </div>
</template>
<script>
export default {
  name: 'home',
  data () {
    return {
      visible: false,
      uid: '',
      username: '',
      sex: 'b',
      items: [
        'https://zmis.me/static/uploads/2017/04/Untitled-1-1.jpg',
        'https://zmis.me/static/uploads/2017/03/未标题-2.jpg',
        'https://zmis.me/static/uploads/2017/04/Untitled-1-1.jpg',
        'https://zmis.me/static/uploads/2017/04/Untitled-1-1.jpg'
      ]
    }
  },
  methods: {
    doChat () {
      this.$router.push({name: 'chat', query: {qq: this.uid, username: this.username, sex: this.sex}})
    }
  }
}
</script>

<style scoped>
.letchat{
  margin: 5px 0;
}
.uid, .username{
  margin: 2px
}
.usex{
  padding: 8px 0;
}
img{
  width: 100%; height: auto;max-width: 100%; display: block;
}
</style>
