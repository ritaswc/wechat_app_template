// pages/index/index.js
Page({
  data:{},
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
  },
//   const ImgLoader = require('../../template/img-loader/img-loader.js');
//   this.imgLoader = new ImgLoader(this);
//   this.imgLoader.load(imgUrlOriginal, (err, data) => {
//     console.log('图片加载完成', err, data.src, data.width, data.height)
// });

  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  goMainPage: function() {
    wx.redirectTo({
      url: '../main/main'
    });
  }
})