//index.js
//获取应用实例
var app = getApp()
var util = require('../../utils/util.js')
Page({
  data: {
    myCity: []
  },


  onShow: function () {

    var res = wx.getStorageSync('myCity')

    this.setData({
      myCity: res
    })
  },
  addCityEvent: function (e) {
    wx.navigateTo({
      url: '../cityChooser/cityChooser'
    })
  },
  deleteCityEvent: function (e) {
    let city = e.currentTarget.id
    //从mycity删除
    console.log(e);
    let myCity = wx.getStorageSync('myCity')
    let res = util.remove(myCity, city);

    wx.setStorageSync('myCity', res);
    wx.showToast({
      title: "删除城市成功",
      icon: "sucess",

    })

    //删除缓存
    wx.removeStorage({
      key: city,
      success: function(res){
        wx.showToast({
          title: "删除城市缓存成功",
          icon: "sucess",

        })
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
    console.log("重新请求当前页面");
    //跳转页面

    this.onShow();
  }
})
