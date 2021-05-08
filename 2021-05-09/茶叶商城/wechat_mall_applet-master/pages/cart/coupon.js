var app = getApp()

Page({
  data: {
    coupons: []
  },

  onShow: function() {
  },

  onLoad: function(params) {
    this.setData({
      product_ids: params['product_ids'],
      products_order_quantities: params['products_order_quantities']
    })
    this.getCoupons()
  },

  bindUseCoupon: function(e) {
    var pages = getCurrentPages()
    var cartPage = pages[pages.length - 2]
    var coupon = this.data.coupons.find(function(ele){
      return ele.id === e.currentTarget.dataset.id
    })
    cartPage.setData({coupon: coupon})
    wx.navigateBack()
  },

  getCoupons: function() {
    var that = this
    var data = {
      product_ids: this.data.product_ids,
      products_order_quantities: this.data.products_order_quantities
    }
    app.authRequest({
      url: `${app.globalData.API_URL}/coupons/list`,
      data: data,
      method: 'GET',
      success: function(res){
        var data = app.store.sync(res.data)
        that.setData({coupons: data})
      }
    })
  }
})
