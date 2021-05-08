if (typeof wx === 'undefined') var wx = getApp().core;
var is_no_more = false;
var is_loading = false;
var p = 2;
Page({

  /**
   * 页面的初始数据
   */
    data: {
        hide: 1,
        qrcode: ""
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) { getApp().page.onLoad(this, options);
      var self = this;
      is_no_more = false;
      is_loading = false;
      p = 2;
      self.loadOrderList(options.status || -1);
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function (options) { getApp().page.onReady(this);
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function (options) { getApp().page.onShow(this);
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function (options) { getApp().page.onHide(this);
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function (options) { getApp().page.onUnload(this);
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function (options) { getApp().page.onPullDownRefresh(this);
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function (options) { getApp().page.onReachBottom(this);
      var self = this;
      if (is_loading || is_no_more)
          return;
      is_loading = true;
      getApp().request({
          url: getApp().api.book.order_list,
          data: {
              status: self.data.status,
              page: p,
          },
          success: function (res) {
              if (res.code == 0) {
                  var order_list = self.data.order_list.concat(res.data.list);
                  self.setData({
                      order_list: order_list,
                      pay_type_list: res.data.pay_type_list
                  });
                  if (res.data.list.length == 0) {
                      is_no_more = true;
                  }
              }
              p++;
          },
          complete: function () {
              is_loading = false;
          }
      });
  },

    /**
     * 初次加载数据
     */
    loadOrderList: function (status) {
        if (status == undefined)
            status = -1;
        var self = this;
        self.setData({
            status: status,
        });
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.book.order_list,
            data: {
                status: self.data.status,

            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        order_list: res.data.list,
                        pay_type_list: res.data.pay_type_list
                    });
                }
                self.setData({
                    show_no_data_tip: (self.data.order_list.length == 0),
                });
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },

    /**
     * 点击取消
     */
    orderCancel:function(e)
    {
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        var id = e.currentTarget.dataset.id;
        getApp().request({
            url: getApp().api.book.order_cancel,
            data: {
                id: id,
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.redirectTo({
                        url: '/pages/book/order/order?status=0'
                    })
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },
    /**
     * 接口已废弃，不应再使用
     * 订单列表点击支付
     */
    GoToPay(e){
        getApp().core.showLoading({
            title: "正在提交",
            mask: true,
        });
        getApp().request({
            url: getApp().api.book.order_pay,
            data: {
                id: e.currentTarget.dataset.id,
            },
            complete: function () {
                getApp().core.hideLoading();
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.requestPayment({
                        _res: res,
                        timeStamp: res.data.timeStamp,
                        nonceStr: res.data.nonceStr,
                        package: res.data.package,
                        signType: res.data.signType,
                        paySign: res.data.paySign,
                        success: function (e) {
                        },
                        fail: function (e) {
                        },
                        complete: function (e) {

                            if (e.errMsg == "requestPayment:fail" || e.errMsg == "requestPayment:fail cancel") {//支付失败转到待支付订单列表
                                getApp().core.showModal({
                                    title: "提示",
                                    content: "订单尚未支付",
                                    showCancel: false,
                                    confirmText: "确认",
                                    success: function (res) {
                                        if (res.confirm) {
                                            getApp().core.redirectTo({
                                                url: "/pages/book/order/order?status=0",
                                            });
                                        }
                                    }
                                });
                                return;
                            }
                            getApp().core.redirectTo({
                                url: "/pages/book/order/order?status=1",
                            });
                        },
                    });
                }
                if (res.code == 1) {
                    getApp().core.showToast({
                        title: res.msg,
                        image: "/images/icon-warning.png",
                    });
                }
            }
        });
    },
    /**
     * 前往详情
     */
    goToDetails:function(e){
        getApp().core.navigateTo({
            url: '/pages/book/order/details?oid=' + e.currentTarget.dataset.id,
        });
    },
    /**
     * 核销码
     */
    orderQrcode: function (e) {
        var self = this;
        var index = e.target.dataset.index;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        if (self.data.order_list[index].offline_qrcode) {
            self.setData({
                hide: 0,
                qrcode: self.data.order_list[index].offline_qrcode
            });
            getApp().core.hideLoading();
        } else {
            getApp().request({
                url: getApp().api.book.get_qrcode,
                data: {
                    order_no: self.data.order_list[index].order_no
                },
                success: function (res) {
                    if (res.code == 0) {
                        self.setData({
                            hide: 0,
                            qrcode: res.data.url
                        });
                    } else {
                        getApp().core.showModal({
                            title: '提示',
                            content: res.msg,
                        })
                    }
                },
                complete: function () {
                    getApp().core.hideLoading();
                }
            });
        }
    },
    hide: function (e) {
        this.setData({
            hide: 1
        });
    },
    /**
     * 申请退款
     */
    applyRefund:function(e)
    {
        var self = this;
        var id = e.target.dataset.id;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.book.apply_refund,
            data: {
                order_id: id
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.showModal({
                        title: '提示',
                        content: '申请退款成功',
                        showCancel:false,
                        success: function (res) {
                            if (res.confirm) {
                                getApp().core.redirectTo({
                                    url: "/pages/book/order/order?status=3",
                                });
                            }
                        }
                    })
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                    })
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },
    /**
     * 前往评价
     */
    comment:function(e){
        getApp().core.navigateTo({
            url: '/pages/book/order-comment/order-comment?id='+e.target.dataset.id,
            success: function(res) {},
            fail: function(res) {},
            complete: function(res) {},
        })
    }
})