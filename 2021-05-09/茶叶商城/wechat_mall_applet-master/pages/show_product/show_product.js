const productUtil = require('../../utils/product.js')
var app = getApp()
Page({
  data: {
    title: '',
    id: 0,
    quantity: 1,
    product: {},
    windowWidth: 375,
    windowHeight: 667,
    pixelRatio: 2,
    accountType: '',
    from_share: false
  },

  onShareAppMessage: function () {
    return {
      title: this.data.product.name,
      desc: "巴爷供销社 － 高品质购物",
      path: `pages/show_product/show_product?id=${this.data.product.id}&share=1`
    }
  },

  onLoad (params) {
    var that = this
    var id = params.id

    try {
      var res = wx.getSystemInfoSync()
      this.setData({
        windowWidth:  res.windowWidth,
        windowHeight: res.windowHeight,
        pixelRatio:   res.pixelRatio
      })
      app.globalData.windowWidth  = res.windowWidth
      app.globalData.windowHeight = res.windowHeight
      app.globalData.pixelRatio   = res.pixelRatio
    } catch (e) {
    }

    if (params.share) {
      productUtil.getProduct(id, function(result){
        var data = app.store.sync(result.data)
        that.setData({
          from_share: true,
          id: data.id,
          product: data,
          title: data.name
        })
      })
    } else {
      var allProducts
      if (params.type) {
        allProducts = wx.getStorageSync(`cate_${params.type}`)
      } else {
        allProducts = wx.getStorageSync('products')
      }
      var id = params.id
      var product = allProducts.filter(function(i){
        return i.id === id
      })[0]

      this.setData({
        from_share: false,
        id: id,
        product: product,
        title: product.name
      })
    }
  },

  onShow() {
    if (app.globalData.currentCustomer) {
      var accountType = app.globalData.currentCustomer.account_type
      this.setData({accountType: accountType})
    }
  },

  onReady() {
    wx.setNavigationBarTitle({ title: this.data.title })
  },

  bindAddToCart (e) {
    var that = this

    //管到屏蔽
    if (!app.globalData.featureManager.enableGuanDao) {
      if (this.data.product && (parseInt(this.data.product['category-id']) === 18)) {
        wx.showModal({
          title: '管到商品暂未开放',
          content: '目前无法在小程序上购买管到商品，如有需要，可以在巴爷微信商城上进行购买。',
          showCancel: false,
          success: function(res) {}
        })
        return
      }
    }

    var cartItems = wx.getStorageSync('cartItems') || []

    var exist = cartItems.filter(function(ele){
      return ele.id === that.data.id
    })[0]

    if (exist) {
      exist.quantity = parseInt(exist.quantity) + 1
    } else {
      cartItems.push({
        id: this.data.id,
        quantity: 1,
        product: this.data.product
      })
    }
    wx.showToast({
      title: '成功加入',
      icon: 'success',
      duration: 1000
    })
    wx.setStorage({
      key: 'cartItems',
      data: cartItems
    })

    if (this.data.from_share) {
      wx.switchTab({
        url: '../cart/cart'
      })
    }
  }
})
