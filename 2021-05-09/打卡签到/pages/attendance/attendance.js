// pages/attendance/attendance.js

var Api = require('../../utils/api.js')

Page({
  data:{
    AMstart: '09:00',
    AMend: '12:00',
    PMstart: '14:00',
    PMend: '18:00'
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    this.setData({
      token: wx.getStorageSync('token')
    })
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
    this.getAttendance();
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  getAttendance: function() {
    wx.request({
      url: Api.information + this.data.token,
      data: {},
      method: 'POST', 
      success: (res) => {
        this.setData({
          AMstart: res.data.commutingTime[0],
          AMend: res.data.commutingTime[1],
          PMstart: res.data.commutingTime[2],
          PMend: res.data.commutingTime[3]
        })
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },

    //保存修改的上下班时间
  saveCommuterTime: function() {
    wx.request({
      url: Api.information + this.data.token,
      data: {
        commutingTime: [this.data.AMstart, this.data.AMend, this.data.PMstart, this.data.PMend] 
      },
      method: 'POST', 
      success: (res) => {
        // success
        this.getAttendance()
        wx.showToast({
          title: '修改成功',
          icon: 'success',
          duration: 2000,
          success: () => {
            wx.navigateBack({ delta: 1, })
          }
        })
      }
    })
  },

  //上下班时间监听函数设置
  bindTimeChange: function(event) {
    var value = event.detail.value
    var time = event.currentTarget.dataset.time

    switch(time) {
      case 'AMstart' : this.setData({
        AMstart: value
      })
      break;
      case 'AMend' : this.setData({
        AMend : value
      })
      break;
      case 'PMstart' : this.setData({
        PMstart: value
      })
      break;
      case 'PMend' : this.setData({
        PMend : value
      })
      break;
      default: 
        break;
    }
  },

})