if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
      
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
      getApp().page.onLoad(this, options);

      var self = this;
      var user_conpon_id = options['user_coupon_id'] ? options['user_coupon_id']: 0;
      var coupon_id = options['coupon_id'] ? options['coupon_id']: 0;

      if(!user_conpon_id && !coupon_id) return;

      getApp().core.showLoading({
          title: "加载中",
      });
      getApp().request({
          url: getApp().api.coupon.coupon_detail,
          data: {
              user_conpon_id: user_conpon_id,
              coupon_id: coupon_id
          },
          success: function (res) {
              if (res.code == 0) {
                  self.setData({
                      list: res.data.list,
                  });
              }
          },
          complete: function () {
              getApp().core.hideLoading();
          }
      });

    },

    goodsList: function (e) {
        var self = this;
        var goods_id = e.currentTarget.dataset.goods_id;
        var id = e.currentTarget.dataset.id;
        var list = self.data.list;
        if (parseInt(list.id) === parseInt(id)) {
            // 指定商品的优惠券才能跳转
            if (list.appoint_type == 2 && list.goods.length > 0) {
                getApp().core.navigateTo({
                    url: '/pages/list/list?goods_id=' + goods_id
                })
            }
            return
        }
    },
    receive: function (e) {
        var self = this;
        var id = e.target.dataset.index;
        getApp().core.showLoading({
            mask: true,
        });
        if (!self.hideGetCoupon) {
            self.hideGetCoupon = function (e) {
                var url = e.currentTarget.dataset.url || false;
                self.setData({
                    get_coupon_list: [],
                });
                if (url) {
                    getApp().core.navigateTo({
                        url: url,
                    });
                }
            };
        }
        getApp().request({
            url: getApp().api.coupon.receive,
            data: { id: id },
            success: function (res) {
                if (res.code == 0) {
                    var list = self.data.list;
                    list['is_receive'] = 1;
                    self.setData({
                        list:list,
                        get_coupon_list: res.data.list,
                    });
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },

    closeCouponBox: function (e) {
        this.setData({
            get_coupon_list: ""
        });
    },
})