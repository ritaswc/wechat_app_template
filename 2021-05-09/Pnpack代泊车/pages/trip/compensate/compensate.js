Page({
  data: {
    o_id: 1,
    reaons: [],
    reason_ids: '',
  },
  onLoad: function (options) {

    var that = this;
    that.setData({
      o_id: options.o_id
    })

    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/order/get-apply-amends',
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        console.log(res)
        that.setData({
          reaons: res.data.data
        })
      }
    });

  },
  checkboxChange: function (e) {
    var that = this;

    that.setData({
      reason_ids: e.detail.value
    })
    console.log(that.data.reason_ids.join('^'));

  },
  bindSure: function () {
    var that = this;
    console.log(that.data.reason_ids.join('^'));
     wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/order/apply-amends',data: {
        openid: wx.getStorageSync('openid'),
        order_id: that.data.o_id,
        reason_ids:that.data.reason_ids.join('^'),
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        console.log(res)
        wx.navigateBack({
          delta: 3
        })
      }
    });
  }
})