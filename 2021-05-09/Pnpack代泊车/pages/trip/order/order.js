
Page({
  data: {
    orderstate: '',
    isdata: false,
    orderstateClass: '',
    returnCar: [],
    orderInfo: [],
    detailinfo: [],
    picker1Value: 0,
    orderPerson: [],
    o_id: '',
    o_ordercode: '',
    winWidth: 0,
    winHeight: 0,
    // tab切换  
    currentTab: 0,
    show: true,
    carTime: []
  },
  onLoad: function (options) {
    var that = this;
     if (options.o_orderstate == '进行中' || options.o_orderstate =='待评价') {
            options.o_orderstate = "jinxing"

          } if (options.o_orderstate == '已完成'||options.o_orderstate == '已完成(赔付)') {
            options.o_orderstate= "wancheng"
          } 
    that.setData({
      o_ordercode: options.o_ordercode,
      o_id: options.o_id,
      orderstate: options.o_orderstate
    })
  

    wx.getSystemInfo({
      success: function (res) {
        that.setData({
          winWidth: res.windowWidth,
          winHeight: res.windowHeight
        });
      }
    });
  },
  onShow: function (e) {
    var that = this;
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/order/order-info',
      data: {
        openid: wx.getStorageSync('openid'),
        order_id: that.data.o_id
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        console.log(res);
        that.setData({
          orderInfo: res.data.data,
          detailinfo: res.data.data.detailinfo
        })
      }
    });
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/order/return-car-period',
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        var returnCar = ['']
        for (var x in res.data.message) {
          returnCar.push(res.data.message[x].name)
        }
        console.log(returnCar + "...");
        that.setData({
          returnCar: returnCar
        })
        that.setData({
          carTime: res.data.message
        })
      }
    });
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/order/valet-info',
      data: {
        openid: wx.getStorageSync('openid'),
        order_id: that.data.o_id
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        console.log(res.data.data)
        that.setData({
          orderPerson: res.data.data
        })
      }
    });
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/order/order-info',
      data: {
        openid: wx.getStorageSync('openid'),
        order_id: that.data.o_id
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        console.log(res)
        // that.setData({
        //   orderPerson: res.data.data
        // })
      }
    });
  },
  swichNav: function (e) {

    var that = this;

    if (this.data.currentTab === e.target.dataset.current) {
      return false;
    } else {
      that.setData({
        currentTab: e.target.dataset.current
      })
    }
  },
  bindCall: function (e) {

    var phoneNumber = e.currentTarget.dataset.phone
    wx.makePhoneCall({
      phoneNumber: phoneNumber,

    })
  },
  bindIdentity: function () {
    wx.redirectTo({
      url: '/pages/validate/validate?id=' + this.data.o_id
    })
  },
  normalPickerBindchange: function (e) {
    var that = this
    var index = e.detail.value
    console.log(that.data.carTime)
    that.setData({
      picker1Value: e.detail.value
    })
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/order/apply-get-car',

      data: {
        openid: wx.getStorageSync('openid'),
        order_id: that.data.o_id,
        timeid: that.data.carTime[index].id
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        wx.showModal({
          title: '提示',
          content: '取车请求已经提交，请您保持电话通畅',
          confirmText: '返回首页',
          showCancel: false,
          success: function(res) {
            if (res.confirm) {
              wx.navigateBack({
                delta: 4
              })
            }
          }
        })

      }
    });
  },
  bindNotorder: function () {
    var that = this;
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/order/cancel-order',
      data: {
        openid: wx.getStorageSync('openid'),
        order_id: that.data.o_id
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        wx.showToast({
          title: '取消成功',
          duration: 2000,
          success: function () {
            wx.redirectTo({
              url: '../trip',
            })
          }
        })
      }
    });
  },
  bindCompensate: function () {
    var that = this;
    wx.redirectTo({
      url: '../compensate/compensate?o_id=' + that.data.o_id
    })
  },
  pnparkEnd: function () {
    var that = this;
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/order/complete-order',
      data: {
        openid: wx.getStorageSync('openid'),
        order_id: that.data.o_id
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        wx.showToast({
          title: '订单完成',
          duration: 2000,
          success: function () {
            wx.redirectTo({
              url: '/pages/trip/trip',
            })
          }
        })
      }
    });
  },
  goEvalute: function () {
    var that = this
    wx.redirectTo({
      url: '/pages/evaluate/evaluate?o_id=' + that.data.o_id
    })
  },
  onPullDownRefresh: function () {
    wx.redirectTo({
      url: '/pages/trip/order/order?o_id=' + this.data.o_id + '&o_ordercode=' + this.data.o_ordercode
    })
  }
})  