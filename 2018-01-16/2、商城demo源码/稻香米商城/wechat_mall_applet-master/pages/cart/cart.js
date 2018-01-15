const district = require('../../utils/address_data.js')
const product = require('../../utils/product.js')

Page({
  data: {
    deleteModalHidden: true,
    wantToDeleteItem: '',
    address: null,
    cartItems: [],
    amount: 0
  },

  onLoad: function (params) {
  },

  onShow: function (params) {
    var cartItems = wx.getStorageSync("cartItems")
    this.setData({cartItems: cartItems || []})

    this.changeCartAmount()

    var detailAddress  = wx.getStorageSync('detailAddress')
    var receiverName   = wx.getStorageSync('receiverName')
    var receiverMobile = wx.getStorageSync('receiverMobile')
    var address = {detail: detailAddress, name: receiverName, mobile: receiverMobile}

    var districtIndex = wx.getStorageSync('currentDistrict') || [0,0,0]
    address.province = district.provinces()[districtIndex[0]]
    address.city     = district.cities(address.province)[districtIndex[1]]
    address.county   = district.counties(address.province, address.city)[districtIndex[2]]

    this.setData({address: address})
  },

  bindChangeQuantity: function (e) {
    var cartItems = this.data.cartItems
    var item = cartItems.find(function(ele){
      return ele.id === e.currentTarget.dataset.id
    })
    item.quantity = e.detail.value
    this.setData({ cartItems: cartItems })
    wx.setStorage({
      key: 'cartItems',
      data: cartItems
    })
    this.changeCartAmount()
  },

  // tap on item to delete cart item
  catchTapOnItem: function (e) {
    this.setData({
      deleteModalHidden: false,
      wantToDeleteItem: e.currentTarget.dataset.id
    })
  },

  deleteModalChange: function (e) {
    var that = this
    if (e.type === "confirm") {
      var cartItems = that.data.cartItems
      var index = cartItems.findIndex(function(ele){
        return ele.id === that.data.wantToDeleteItem
      })
      cartItems.splice(index, 1)
      this.setData({ cartItems: cartItems })
      wx.setStorage({
        key: 'cartItems',
        data: cartItems
      })
    }
    this.setData({
      deleteModalHidden: true
    })
    this.changeCartAmount()
  },

  bindBilling: function () {
    var cartItems = wx.getStorageSync('cartItems')
    if (cartItems) {
      var cartArray = cartItems.map(function(obj){
        var rObj = {};
        rObj['id'] = obj.id;
        rObj['quantity'] = obj.quantity;
        return rObj;
      });

      product.postBilling({
        items: cartArray,
        address: this.data.address
      }, function(result){

      })
    }
  },

  changeCartAmount: function () {
    var amount = 0
    this.data.cartItems.forEach(function(entry){
      amount += entry.quantity * entry.product.price
    })
    this.setData({amount: amount})
  },

  bindTapAddress () {
    wx.navigateTo({
      url: '../address/address'
    })
  }
})
