// pages/boss/boss.js

var Api = require('../../utils/api.js');

Page({
  data:{
    name: '',
    address: ''
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
    this.getInformation()
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },

  //获取公司信息
  getInformation: function() {
    wx.request({
      url: Api.information + this.data.token,
      data: {},
      method: 'POST', 
      success: (res) => {
        // success
        console.log(res)
        this.setData({
          name: res.data.name,
          address: res.data.address,
          latitude: res.data.coordinate_latitude,
          longitude: res.data.coordinate_longitude
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

  //去修改公司名称
  toCompanyName: function() {
    wx.navigateTo({
      url: '/pages/name/name?name=' + this.data.name,
    })
  },

  //去修改公司地理位置
  toAddress: function() {
    wx.navigateTo({
      url: '/pages/address/address?address=' + this.data.address,
    })
  },

  //去修改考勤时间
  toAttendance: function() {
    wx.navigateTo({
      url: '/pages/attendance/attendance',
    })
  },

  //精准定位
  handleUpdateLocation: function() {
    this.updateLocation(() => {
      wx.showToast({
        title: '更新成功',
        icon: 'success'
      })
      this.getInformation()
    })
  },

  //更新地理位置信息
  updateLocation: function(cb) {
    wx.getLocation({
      type: 'wgs84', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标
      success: (res) => {
        // 更新经度纬度
        wx.request({
          url: Api.information + this.data.token,
          data: {
            latitude: res.latitude,
            longitude: res.longitude
          },
          method: 'POST',
          success: (res) => {
            typeof cb == 'function' && cb()
          }
        })
      }
    })
  },

  //生产二维码
  getQRCode: function() {
    wx.showModal({
      title: '提示',
      content: '请在公司打卡位置生成二维码',
      success: (res) => {
        if (res.confirm) {
          console.log('用户点击确定')
          this.updateLocation(() => {
            wx.request({
              url: Api.qrcode + this.data.token,
              data: {},
              method: 'GET', 
              success: (res) => {
                wx.navigateTo({
                  url: '/pages/qrcode/qrcode?QRCodeUrl=' + res.data.QRCodeUrl 
                  + '&name=' + this.data.name 
                  +  '&address=' + this.data.address,
                })
              }
            })
          })
        }
      }
    })

  },

  //申请列表
  toApplyList: function() {
    wx.navigateTo({
      url: '/pages/applylist/applylist',
    })
  },

  //解散企业
  dissolveCompany: function() {
    wx.showModal({
      title: '警告',
      content: '解散企业会清空所有信息，您确定要解散企业吗？',
      success: (res) => {
        if (res.confirm) {
          wx.request({
            url: Api.deleteCompany + this.data.token,
            data: {},
            method: 'DELETE',
            success: function(res){
              // success
              console.log(res)
              if(res.statusCode == 200) {
                wx.showToast({
                  title: '解散成功',
                  icon: 'success'
                })
                wx.setStorage({
                  key: 'userType',
                  data: res.data.types,
                  success: function(res){
                    // success
                    wx.redirectTo({ url: '/pages/select/select', })
                  }
                })
              }
              else {
                wx.showModal({
                  title: '提示',
                  content: '解散企业失败，请稍后再试'
                })
              }
            }
          })
        }
      }
    })
  },

  feedback: function() {
    wx.navigateTo({
      url: '/pages/feedback/feedback',
    })
  }
})