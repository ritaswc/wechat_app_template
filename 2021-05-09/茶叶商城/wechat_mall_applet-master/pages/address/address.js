const district = require('../../utils/address_data.js')

Page({
  data: {
    detailAddress: '',
    receiverName:'',
    receiverMobile:'',
    arrayProvince: [],
    arrayCity: [],
    arrayCounty: [],
    indexProvince: 0,
    indexCity: 0,
    indexCounty: 0,
  },

  bindChangeProvince: function(e) {
    var that = this
    var p = this.data.arrayProvince[e.detail.value]
    district.cities(p, function(arrayCity){
      that.setData({arrayCity: arrayCity, indexCity: 0})
      district.counties(p, arrayCity[0], function(arrayCounty){
        that.setData({arrayCounty: arrayCounty, indexCounty: 0})
      })
    })

    this.setData({indexProvince: e.detail.value})
    wx.setStorageSync('currentDistrict', [this.data.indexProvince, this.data.indexCity, this.data.indexCounty])
  },

  bindChangeCity: function(e) {
    var that = this
    var p = this.data.arrayProvince[this.data.indexProvince]
    var c = this.data.arrayCity[e.detail.value]
    district.counties(p, c, function(arrayCounty){
      that.setData({arrayCounty: arrayCounty, indexCounty: 0})
    })
    this.setData({indexCity: e.detail.value})
    wx.setStorageSync('currentDistrict', [this.data.indexProvince, this.data.indexCity, this.data.indexCounty])
  },

  bindChangeCounty: function(e) {
    this.setData({
      indexCounty: e.detail.value
    })
    wx.setStorageSync('currentDistrict', [this.data.indexProvince, this.data.indexCity, this.data.indexCounty])
  },

  formSubmit: function(e) {
    // this.setData({'detailAddress': e.detail.value.inputDetail})
    wx.setStorage({key:'detailAddress', data: e.detail.value.inputDetail.trim()})

    var receiverName = e.detail.value.inputName.trim()
    var receiverMobile = e.detail.value.inputMobile.trim()
    if (!(receiverName && receiverMobile)) {
      this.errorModal('收货人姓名和手机号不能为空')
      return
    }
    if (!receiverMobile.match(/^1[3-9][0-9]\d{8}$/)) {
      this.errorModal('手机号格式不正确，仅支持国内手机号码')
      return
    }
    wx.setStorage({key:'receiverName', data: receiverName})
    wx.setStorage({key:'receiverMobile', data: receiverMobile})
    wx.setStorageSync('currentDistrict', [this.data.indexProvince, this.data.indexCity, this.data.indexCounty])
    var pages = getCurrentPages()
    var cartPage = pages[pages.length - 2]
    cartPage.setData({refreshAddress: true})
    wx.navigateBack()
  },

  onLoad (params) {
    var that = this
    var currentDistrict = wx.getStorageSync('currentDistrict') || [1, 0, 0]
    var arrayProvince = district.provinces()
    district.cities(arrayProvince[currentDistrict[0]], function(arrayCity){
      that.setData({arrayCity: arrayCity})
      district.counties(arrayProvince[currentDistrict[0]], arrayCity[currentDistrict[1]], function(arrayCounty){
        that.setData({arrayCounty: arrayCounty})
      })
    })

    this.setData({
      indexProvince:  currentDistrict[0],
      indexCity:      currentDistrict[1],
      indexCounty:    currentDistrict[2],
      arrayProvince:  arrayProvince,
      detailAddress:  wx.getStorageSync('detailAddress'),
      receiverName:   wx.getStorageSync('receiverName'),
      receiverMobile: wx.getStorageSync('receiverMobile')
    })
  },

  errorModal: function(content) {
    wx.showModal({
      title: '出现错误',
      content: content
    })
  }
})
