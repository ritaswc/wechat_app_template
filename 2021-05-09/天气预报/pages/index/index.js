//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    userInfo: {
      avatar: '../../src/img/menu-img.jpg',
      name: "康兵奎",
      post: "前端开发工程师",
      desc: "专注于前端的专职工程师，专注发现和学习前端最新的知识和框架，不断提升自己，扩充自己的知识储备！"
    }
  },
  //事件处理函数
  weatherSearch: () => {
    wx.navigateTo({
      url: '../weather/weather'
    });
  },
  calc: () => {
    wx.navigateTo({
      url: '../xiaomi/xiaomi'
    });
  },
  tapTo2048:()=>{
    wx.navigateTo({
      url: '../2048/2048'
    });
  },
  onLoad: function () {

  }
})
