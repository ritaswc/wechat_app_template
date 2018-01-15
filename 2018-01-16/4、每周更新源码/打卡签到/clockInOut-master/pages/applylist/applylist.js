// pages/applylist/applylist.js

var Api = require('../../utils/api.js');

Page({
  data: {
    list: [],
    winWidth: '',
    winHeight: '',
  },
  onLoad: function (options) {
    wx.getSystemInfo({
      success: (res) => {
        this.setData(
          {
            token: wx.getStorageSync('token'),
          })
      }
    })
    this.getApplyList()
  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  },

  getApplyList: function () {
    wx.request({
      url: Api.applylist + this.data.token,
      data: {},
      method: 'GET',
      success: (res) => {
        console.log(res)
        this.setData({
          list: res.data.applyMember
        })
      }
    })
  },


  submitForm: function(event) {
    console.log(event.detail.formId)
 
    var formId = event.detail.formId
    var id = event.detail.target.dataset.id
    var applyType  = event.detail.target.dataset.applyType
    this.handleApply(formId, id, applyType, () => {
        this.getApplyList()
    })
  },

  handleApply: function(formId, id, validation, cb) {
    console.log('formId', formId)

    wx.request({
      url: Api.verifyApply + id + '?token=' + this.data.token,
      data: {
        validation: validation,
        formId: formId
      },
      method: 'POST',
      success: function (res) {
        // success
        if (res.statusCode == 201) {
          typeof cb === 'function' && cb()
        }
      }
    })
  }

})