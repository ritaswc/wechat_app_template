//城市选择
var app = getApp();
Page({
  data: {
    shengArr: [],//省级数组
    shengId: [],//省级id数组
    shiArr: [],//城市数组
    shiId: [],//城市id数组
    quArr: [],//区数组
    shengIndex: 0,
    shiIndex: 0,
    quIndex: 0,
    mid: ''
  },
  formSubmit: function (e) {
    console.log(e)
    var addressOld = wx.getStorageSync('address')
    if (addressOld) {
      addressOld.push(e.detail.value)
    } else {
      var addressOld = [];
      addressOld.push(e.detail.value)
    }
    wx.setStorageSync('address', addressOld)
    wx.request({
      url: `${app.globalData.API}/address`,
      data: e.detail.value,
      method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
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

    wx.navigateBack({
      delta: 1, // 回退前 delta(默认为1) 页面
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
  onLoad: function (options) {
    // 生命周期函数--监听页面加载
    var that = this;
    //获取用户mid
    var mid = wx.getStorageSync('login').mid;
    that.setData({
      mid: mid
    })
    //获取省级城市
    wx.request({
      url: `${app.globalData.API}/api/district?level=1&limit=100`,
      data: {},
      method: 'GET',
      success: function (res) {
        console.log(res)
        var sArr = [];
        var sId = [];
        for (var i = 0; i < res.data.length; i++) {
          sArr.push(res.data[i].name)
          sId.push(res.data[i].id)
        }
        that.setData({
          shengArr: sArr,
          shengId: sId
        })

      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })

  },

  bindPickerChangeshengArr: function (e) {
    console.log('picker发送选择改变，携带值为', e)
    this.setData({
      shengIndex: e.detail.value
    })
    var that = this;
    wx.request({
      url: `${app.globalData.API}/api/district?level=2&upid=` + that.data.shengId[e.detail.value],
      data: {},
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        // success
        console.log(res)
        var hArr = [];
        var hId = [];
        for (var i = 0; i < res.data.length; i++) {
          hArr.push(res.data[i].name)
          hId.push(res.data[i].id)
        }
        that.setData({
          shiArr: hArr,
          shiId: hId
        })
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
  },
  bindPickerChangeshiArr: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      shiIndex: e.detail.value
    })
    var that = this;
    wx.request({
      url: `${app.globalData.API}/api/district?level=3&upid=` + that.data.shiId[e.detail.value],
      data: {},
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        var qArr = [];
        var qId = [];
        for (var i = 0; i < res.data.length; i++) {
          qArr.push(res.data[i].name)
          qId.push(res.data[i].id)
        }
        that.setData({
          quArr: qArr,
          quiId: qId
        })
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
  },
  bindPickerChangequArr: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      quIndex: e.detail.value
    })
  }

})