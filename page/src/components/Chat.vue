<template>
  <div>
      <div class="source">
        <p>源码请访问<a class="github-link" href="https://github.com/zmisgod/SimpleChatOnline">Github</a></p>
      </div>
      <div class="user">
          <div class="user-head">

          </div>
          <div class="user-info">
            <p class="username">zmisgod</p>
            <p class="userid">838881690</p>
            <el-switch :width="oneline_button_size" on-text="online" off-text="offline" v-model="online_status" on-color="#13ce66" off-color="#ff4949"></el-switch>
          </div>
      </div>
      <div class="form">
        <el-input type="textarea" :rows="2" placeholder="请输入内容" v-model="ucontent"></el-input>
        <div class="send-to-id">
            <el-input class="to-user-id" placeholder="请输入uid" v-model="uid"></el-input>
            <el-button type="primary">发送</el-button>
            <el-button type="text" @click="dialogTableVisible = true">在线列表</el-button>
        </div>
      </div>
      <div class="chat-form">
          <el-tabs v-model="editableTabsValue2" type="card" closable @tab-remove="removeTab">
            <el-tab-pane v-for="(item, index) in editableTabs2" :key="item.name" :label="item.title" :name="item.name">{{item.content}}</el-tab-pane>
          </el-tabs>
      </div>


      <el-dialog title="当前在线人数" :visible.sync="dialogTableVisible">
      <el-table :data="gridData">
        <el-table-column label="操作">
          <template scope="scope">
            <el-button type="text" @click="addTab(editableTabsValue2)">点击交谈</el-button>
          </template>
        </el-table-column>
        <el-table-column property="uid" label="uid" ></el-table-column>
        <el-table-column property="name" label="姓名"></el-table-column>
      </el-table>
      </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'chat',
  data () {
    return {
      online_status: true,
      ucontent: '',
      uid: '',

      dialogTableVisible: false,

      gridData: [{name: 'zmisgod', uid: 8288812}],

      oneline_button_size: 72,
      editableTabsValue2: '2',
      editableTabs2: [{
        title: 'Tab 1',
        name: '1',
        content: 'Tab 1 content'
      }, {
        title: 'Tab 2',
        name: '2',
        content: 'Tab 2 content'
      }],
      tabIndex: 2
    }
  },
  methods: {
    addTab (targetName) {
      let newTabName = ++this.tabIndex + ''
      this.editableTabs2.push({
        title: 'New Tab',
        name: newTabName,
        content: 'New Tab content'
      })
      this.editableTabsValue2 = newTabName
    },
    removeTab (targetName) {
      let tabs = this.editableTabs2
      let activeName = this.editableTabsValue2
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
      this.editableTabsValue2 = activeName
      this.editableTabs2 = tabs.filter(tab => tab.name !== targetName)
    }
  }
}
</script>


<style>
.source{
    width: 100%;
    height: 40px;
    display: flex;
    background-color: #D3DCE6;
    justify-content: center;
    align-items: center;
}
.github-link{
    color: #324057;
    padding-top: 20px;
    text-decoration: none;
    font-size: 16px;
}
.user{
    display: flex;
    flex-direction: row;
    justify-content: center;
    margin-top: 10px;
}
.user-head{
    width: 80px;
    height: 80px;
    background-color: #13CE66;
    border-radius: 50%;
    display: flex;
}
.user-info{
    display: flex;
    flex-direction: column;
    margin-left: 4px;
    padding-top: 5px;
}
.username, .userid{
    padding: 0 0 5px;
    text-align: left;
}
.form{
    padding: 0 10%;
    margin-top: 10px;
    width: 80%;
    display: flex;
    justify-content: center;
    flex-direction: column;
}
.send-to-id{
    display: flex;
    margin-top: 10px;
    justify-content: flex-start;
}
.to-user-id{
    width: 30%;
    margin-right: 5px;
}
.chat-form{
    width: 80%;
    padding: 0 10%;
    margin-top: 10px;
}
</style>

