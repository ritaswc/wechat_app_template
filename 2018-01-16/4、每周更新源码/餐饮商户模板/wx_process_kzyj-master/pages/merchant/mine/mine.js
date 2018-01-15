var app = getApp()
const { aboutMe } = require('../../../components/mine-top/mine-top.js');
const shopInfo = [{name:"商户",content:"商户名称"},{name:"简介",content:"商户名称商户名称商户名称商户名称"}];

const shopTag = [{name:'标签',content:'标签A'},{name:'营业时间',content:'00:00:00--00:00:00'},{name:'联系方式',content:'13222222222'}];


Page({
  data: {
    motto: 'Hello World',
    userInfo: {},
    shopInfo:shopInfo,
    shopTag:shopTag,
    positionInfo:[{name:'常用位置',content:'XXXXXXXXXXXXXXXXX'}]
  },
  
  onLoad: function () {
    const _this = this;
    // wx.showNavigationBarLoading()
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      _this.setData({
        userInfo:userInfo
      })
    })
  },
  aboutMe:aboutMe,
  // 修改信息
  alterMineInfo:function(){
    wx.navigateTo({
      url: '../edit-mine-info/edit-mine-info',
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  }
})
