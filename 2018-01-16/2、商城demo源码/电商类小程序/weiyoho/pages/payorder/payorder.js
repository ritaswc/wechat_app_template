// pages/payorder/payorder.js
var uctooPay = require('../../utils/uctoo-pay.js')
var app = getApp()
Page({
  data: {
    payorder: [],
    radioItems: {
      name:'',
      phone:'',
      address:'请选择物流地址'
    },
  },
  //更多物流地址
  addmore: function () {
    wx.navigateTo({
      url: '/pages/address/user-address/user-address',
      success: function (res) {
        // success
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
  },
  radioChange: function (e) {
    console.log('radio发生change事件，携带value值为：', e.detail.value);
    var radioItems = this.data.radioItems;
    var addname = wx.getStorageSync('address')
    console.log(addname[0])
    for (var i = 0, len = addname.length; i < len; ++i) {
      radioItems[i].checked = radioItems[i].value == e.detail.value;
      radioItems[i].name = addname[i].province + addname[i].city + addname[i].town + addname[i].address;
    }
    this.setData({
      radioItems: radioItems
    });
  },
  onLoad: function (options) {
    var that = this;
    // 页面初始化 options为页面跳转所带来的参数
     //获取物流地址
    var addname = wx.getStorageSync('address')
    var login = wx.getStorageSync('login')
    wx.request({
      url: `${app.globalData.API_URL}/address?id=` + login.mid,
      data: {},
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        // success
        console.log(res)
        var addname=res.data[0]
        var address = addname.province + addname.city + addname.town + addname.address;
        var radioItems ={ address: address, phone:addname.phone, name:addname.name }
        
        that.setData({
          radioItems: radioItems
        })

      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
    //获取订单
    wx.request({
      url: `${app.globalData.API_URL}` + '/order',
      data: {
        order_id: options.id
      },
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        // success
        console.log(res)
        that.setData({
          payorder: res.data
        })
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
   
    var login = wx.getStorageSync('login')
    wx.request({
      url: `${app.globalData.API_URL}/address?id=` + login.mid,
      data: {},
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        // success
        console.log(res)
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
  },
  pay: function () {
    uctooPay.generateOrder()
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