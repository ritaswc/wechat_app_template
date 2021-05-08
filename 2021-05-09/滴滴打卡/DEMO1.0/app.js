const AV=require('./lib/av-weapp-min');
AV.init({ 
 appId: 'kbG5hYE54Tl5vpt2qDuGBWzQ-gzGzoHsz', 
 appKey: '3dTWK3Vg0K0Cq6AeJmbf2oTJ', 
});
App({
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
});