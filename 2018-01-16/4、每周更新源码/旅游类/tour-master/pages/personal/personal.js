// pages/personal/personal.js
let app=getApp()
Page({
  data:{
    userInfo:{}
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    app.getUserInfo((res)=>{     
      let info={
        avatarUrl:res.avatarUrl,
        nickName:res.nickName,
        tel:17708565327,
        grade:2,
        vip:true,
        integrate:100,
        Cash:20
      }
      this.setData({
        userInfo:info
      })
    })
  },
  edit(){
    console.log(1)
    let userInfo=JSON.stringify(this.data.userInfo);
    wx.navigateTo({
      url: '/pages/personal/modify-info/modify-info?userInfo='+userInfo+''
    })
  }
})