// pages/name/name.js

var Api = require('../../utils/api.js')

Page({
  data:{
    newName: ''
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    this.setData({
      token: wx.getStorageSync('token'),
      originName: options.name
    })
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  bindInputOnchange: function(event) {
    this.setData({
      newName: event.detail.value
    })
  },
  bindChangeNameBtn: function() {
    wx.request({
      url: Api.information + this.data.token,
      data: {
        name: this.data.newName
      },
      method: 'POST', 
      success: function(res){
        console.log(res)
        wx.showToast({
          title: '修改成功',
          icon: 'success'
        })
        wx.navigateBack({
          delta: 1, // 回退前 delta(默认为1) 页面
        })
      }
    })
  }
})