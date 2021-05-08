//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    userInfo: {},
    chooseimagePaths: ''
  },
  //事件处理函数
  userlogo: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  getin: function() {
    wx.navigateTo({
      url: '../main/main'
    })
  },
   
  chooseimage: function() {
    var _this = this;
    wx.chooseImage({
      count: 1,
      sizeType: ['original','compressed'],
      sourceType: ['camera','album'],
      success: function(res) {
        let paths = res.tempFilePaths
        wx.uploadFile({
          url: 'https://www.pihuataotao.cn/applet/album/upload',
          filePath: paths[0],
          name: 'image',
          header: {
            "Content-Type": "multipart/form-data"
          }, 
          success: function (res) {
            wx.downloadFile({
              url: "https://www.pihuataotao.cn/output.jpg",
              success: function (res) {
                _this.setData({
                  chooseimagePaths: res.tempFilePath
                }),
                wx.navigateTo({
                  url: '../main/main?Paths='+res.tempFilePath
                })
              },
              fail: function (res) {
                // fail
              },
              complete: function (res) {
                // complete
                
                console.log('complete');
              }
            })
          }
        })
      }
    })
  },


  onLoad: function () {
    console.log('onLoad')
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
