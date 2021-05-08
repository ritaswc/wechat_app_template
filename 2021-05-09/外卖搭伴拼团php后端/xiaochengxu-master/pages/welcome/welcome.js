// pages/welcome/welcome.js
Page({
  data:{
    animationData : {},
    animationImgData : {},
    welcomeStatus : ''
  },
  onLoad:function(options){
    var page = this;
    var animationImg = wx.createAnimation({
        duration: 400,
        timingFunction: 'ease-in-out'
    })
    animationImg.opacity(1).translateY(-50).step();
    page.setData({
      animationImgData : animationImg.export()
    })
    setTimeout(function(){
      // var animation = wx.createAnimation({
      //       duration: 400,
      //       timingFunction: 'ease-in-out'
      //   })
      // animation.opacity(0).step();
      // page.setData({
      //   animationData : animation.export(),
      //   welcomeStatus : 'hide'
      // })
      wx.switchTab({
        url: '/pages/index/index'
      })
    },2000)
  }
})