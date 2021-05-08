// pages/address/user-address/user-address.js
var app = getApp()
Page({
  data: {
    address: [],
    radioindex: '',
   
  },
  onLoad: function (options) {

    var that = this
    // 页面初始化 options为页面跳转所带来的参数
    var address = wx.getStorageSync('address')
    var radioindex = wx.getStorageSync('radioindex')
    var that = this
    that.setData({
      address: address,
      radioindex: radioindex
    })
    if (!address) {
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
        var addname = res.data[0]
        var address = that.data.address
        if (address == '') {
          var address = []
        }
        address.push(addname)
        that.setData({
          address: address
        })

      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
    }
    
  },
  radioChange: function (e) {
    console.log(e)
    var that = this
    var login = wx.getStorageSync('login')
    console.log(that.data.address[e.detail.value])
    wx.setStorageSync('radioindex', e.detail.value)
    wx.request({
      url: `${app.globalData.API}/address`,
      data: that.data.address[e.detail.value],
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
    this.onLoad()
  },
  onReady: function () {
    // 页面渲染完成
  },
  delAddress: function (e) {
    console.log(e.currentTarget.dataset.id);
    var that = this;
    var cardId = e.currentTarget.dataset.cardId;
    wx.showModal({
      title: '提示',
      content: '你确认移除吗',
      success: function(res) {
        res.confirm && wx.request({
          url: app.d.hostUrl + '/ztb/shopCartZBT/DeleteShopCart',
          method:'post',
          data: {
            cartID: cardId,
          },
          header: {
            'Content-Type':  'application/x-www-form-urlencoded'
          },
          success: function (res) {
            //--init data
            var data = res.data;
            console.log(data);
            //todo
            if(data.result == 'ok'){
              //that.data.productData.length =0;
              that.loadProductData();
            }
          },
        });
      }
    });

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