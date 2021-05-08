//index.js
//获取应用实例
var app = getApp()
// Page({
//   data: {
//     motto: 'Hello World',
//     userInfo: {}
//   },
//   //事件处理函数
//   bindViewTap: function() {
//     wx.navigateTo({
//       url: '../logs/logs'
//     })
//   },
//   onLoad: function () {
//     console.log('onLoad')
//     var that = this
//     //调用应用实例的方法获取全局数据
//     app.getUserInfo(function(userInfo){
//       //更新数据
//       that.setData({
//         userInfo:userInfo
//       })
//     })
//   }
// })


Page({
  data: {
    imgMsg: [],
    trunkLabelList:[],
    banner:[],
    indicatorDots: true,
    autoplay: true,
    interval: 5000,
    duration: 1000
  },
  onLoad: function () {
    var that = this;
    wx.request({
      method:'POST',
      url: "https://test.jlibom.com/home/queryLabel",
      data: {
         labelId:'00001',
         page:1,
         pageSize:10
      },
      success: function (result) {
        console.log(result.data.data.trunkLabelList);
        that.setData({
            banner:result.data.data.titleLabelList,
            trunkLabelList:result.data.data.trunkLabelList
        })
      }
    });
  }
})


