<template>
    <div>
        <el-button @click="visible = true">let's chat</el-button>
        <el-dialog v-model="visible" title="Hello world">
          <el-input v-model="qq" placeholder="请输入您的qq号码"></el-input>
          <el-button type="primary"  v-on:click="doChat()">确定</el-button>
        </el-dialog>
    </div>
</template>
<script>
export default {
  name: 'home',
  data () {
    return {
      visible: false,
      qq: ''
    }
  },
  methods: {
    doChat () {
      this.$router.push({name: 'chat', query: {qq: this.qq}})
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-block;
  margin: 0 10px;
}

a {
  color: #42b983;
}
</style>
