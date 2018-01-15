/**
 * app.js是小程序的脚本代码。我们可以在这个文件中监听并处理小程序的声明周期函数、声明全局变量
 * 调用MINA提供的丰富API
 */
// import './utils/polyfill'
App({
  // onLaunch(){ }
  // onLaunch() {
    // console.log('onLaunch')
    //调用API从本地缓存中获取数据
    // const self = this
    // console.log(self)
    // self.aaa = '这是挂到app上的数据' // 这个同步挂载的数据在其他文件中是可以访问的
    // wx.request({
    //   url: 'http://s.diaox2.com/view/app/gift_supply.php',
    //   header: {
    //       'Content-Type': 'application/json'
    //   },
    //   /**
    //    * 异步往app上挂载数据，在其他文件中调用
    //    * app并不会得到挂载的数据
    //    * 所以取到的数据不挂载到app上了而是存在本地
    //    */
    //   success(res) {
    //     self.gifts = res
    //     // console.log(res)
    //     // 把数据存入本地
    //     // wx.setStorageSync('key','value')
    //   }
    // })
  // },
  // onShow(){
    // console.log('App Show')
  // },
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
  // globalData:{
  //   userInfo:null
  // },
  // 声明一个全局变量。在其他组件中可通过var app = getApp();app.APP_NAME来引用
  // APP_NAME: '有调',
  // 声明一个全局变量。在其他组件中可通过var app = getApp();app.version来引用
  // version:'0.0.1'
});
