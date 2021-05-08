const district = require('../../utils/address_data.js')
const order = require('../../utils/order.js')
const pay = require('../../utils/pay.js')
var app = getApp()

Page({
  data: {
    refreshAddress: true,
    wantToDeleteItem: '',
    address: null,
    cartItems: [],
    amount: 0,
    accountType: '',
    coupon: null,
    address: {}
  },

  onLoad: function (params) {
  },

  onShow: function (params) {
    var that = this
    // 判断要显示什么类型的价格
    if (app.globalData.currentCustomer) {
      var accountType = app.globalData.currentCustomer.account_type
      this.setData({accountType: accountType})
    }

    var cartItems = wx.getStorageSync("cartItems")
    this.setData({cartItems: cartItems || []})

    this.changeCartAmount()

    if (this.data.refreshAddress) {
      var detailAddress  = wx.getStorageSync('detailAddress')
      var receiverName   = wx.getStorageSync('receiverName')
      var receiverMobile = wx.getStorageSync('receiverMobile')
      var address = {detail_address: detailAddress, customer_name: receiverName, customer_mobile: receiverMobile}

      var districtIndex = wx.getStorageSync('currentDistrict') || [1,0,0]
      address.province = district.provinces()[districtIndex[0]]
      that.setData({address: address, refreshAddress: false})
      district.cities(address.province, function(arrayCity){
        address.city = arrayCity[districtIndex[1]]
        that.setData({address: address})
        district.counties(address.province, address.city, function(arrayCounty){
          address.county = arrayCounty[districtIndex[2]]
          that.setData({address: address})
        })
      })
    }
  },

  bindSelectCoupon: function() {
    var product_ids = this.data.cartItems.map(function(ele){return ele.id})
    var products_order_quantities = this.data.cartItems.map(function(ele){return ele.quantity})
    wx.navigateTo({
      url: `coupon?product_ids=${product_ids}&products_order_quantities=${products_order_quantities}`
    })
  },

  bindChangeQuantity: function (e) {
    if (e.detail.value === "") { return "" }

    var cartItems = this.data.cartItems
    var item = cartItems.filter(function(ele){
      return ele.id === e.currentTarget.dataset.id
    })[0]
    if (e.detail.value >= 1) {
      item.quantity = parseInt(e.detail.value)
    } else {
      item.quantity = 1
    }
    this.setData({ cartItems: cartItems })
    wx.setStorage({
      key: 'cartItems',
      data: cartItems
    })
    this.changeCartAmount()
    return item.quantity.toString()
  },

  // tap on item to delete cart item
  catchTapOnItem: function (e) {
    var that = this
    this.setData({
      wantToDeleteItem: e.currentTarget.dataset.id
    })

    wx.showModal({
      title: '删除商品',
      content: '是否要删除这件商品？',
      confirmText: '删除',
      cancelText: '别删',
      success: function(res) {
        if (res.confirm) {
          var cartItems = that.data.cartItems
          var index = cartItems.findIndex(function(ele){
            return ele.id === that.data.wantToDeleteItem
          })
          cartItems.splice(index, 1)
          that.setData({ cartItems: cartItems })
          wx.setStorage({
            key: 'cartItems',
            data: cartItems
          })
          that.changeCartAmount()
        }
      }
    })
  },

  bindBilling: function () {
    wx.showToast({
      title: '购买中...',
      icon: 'loading',
      duration: 5000
    })

    var that = this
    if (!this.addressValid()) {
      wx.hideToast()
      return
    }
    var cartItems = wx.getStorageSync('cartItems')
    if (!cartItems || cartItems.length === 0) {
      wx.hideToast()
      wx.showModal({
        title: '未选购商品',
        content: '您需要将商品加入购物车后才能支付',
        showCancel: false,
        success: function(res) {}
      })
      return
    }

    var order_items_attributes = cartItems.map(function(obj){
      var rObj = {};
      rObj['product_uid'] = obj.product.uid
      rObj['quantity'] = parseInt(obj.quantity)
      rObj['shippment_type'] = '包邮'
      // rObj['external_content'] = ""
      return rObj
    })

    var params = JSON.parse(JSON.stringify(this.data.address))
    params['order_from'] = 'from_applet'
    params['order_items'] = order_items_attributes
    if (this.data.coupon) {
      params['coupon_code'] = this.data.coupon.code
    }

    order.postBilling(params, function(result){
      if (parseInt(result.statusCode) === 403) {
        wx.hideToast()
        if (parseInt(result.data.code) === 4001) {
          that.setData({coupon: ''})
        }
        wx.showModal({
          title: '出错',
          content: result.data.msg,
          showCancel: false,
          success: function(res) {}
        })
        return
      }
      wx.hideToast()

      pay.pay(result.data.hash, function(){
        wx.removeStorage({
          key: 'cartItems',
          success: function(res) {
            wx.showModal({
              title: '提示',
              content: '你已成功购买，如需查看订单，可下载 ‘巴爷供销社’ APP',
              showCancel: false,
              success: function(res) {
                if (res.confirm) {
                  that.setData({
                    cartItems: [],
                    coupon: ''
                  })
                  that.changeCartAmount()
                }
              }
            })
          }
        })
      })
    })
  },

  addressValid: function() {
    var address = this.data.address
    var valid = address && address.detail_address && address.customer_name && address.customer_mobile
    if (!valid) {
      wx.showModal({
        title: '提示',
        content: '请填写收货地址',
        showCancel: false,
        success: function(res) {}
      })
    }
    return valid
  },

  changeCartAmount: function () {
    var amount = 0
    if (this.data.accountType === '巴爷') {
      this.data.cartItems.forEach(function(entry){
        amount += entry.quantity * entry.product['baye-price']
      })
    } else {
      this.data.cartItems.forEach(function(entry){
        amount += entry.quantity * entry.product['member-price']
      })
    }

    this.setData({amount: amount})
  },

  addQuantity: function(e) {
    this.changeCartItemQuantity('+', e)
  },

  minusQuantity: function(e) {
    this.changeCartItemQuantity('-', e)
  },

  changeCartItemQuantity: function(op, e) {
    var cartItems = this.data.cartItems
    var item = cartItems.filter(function(ele){
      return ele.id === e.currentTarget.dataset.id
    })[0]
    if (op === '-' && item.quantity > 1) {
      item.quantity -= 1
    } else if (op === '+') {
      item.quantity += 1
    }

    this.setData({ cartItems: cartItems })
    wx.setStorage({
      key: 'cartItems',
      data: cartItems
    })
    this.changeCartAmount()
  },

  bindTapAddress () {
    wx.navigateTo({
      url: '../address/address'
    })
  }
})
