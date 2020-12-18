//app.js

App({
  onLaunch: function(){
    this.getSystemInfo();
  },
  //获取用户信息
  getUserInfo:function(){
    var user_info = wx.getStorageSync('user_info');
    if(!user_info){
      wx.navigateTo({
        url : '/pages/login/login'
      });
      return ;
    }
    this.globalData.userInfo = user_info;
    return user_info;

  },
  //获取设备信息
  getSystemInfo : function(){
    if(this.globalData.systemInfo){
      console.log('获取系统信息');
      return this.globalData.systemInfo;
      // typeof cb == "function" && cb(this.globalData.systemInfo);
    }else{
        console.log('重新获取系统信息')
        wx.getSystemInfo({
          success : (res) => {
            console.log('info',res);
            this.globalData.systemInfo = res;
            return this.globalData.systemInfo;
            // typeof cb == "function" && cb(_this.globalData.systemInfo);
          }
        })
      }
  },
  //全局页内跳转动画
  globalPageAnimate : function(flag, cb){
    var animation = wx.createAnimation({
      duration: 500,
      timingFunction: 'ease'
    });
    switch (flag){
      case 'left':
        animation.translate3d(0,0,0).step();
        break;
      case 'right':
        animation.translate3d('100%',0,0).step();
    }

    typeof cb == 'function' && cb(animation);

  },
  /*
  * 自定义Loading加载
  * _this
  * curPageRequsetNumber 页面请求个数
  * */
  MLoading : function(_this, curPageRequsetNumber){
    var onRequsetnumber = this.onRequsetnumber = this.onRequsetnumber ? this.onRequsetnumber + 1 : 1;

    if(onRequsetnumber == curPageRequsetNumber){
      setTimeout(() =>{
        _this.setData({
          "loading.hidden" : true,
          "loading.isViewHidden" : false
        });
      },100);
      this.onRequsetnumber = 0;
    }
  },
  //检查用户登录
  checkUserLoginStatus : function(){
    if(this.globalData.userInfo == null){
      console.log('null')
      return false;
    }
  },
  globalData:{
    userInfo:null,
    systemInfo:null
  }
});