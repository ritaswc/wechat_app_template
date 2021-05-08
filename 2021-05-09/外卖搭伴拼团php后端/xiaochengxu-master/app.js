//app.js

App({
  onLaunch: function () {
    //调用API从本地缓存中获取数据
    // var logs = wx.getStorageSync('logs') || []
    // logs.unshift(Date.now())
    // wx.setStorageSync('logs', logs)


    var that = this
    if(this.globalData.userInfo){
      typeof cb == "function" && cb(this.globalData.userInfo)
    }else{
      //调用登录接口
      wx.login({
        success: function (ret) {
          wx.getUserInfo({
            success: function (res) {
              wx.setStorageSync('auth','success');
              var $post = {
                code : ret.code,
                encryptedData : res.encryptedData,
                iv : res.iv
              }
              wx.request({    
                  url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Login/regSmallUser',    
                  data: {data:JSON.stringify($post)},
                  method: 'POST',
                  header: {
                      'content-type': 'application/x-www-form-urlencoded'
                  },
                  success: function(res){    console.log(res,3333)
                    wx.setStorageSync('unionId',res.data); 

                     wx.request({    
                        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Login/getUid',    
                        data: {data:JSON.stringify({'unionId':res.data})},
                        method: 'POST',
                        header: {
                            'content-type': 'application/x-www-form-urlencoded'
                        },
                        success: function(res){  
                          
                           wx.setStorageSync('uid',res.data.data); 
                          
                          // page.setData({userInfo:uid})

                          if(!res.data.data){
                            wx.showModal({
                              title: '您还未绑定手机号',
                              confirmText: '去绑定',
                              showCancel: false,
                              success: function(res) {//console.log(res)
                                if (res.confirm) {
                                  wx.navigateTo({
                                    url: '/pages/mine/login/login',
                                  })
                                }
                              }
                            })
                          }
                          
                        }    
                      });  
                    
                  }    
              });  
            }
          }) 
        }
      })
    }
  },
  getUserInfo:function(cb){
    var that = this
    if(this.globalData.userInfo){
      typeof cb == "function" && cb(this.globalData.userInfo)
    }else{
      //调用登录接口
      wx.login({
        success: function (ret) {
          wx.getUserInfo({
            success: function (res) {console.log(res)
              var $post = {
                code : ret.code,
                encryptedData : res.encryptedData,
                iv : res.iv
              }
              wx.request({    
                  url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Login/regSmallUser',    
                  data: {data:JSON.stringify($post)},
                  method: 'POST',
                  header: {
                      'content-type': 'application/x-www-form-urlencoded'
                  },
                  success: function(res){    //console.log(res.data)
                    
                     wx.setStorageSync('unionId',res.data); 

                     wx.request({    
                        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Login/getUid',    
                        data: {data:JSON.stringify({'unionId':res.data})},
                        method: 'POST',
                        header: {
                            'content-type': 'application/x-www-form-urlencoded'
                        },
                        success: function(res){  
                          
                           wx.setStorageSync('uid',res.data.data); 
                          
                          // page.setData({userInfo:uid})

                          if(!res.data.data){
                            wx.showModal({
                              title: '您还未绑定手机号',
                              confirmText: '去绑定',
                              showCancel: false,
                              success: function(res) {//console.log(res)
                                if (res.confirm) {
                                  wx.navigateTo({
                                    url: '/pages/mine/login/login',
                                  })
                                }
                              }
                            })
                          }
                          
                        }    
                      });  
                  }    
              });  
            }
          }) 
        }
      })
    }
  },
  globalData:{
    userInfo:null,
    appid: 'wx38002adc825edc81',
    appsecret : '9dc5b950c4ec8d8efffa5b7acb40702e'
  },
  applyNotice : function(){
    var that = this;
    if (wx.openSetting) {
      wx.openSetting({
        success: (res) => {
        
          console.log(res)

          if(!res.authSetting["scope.userInfo"]){
              console.log('auth error');
              that.applyNotice();
              return ;
          }else{
              wx.setStorageSync('auth','success');

              //调用应用实例的方法获取全局数据
              that.getUserInfo(function(userInfo){
                //更新数据
                console.log(userInfo)
              })  

              console.log('auth success');
              
          }
        }
      })
    } else {
      // 如果希望用户在最新版本的客户端上体验您的小程序，可以这样子提示
      wx.showModal({
        title: '提示',
        content: '当前微信版本过低，无法使用该功能，请升级到最新微信版本后重试。'
      })
    }
  },
  backHome : function(){
    wx.switchTab({
      url: '/pages/index/index'
    })
  }
})