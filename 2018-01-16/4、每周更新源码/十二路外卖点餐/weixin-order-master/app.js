//app.js
App({
  onLaunch: function () {
    //调用API从本地缓存中获取数据
    var that=this;  
    that.getUserInfo(function(userInfo){ 
         that.globalData.userInfo=userInfo
         //console.log(that.globalData.userInfo);
    }); 
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
    userInfo:null
  }
})