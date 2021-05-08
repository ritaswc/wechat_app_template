//index.js
//获取应用实例
var app = getApp()
Page({
    toPartner:function(){
        wx.navigateTo({
        url: '../partner/partner'
      })
    }
})
