//app.js
App({
  onLaunch: function () {
    //调用API从本地缓存中获取数据
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    wx.setStorageSync('logs', logs)
    let res = wx.getSystemInfoSync()
    this.globalData.height = res.windowHeight
  },
  getUserInfo:function(cb){
    var that = this
    if(this.globalData.userInfo){
      typeof cb == "function" && cb(this.globalData.userInfo)
    }else{
      //调用登录接口
      wx.login({
        success: function () {
          wx.getUserInfo({
            success: function (res) {
              that.globalData.userInfo = res.userInfo
              typeof cb == "function" && cb(that.globalData.userInfo)
            }
          })
        }
      })
    }
  },
  globalData:{
    userInfo:null,
    height:0
  },
  //把对象转为符合页面参数传递的函数  
  // 类型为key=valuev&key=value
  objcToString:function(objc){
    let objcString = ''
    for(let key in objc){
       objcString = objcString.concat(key,"=",objc[key],"&")        
    }
    if(objcString.length > 0){
      return objcString.slice(0,objcString.length - 1)
    }else{
      return ""
    }
  }
})