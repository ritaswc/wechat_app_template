// pages/create/create.js

var Api = require('../../utils/api.js')

Page({
  data: {
    name: '',
    location: '',
    errMsg: '',
    btnDis: true,
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    this.setData({
      token: wx.getStorageSync('token')
    })
  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  },

  //公司输入
  handleInput: function (event) {
    var flag = false
    var na = event.detail.value
    var loca = this.data.location
    if (na == '') {
      flag = true
    }
    if (loca == '') {
      flag = true
    }
    this.setData({
      name: event.detail.value,
      btnDis: flag
    })
  },

  //选择定位
  chooseLocation: function () {
    wx.chooseLocation({
      success: (res) => {
        var flag = false
        if (this.data.name == '') {
          flag = true
        }
        this.setData({
          location: res,
          btnDis: flag
        })
      }
    })
    wx.getLocation({
      type: 'wgs84',
      success: (res) => {
        this.setData({
          latitude: res.latitude,
          longitude: res.longitude
        })
      }
    })
  },

  //创建按钮
  handleCreateCompany: function () {
    this.checkInput((res) => {
      if (res == 'success') {
        console.log('验证通过')
        this.createCompany()
        wx.showNavigationBarLoading()
      }
    })
  },

  //创建公司
  createCompany: function () {
    var apiUrl = Api.company + this.data.token
    wx.request({
      url: apiUrl,
      data: {
        name: this.data.name,
        address: this.data.location.address,
        latitude: this.data.latitude,
        longitude: this.data.longitude
      },
      method: 'POST',
      success: function (res) {
        console.log(res)
        if (res.statusCode == 201) {
          //创建成功
          wx.hideNavigationBarLoading()
          wx.setStorageSync('userType', res.data.types)
          wx.showToast({
            title: '创建成功',
            icon: 'success'
          })
          wx.switchTab({ url: '/pages/workers/workers' })
        }
        else {
          //创建失败
          wx.hideNavigationBarLoading()
          if (res.statusCode == 403) {
            wx.setStorageSync('userType', 'manager')
            wx.showModal({
              title: '创建失败',
              content: '你已经创建了一个公司，点击确定查看',
              success: function (res) {
                if (res.confirm) {
                  wx.switchTab({ url: '/pages/workers/workers' })
                }
              }
            })
          }
          else {
            wx.showModal({
              title: '创建失败',
              content: '请您稍后再试'
            })
          }
        }
      }
    })
  },

  //验证输入信息
  checkInput: function (cb) {
    if (this.data.name == '') {
      this.setData({
        errMsg: '公司名称不能为空'
      })
    }
    else if (this.data.location == '') {
      this.setData({
        errMsg: '地址位置不能为空'
      })
    }
    else {
      this.setData({
        errMsg: ''
      })
      typeof cb == 'function' && cb('success')
    }
  }

})