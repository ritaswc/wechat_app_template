// app.js
App({
  d: {
    hostUrl: 'https://app.gemmy.so',
    hostImg: 'http://img.ynjmzb.net',
    hostVideo: 'http://zhubaotong-file.oss-cn-beijing.aliyuncs.com',
    userId: 0,
    appId:"",
    appKey:""
  },
    onLaunch: function () {
    //调用API从本地缓存中获取数据
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    wx.setStorageSync('logs', logs);
    //login
    this.getUserInfo();
  },
  getUserInfo:function(cb){
    var that = this
    if(this.globalData.userInfo){
      typeof cb == "function" && cb(this.globalData.userInfo)
    }else{
      //调用登录接口
      wx.login({
        success: function (res) {
          console.log(res);
          var code = res.code;
          //get wx user simple info
          wx.getUserInfo({
            success: function (res) {
              console.log(res);
              that.globalData.userInfo = res.userInfo
              typeof cb == "function" && cb(that.globalData.userInfo);
              //get user sessionKey
              //get sessionKey
              that.getUserSessionKey(code);
            }
          });
        }
      });
    }
  },

  getUserSessionKey:function(code){
    console.log('code：' + code);
    //用户的订单状态
    var that = this;
    wx.request({
      url: that.d.hostUrl + '/ztb/userZBT/GetSession',
      method:'post',
      data: {
        code: code
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        //--init data        
        var data = res.data;
        that.globalData.userInfo['sessionId'] = data.message;
        // console.log(that.globalData.userInfo);
        console.log(res.data);
        that.onLoginUser();
      },
    });
  },
  onLoginUser:function(){
    var that = this;
    var user = that.globalData.userInfo;

    wx.request({
      url: that.d.hostUrl + '/ztb/userZBT/Login',
      method:'post',
      data: {
        SessionId: user.sessionId,
        NickName: user.nickName,
        HeadUrl: user.avatarUrl,
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        //--init data        
        var data = res.data.data[0];
        that.globalData.userInfo['id'] = data.ID;
        that.globalData.userInfo['tel'] = data.PhoneNum;
        that.d.userId = data.ID;
        if(!that.d.userId){
          wx.showToast({
            title: '登录失败！',
            icon: 'success',
            duration: 200000
          });
        };

        //that.getOrBindTelPhone();
        
        console.log(that.globalData);
      },
    });
  },
  getOrBindTelPhone:function(returnUrl){
    var user = this.globalData.userInfo;
    if(!user.tel){
      wx.navigateTo({
        url: 'pages/binding/binding'
      });
    }
  },

 globalData:{
    API_URL:'https://www.weiyoho.com',
    API:'https://www.weiyoho.com',
    userInfo:null
  },

  onPullDownRefresh: function (){
    wx.stopPullDownRefresh();
  }

});





