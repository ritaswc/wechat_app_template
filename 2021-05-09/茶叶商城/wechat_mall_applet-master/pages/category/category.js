const product = require('../../utils/product.js')
var app = getApp()

Page({
  data: {
    title: '',
    items: null,
    accountType: '',
    categoryType: null,
    categoryTypeId: null
  },

  onLoad: function() {
  },

  onShow() {
    var that = this
    var cateType = app.globalData.currentCateType
    this.setData({categoryType: cateType.typeName, categoryTypeId: cateType.typeId})
    if (app.globalData.currentCustomer) {
      var accountType = app.globalData.currentCustomer.account_type
      that.setData({accountType: accountType})
    }

    product.getCategories(that.data.categoryTypeId, function(result) {
      var data = getApp().store.sync(result.data)
      that.setData({items: data})
      wx.setStorage({
        key: `cate_${that.data.categoryType}`,
        data: data
      })
    }, function(fail) {
      var key = `cate_${that.data.categoryType}`
      var data = wx.getStorage(key)
      wx.setData({items: data})
    })
  },

  onReady() {
    var title = '巴爷供销社 - ' + this.data.categoryType
    wx.setNavigationBarTitle({ title: title})
  },

  bindTapProduct: function(e) {
    var that = this

    wx.navigateTo({
      url: `../show_product/show_product?id=${e.currentTarget.dataset.id}&type=${this.data.categoryType}`
    })
  }
})
