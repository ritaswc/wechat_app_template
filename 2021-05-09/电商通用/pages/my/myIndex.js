var app = getApp()
Page({
  data: {
      imgUrls: [
      'http://img2.xyyzi.com/Upload/images/20161218/58564c6a071c8.jpg',
      'http://img2.xyyzi.com/Upload/images/20160828/57c25bafaa7b4.jpg',
      'http://img06.tooopen.com/images/20160818/tooopen_sy_175833047715.jpg',
      'http://img2.xyyzi.com/Upload/images/20160716/576c326c7723d.jpg'
    ],
      indicatorDots: true,
      autoplay: true,
      interval: 5000,
      duration: 500
  },
    onLoad: function () {
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
    })
  }
})