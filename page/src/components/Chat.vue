<template>
  <div>
    <div class="source">
      <p>源码请访问
        <a class="github-link" href="https://github.com/zmisgod/SimpleChatOnline">Github</a>
      </p>
    </div>
    <div class="user">
      <div class="user-info">
        <p class="username">欢迎您:
          <span>zmisgod</span> (
          <span>838881690</span>)  <a href="/">登出</a></p>
        <el-switch :width="oneline_button_size" on-text="online" off-text="offline" v-model="online_status" on-color="#13ce66" off-color="#ff4949"></el-switch>

      </div>
    </div>
    <div class="form">
      <div class="sendtextarea">
        <div class="user-head"><img :src="user_img" width="60" height="60"></div>
        <el-input type="textarea" :rows="2" placeholder="请输入内容" v-model="ucontent"></el-input>
      </div>
      <div class="send-to-id">
        <!-- <el-input class="to-user-id" placeholder="请输入uid" v-model="uid"></el-input> -->
        <el-button type="text" @click="dialogOnline = true">在线列表</el-button>
        <el-button type="primary">发送</el-button>
      </div>
    </div>
    <div class="chat-form">
      <el-tabs v-model="nowTabName" type="card" closable @tab-remove="removeTab">
        <el-tab-pane v-for="(item, index) in tabLists" :key="item.name" :label="item.title" :name="item.name">{{item.content}}</el-tab-pane>
      </el-tabs>
    </div>

    <el-dialog :title="countPeople" :visible.sync="dialogOnline">
      <el-table :data="gridData">
        <el-table-column label="操作">
          <template scope="scope">
            <el-button type="text" @click="addTab(nowTabName, scope.row)">开聊</el-button>
          </template>
        </el-table-column>
        <el-table-column property="username" label="昵称"></el-table-column>
        <el-table-column property="uid" label="ID"></el-table-column>
      </el-table>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'chat',
  data () {
    return {
      online_status: true, // 用户进来默认显示的在线/离线状态
      ucontent: '', // 默认的textarea的内容
      uid: '', // 默认发送给用户的uid
      user_img: '',

      countPeople: '当前在线人数 231 人', // 在线列表显示的标题
      dialogOnline: false, // 是否显示在线列表的弹层

      gridData: [{ username: 'zmisgod', uid: 8288812 }],

      oneline_button_size: 72, // 当前用户在线/离线 的switch按钮大小
      nowTabName: '2', // 当前tab name
      tabLists: [], // tab的列表
      tabIndex: 0 // 总共tab的多少
    }
  },
  mounted () {
    this.user_img = '/static/head/' + parseInt(Math.random() * 10) + '.png'
  },
  methods: {
    addTab (targetName, row) {
      let newTabName = ++this.tabIndex + ''
      this.tabLists.push({
        title: row.username,
        name: newTabName,
        content: 'chat to ' + row.username
      })
      this.nowTabName = newTabName
    },
    removeTab (targetName) {
      let tabs = this.tabLists
      let activeName = this.nowTabName
      if (activeName === targetName) {
        tabs.forEach((tab, index) => {
          if (tab.name === targetName) {
            let nextTab = tabs[index + 1] || tabs[index - 1]
            if (nextTab) {
              activeName = nextTab.name
            }
          }
        })
      }
      this.nowTabName = activeName
      this.tabLists = tabs.filter(tab => tab.name !== targetName)
    }
  }
}
</script>


<style>
.source {
  width: 100%;
  height: 40px;
  display: flex;
  background-color: #D3DCE6;
  justify-content: center;
  align-items: center;
}

.github-link {
  color: #324057;
  padding-top: 20px;
  text-decoration: none;
  font-size: 16px;
}

.user {
  display: flex;
  flex-direction: row;
  justify-content: center;
  margin-top: 10px;
}

.user-head {
  width: 80px;
  height: 80px;
  border: 1px solid #E5E9F2;
  border-radius: 50%;
  display: flex;
  margin-right: 10px;
  justify-content: center;
  align-items: center;
}

.user-info {
  display: flex;
  flex-direction: row;
  margin-left: 4px;
  padding-top: 5px;
}

.username,
.userid {
  padding: 0 5px 5px;
}

.form {
  padding: 0 10%;
  margin-top: 10px;
  width: 80%;
  display: flex;
  justify-content: center;
  flex-direction: column;
}

.el-textarea {
  display: flex;
  width: 80%;
}

.send-to-id {
  padding-right: 7%;
  display: flex;
  margin-top: 10px;
  justify-content: flex-end;
}

.to-user-id {
  width: 30%;
  margin-right: 5px;
}

.chat-form {
  width: 80%;
  padding: 0 10%;
  margin-top: 10px;
}

.is-leaf .cell {
  text-align: center
}

.sendtextarea {
  display: flex;
  flex-direction: row
}
</style>

