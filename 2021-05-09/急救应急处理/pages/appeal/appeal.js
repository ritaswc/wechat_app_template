var bmap = require('../../libs/bmap-wx');
var wxMarkerData = [];
Page({
  data: {
    city: '',
    district: '',
    street: '',
    street_number: '',
  },

  onLoad: function () {
    var that = this;
    var BMap = new bmap.BMapWX({
      ak: 'L0Npf6qyYzrXxHSnwfGccjvBoKj0my8E'
    });
    var fail = function(data) {
      console.log(data)
    };
    var success = function(data) {
      console.warn(data);
      that.setData({
        city: data.originalData.result.addressComponent.city,
        district: data.originalData.result.addressComponent.district,
        street: `${data.originalData.result.addressComponent.street}${data.originalData.result.addressComponent.street_number}`,
      })
    }
    BMap.regeocoding({
      fail: fail,
      success: success,
    });
  },

  callPhone: function() {
    wx.makePhoneCall({
      phoneNumber: '120' //仅为示例，并非真实的电话号码
    });
  }

});
