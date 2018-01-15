/**
 * app.js是小程序的脚本代码。我们可以在这个文件中监听并处理小程序的声明周期函数、声明全局变量
 * 调用MINA提供的丰富API
 */
App({
  onLaunch: function () {
    //调用API从本地缓存中获取数据
    // console.log('App Launch')
  },
  onShow(){
    // console.log('App Show')
  },
  // getUserInfo:function(cb){
  //   var that = this;
  //   if(this.globalData.userInfo){
  //     typeof cb == "function" && cb(this.globalData.userInfo)
  //   }else{
  //     //调用登录接口
  //     wx.login({
  //       success: function () {
  //         wx.getUserInfo({
  //           success: function (res) {
  //             that.globalData.userInfo = res.userInfo;
  //             typeof cb == "function" && cb(that.globalData.userInfo)
  //           }
  //         })
  //       }
  //     });
  //   }
  // },
  globalData:{
    userInfo:null
  },
  // 声明一个全局变量。在其他组件中可通过var app = getApp();app.APP_NAME来引用
  APP_NAME: '有调',
  // 声明一个全局变量。在其他组件中可通过var app = getApp();app.version来引用
  version:'0.0.1'
});
