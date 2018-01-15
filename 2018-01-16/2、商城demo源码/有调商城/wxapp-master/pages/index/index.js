//index.js
//获取应用实例
const app = getApp()
Page({
  data: {
    motto: 'Hello World',
    userInfo: {},
    switchViewActive: {}
  },
  //事件处理函数
  // bindViewTap: function() {
  //   /**
  //    * 路由处理
  //    *
  //    *  1. navigationTo    保留当前页面，跳转到指定页面
  //    *  2. redirectTo      不保留当前页面，跳转到指定页面
  //    *  3. navigationBack  关闭当前页面，回退前一页面
  //    */
  //   console.log('点击了页面')
  //   wx.navigateTo({
  //     url: '../cepin/index'
  //   })
  // },
  onLoad: function () {
    // console.log('onLoad')
    // var that = this
  	// //调用应用实例的方法获取全局数据
    // app.getUserInfo(function(userInfo){
    //   //更新数据
    //   that.setData({
    //     userInfo:userInfo
    //   })
    //   that.update()
    // })
  }
  ,onReady: function(){
    // console.log('onReady');
  },
  onShow: function(){
    // console.log('onShow');
  },
  onHide: function(){
    // console.log('onHide');
  },
  onUnload(){
    // console.log('onUnload');
  }
})

// console.log(app.APP_NAME);

// console.log('当前有调小程序版本是：%s',app.version);
