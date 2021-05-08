// pages/address/address.js

var Api = require('../../utils/api.js')

Page({
  data:{
    newAddress: ''
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    this.setData({
      token: wx.getStorageSync('token'),
      originAddress: options.address
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
      newAddress: event.detail.value
    })
  },
  bindChangeAddressBtn: function() {
    wx.request({
      url: Api.information + this.data.token,
      data: {
        address: this.data.newAddress
      },
      method: 'POST', 
      success: function(res){
        console.log(res)
        wx.showToast({
          title: '修改成功',
          icon: 'success',
          duration: 2000
        })
        wx.navigateBack({
          delta: 1, // 回退前 delta(默认为1) 页面
        })
      }
    })
  }
})