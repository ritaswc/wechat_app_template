var app = getApp();
Page({
  onLoad: function (option) {
    console.log(option)
    var that = this;
    var state = option.state;
    var id = option.id;
    that.setData({
      state: state,
      id: id
    })
    if (state == 1) {
      that.setData({
        reasonShow: true,
        updateState: 6,
        stateName: [{ updateState: 6, name: '已取消' }]
      })
    } else if (state == 2) {
      that.setData({
        expressShow: true,
        updateState: 4,
        stateName: [{ updateState: 4, name: '已发货' }, { updateState: 6, name: '已取消' }]
      })
    } else if (state == 3) {
      that.setData({
        updateState: 4,
        stateName: [{ updateState: 4, name: '已发货' }]
      })
    } else if (state == 4) {
      that.setData({
        updateState: 5,
        stateName: [{ updateState: 5, name: '已完成' }]
      })
    }

    if (state == 2 || state == 3) {//请求获取快递列表和订单已经存在的物流信息 
      var params = {}, adminObj = app.globalData.adminObj;
      params.id = id;
      params.phone = adminObj.phone;
      params.password = app.globalData.password;
      params.sessionId = adminObj.sessionId;

      wx.request({
        url: app.globalData.requestUrl + 'weixinMerchant/getExpress',
        data: params,
        success: function (res) {
          if (res.data.code == '0') {
            var express = res.data.mapResults.express;
            express.unshift({ 'code': 'renyikuaidi', 'name': '任意快递' });
            that.setData({
              'express_code': 'renyikuaidi', 'express_name': '任意快递',
              express: express
            })

            var order = res.data.mapResults.order;
            for (var i in express) {//获取订单物流信息判断选中
              var express_code = order.express_code;
              if (express_code == express[i].code) {
                that.setData({
                  'express_code': express_code, 'express_name': order.express_name,
                  expressIndex: i,
                })
                var express_no = order.express_no;
                if (express_no != '') {
                  that.setData({
                    express_no: express_no
                  })
                }
                break;
              }
            }
          } else {
            app.noLogin(res.data.msg);
          }
        },
        fail: function () {
          app.warning('服务器无响应');
        }
      })
    }
  },
  data: {
    imageCtx: app.globalData.imageCtx,
    hidden: true,
    reasonShow: false,
    expressShow: false,
    updateState: '',
    reason: '',
    express_no: '',
    express_code: '',
    express_name: '',
    index: 0,
    expressIndex: 0
  },
  reason: function (e) {
    this.setData({
      reason: e.detail.value
    })
  },
  expressInput: function (e) {
    this.setData({
      express_no: e.detail.value
    })
  },
  trim: function (e) {
    var express_no = e.detail.value, reg = /^\s*|\s*/g;
    express_no = express_no.replace(reg, '');
    this.setData({
      express_no: express_no
    })
  },
  updateState: function () {
    var that = this, reg = /^\s*|\s*/g;
    var params = {}, adminObj = app.globalData.adminObj;
    params.phone = adminObj.phone;
    params.sessionId = adminObj.sessionId;
    params.password = app.globalData.password;

    var state = that.data.state, updateState = that.data.updateState;
    if (state != 4) {
      if (updateState == 4) {
        params.express_no = that.data.express_no;
        params.express_code = that.data.express_code;
        params.express_name = that.data.express_name;
      } else if (updateState == 6) {
        var reason = that.data.reason;
        reason = reason.replace(reg, '');
        if (!reason) {
          app.warning('请输入原因');
          return;
        }
        params.reason = reason;
      }
    }
    params.id = that.data.id;
    params.state = updateState;

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
                state: updateState
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
    var that = this, state = that.data.state, stateName = that.data.stateName, index = e.detail.value;
    console.log('picker发送选择改变，携带值为:' + index)
    that.setData({
      index: index
    })
    if (state == 2) {
      var updateState = stateName[index].updateState;
      console.log('修改状态切换显示下面内容');
      if (updateState == 4) {
        that.setData({
          reasonShow: false,
          expressShow: true,
        })
        console.log('发货状态');
      } else if (updateState == 6) {
        that.setData({
          expressShow: false,
          reasonShow: true,
        })
        console.log('取消状态');
      }
      that.setData({
        updateState: updateState
      })
    }
  },
  bindPickerChange2: function (e) {
    var that = this, express = that.data.express, expressIndex = e.detail.value;
    console.log('picker发送选择改变，携带值为', expressIndex)
    this.setData({
      expressIndex: expressIndex,
      express_code: express[expressIndex].code,
      express_name: express[expressIndex].name
    })
  }
})