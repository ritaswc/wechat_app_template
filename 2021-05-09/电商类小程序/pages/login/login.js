// pages/login/login.js
var login = require('../../utils/uctoo-login.js');
var app = getApp();
Page({
  data: {
    group: [],
    groupArr: [],
    groupId: 0
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    console.log(options)
    login.login();
    if (options.groupId) {
      this.setData({
        groupId: options.groupId
      })
    }
    //获取群数据
     var that = this;
    wx.request({
      url: `${app.globalData.API_URL}/group`,
      data: {},
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        console.log(res)
        var arr = [];
        var arrid = [];
        for (var i = 0; i < res.data.length; i++) {
          console.log(res.data[i].shop_name)
          arr.push(res.data[i].shop_name)
          arrid.push(res.data[i].id)
        }
        console.log(arr)
        that.setData({
          group: arr,
          groupArr: arrid
        })
        console.log(that.data.groupArr)
      },

      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
  },
  bindPickerChange: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      groupId: e.detail.value
    })
  },
  onShareAppMessage: function () {
    var that = this;
    return {
      title: '微社区',
      path: '/pages/login/login?groupId=' + that.data.groupId
    }
  },
  loginbtn: function () {
    //登录

    //判断用户名和群名称，如果是该群的跳转到首页


    var that = this;
    var loginInfo = wx.getStorageSync('login')
    if (!loginInfo) {
      login.login();
    }
    wx.request({
      url: `${app.globalData.API_URL}/shops`,
      data: {
        // "group":parseInt(that.data.groupId),//通过id找群
        "group": that.data.groupArr[that.data.groupId],
        "user": loginInfo.mid

      },
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        console.log(res)
        if (res.data == 1) {
          wx.setStorageSync('groupId',that.data.groupArr[that.data.groupId])
          wx.switchTab({
            url: '/pages/index/index'
          })
        } else {
          wx.showModal({
            title: '微商城温馨提示',
            content: '你不在这个群，是否提交验证？',
            success: function (res) {
              if (res.confirm) {
                that.checkbtn();
              }
            }
          })
        }
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })


  },
  checkbtn: function () {
    //提交给后台验证
    var that = this;
    var loginInfo = wx.getStorageSync('login')
    if (!loginInfo) {
      login.login();
    }
    setTimeout(function () {
      wx.request({
        url: `${app.globalData.API_URL}/shops`,
        data: {
          // "group":parseInt(that.data.groupId),//通过id找群
          "group": that.data.groupArr[that.data.groupId],
          "user": loginInfo.mid
        },
        method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        // header: {}, // 设置请求的 header
        success: function (res) {
          console.log(res)
          if (res.data == 0) {
            wx.showToast({
              title: '验证已经提交！',
              icon: 'sucess',
              duration: 1000
            })
          } else {
            wx.showToast({
              title: '你已提交验证！',
              icon: 'fail',
              duration: 1000
            })
          }
        },
        fail: function () {
          // fail
        },
        complete: function () {
          // complete
        }
      })

    }, 1000)


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
  }
})