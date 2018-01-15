const product = require('../../utils/product.js')

Page({
  data: {
    toastAddProduct: true,
    title: '',
    id: 0,
    quantity: 1,
    product: {}
  },

  onLoad (params) {
    var id = params.id
    var product = wx.getStorageSync('products').find(function(i){
      return i.id === id
    })

    this.setData({
      id: id,
      product: product,
      title: product.name
    })
  },

  onReady() {
    wx.setNavigationBarTitle({ title: this.data.title })
  },

  bindAddToCart (e) {
    var that = this
    var cartItems = wx.getStorageSync('cartItems') || []

    var exist = cartItems.find(function(ele){
      return ele.id === that.data.id
    })

    if (exist) {
      exist.quantity = parseInt(exist.quantity) + 1
    } else {
      cartItems.push({
        id: this.data.id,
        quantity: this.data.quantity,
        product: this.data.product
      })
    }
    this.setData({ toastAddProduct:false });
    wx.setStorage({
      key: 'cartItems',
      data: cartItems
    })
  },

  bindQuantityInput (e) {
    this.setData({'quantity': e.detail.value})
  },

  toastChange: function(){
    this.setData({ toastAddProduct:true });
  }
})
