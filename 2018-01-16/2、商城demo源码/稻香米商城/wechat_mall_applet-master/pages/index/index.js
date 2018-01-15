const product = require('../../utils/product.js')

Page({
  data: {
    items: [],
    slides: [],
    navs: [{icon: "../../images/asset.png",       name: "资产"},
           {icon: "../../images/direct_sale.png", name: "直销"},
           {icon: "../../images/our_select.png",  name: "甄选"},
           {icon: "../../images/packing.png",     name: "包装"}],

    popularity_products: [],
    new_products: [],
    hot_products: [],
    promotions: []
  },

  bindShowProduct: function (e) {
    wx.navigateTo({
      url: `../show_product/show_product?id=${e.currentTarget.dataset.id}`
    })
  },

  catchTapCategory: function (e) {
    wx.navigateTo({
      url: `../category/category?type=${e.currentTarget.dataset.type}`
    })
  },

  onLoad: function() {
    var that = this

    product.getSlides(function(result) {
      var data = getApp().store.sync(result.data)
      that.setData({'slides': data})
      wx.setStorage({
        key:"indexSlides",
        data:data
      })
    })

    wx.getNetworkType({
      success: function(res) {
        var networkType = res.networkType // 返回网络类型2g，3g，4g，wifi
        if (networkType) {
          product.getProducts(function(result) {
            var data = getApp().store.sync(result.data)
            that.setData({
              items: data,
              popularity_products: data.filter(product => product.flag === '最热'),
              new_products:        data.filter(product => product.flag === '新品'),
              hot_products:        data.filter(product => product.flag === '火爆'),
            })
            wx.setStorageSync('products', data)
          })
        } else {
           cache = wx.getStorageSync('products')
           if (cache) {
             that.setData({'items': cache})
           } else {
             that.setData({'items': []})
           }
        }
      }
    })
  }
})
