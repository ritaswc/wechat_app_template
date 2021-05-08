var app =getApp()
Page({
  data:{
    "text":"邀请好友规则"
  },
  change:function(e){
      var id = e.currentTarget.id , data = {};
      data[id + 'Show'] = !this.data[id + 'Show'];
      this.setData(data);
      console.log(data)
  }
})