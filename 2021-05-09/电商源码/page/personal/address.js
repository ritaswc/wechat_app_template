var app     = getApp();
var common  = require('../../util/util.js');
var address = {
  "code": 200,
  "requestId": "xxx",
  "data": [
    {
      recipient: "小王",
      telephone: 12233445566,
      province: "北京",
      city: "北京市",
      area: "海淀区",
      address: "知春路",
      isDefault: 0
    },
    {
      recipient: "小li",
      telephone: 12233445566,
      province: "北京",
      city: "北京市",
      area: "海淀区",
      address: "知春路丙",
      isDefault: 1
    },
    {
      recipient: "小zh",
      telephone: 12233445566,
      province: "北京",
      city: "北京市",
      area: "海淀区",
      address: "知春路丙",
      isDefault: 0
    }
  ],
  "success": true,
  "message": "success"
}
Page({
  data: {},
  onLoad: function() {

  },
  onShow: function(){
    var self = this,
        data = address.data;
    self.setData({
      address: data
    })
  },
  setDefault: function(e) {
    var self    = this,
        bindVal = e.currentTarget.dataset.value.index;
  },
  delAddr: function(e) {
    var id = e.currentTarget.dataset.id;
  },
  editAddr: function(e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "editAddr?id=" + id,
      success: function(res) {
      },
    })
  },

  addAddr: function() {
    wx.navigateTo({
      url: "editAddr",
      success: function(res) {
      },
    })
  }

})