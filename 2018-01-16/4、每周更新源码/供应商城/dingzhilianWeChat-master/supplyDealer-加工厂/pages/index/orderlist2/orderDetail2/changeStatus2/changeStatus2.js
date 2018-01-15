var app = getApp();
Page({
  onLoad: function (option) {
    var that = this;
    var state = option.state;
    var id = option.id;
    that.setData({
      state: state,
      id: id
    })
    if (state == 2) {
      that.setData({
        reasonShow: true,
        updateState: 6,
        stateName: [{ updateState: 6, name: '已取消' }]
      })
    } else if (state == 4) {
      that.setData({
        updateState: 5,
        stateName: [{ updateState: 5, name: '已入库' }]
      })
    }
  },
  data: {
    imageCtx: app.globalData.imageCtx,
    hidden: true,
    reasonShow: false,
    reason: '',
    index: 0
  },
  reason: function (e) {
    this.setData({
      reason: e.detail.value
    })
  },
  updateState: function () {
    var that = this;
    var params = {}, adminObj = app.globalData.adminObj, state = that.data.state;
    if (state == 2) {
      var reason = that.data.reason, reg = /^\s*|\s*/g;
      reason = reason.replace(reg, '');
      if (!reason) {
        app.warning('请输入原因');
        return;
      }
      params.reason = reason;
    }
    params.id = that.data.id;
    params.state = that.data.updateState;
    params.phone = adminObj.phone;
    params.password = app.globalData.password;
    params.sessionId = adminObj.sessionId;

    that.setData({
      hidden: false
    })
    wx.request({
      url: app.globalData.requestUrl + 'weixinMerchant/updateOrderState',
      data: params,
      success: function (res) {
        if (res.data.code == '0') {
          that.setData({
            hidden: true
          })

          var pages = getCurrentPages();
          var page2 = pages[1];  //第二个页面            
          wx.navigateBack({
            delta: 2, // 回退前 delta(默认为1) 页面
            success: function () {
              page2.setData({//直接调用上一个页面的setData()方法，把数据存到上一个页面中去
                state: that.data.updateState
              })
              page2.getOrders();
            }
          })
        } else {
          app.noLogin(res.data.msg);
        }
      },
      fail: function (res) {
        that.setData({
          hidden: true
        })
        app.warning("服务器无响应");
      }
    })
  },
  bindPickerChange: function (e) {
    var that = this, stateName = that.data.stateName, index = e.detail.value;
    console.log('picker发送选择改变，携带值为:' + index)
    that.setData({
      index: e.detail.value,
      updateState: stateName[index].updateState
    })
  }
})