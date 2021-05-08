//app.js
App({
  data:{},
  onLaunch: function () {
    var that = this;
    //用户登录
    function loginFn(){
      wx.login({
        success: function(loginRes){
          if (loginRes.code) {
            wx.getUserInfo({
              success: function (userinfoRes) {
                var userInfoStr=JSON.stringify(userinfoRes);
                wx.request({
                  url: 'https://shop.llzg.cn/weapp/login1.php',
                  data: {
                    code: loginRes.code,
                    userInfo:userInfoStr
                  },
                  header: {
                      'content-type': 'application/x-www-form-urlencoded'
                  },
                  method:'POST',
                  success: function(res) {
                    if(res.data.error==0){
                      wx.setStorage({
                        key:"session",
                        data:{
                          session:res.data.session_id,
                          userInfo:userinfoRes.userInfo,
                          expires:res.data.expires   //超时时间戳
                        },
                        success:function(){
                          console.log('写入缓存成功！');
                        },
                        fail:function(){
                          console.log('写入缓存失败！');
                        }
                      });
                      
                    }else{
                      console.log('刷新缓存失败！');  
                    }
                  },
                  fail:function(res){
                    console.log('刷新session失败！');
                  }
                });
              },
              fail:function(res){
                console.log('获取用户信息失败！' + res)
              }
            });
          }else {
            console.log('获取用户登录态失败！' + res.errMsg)
          }
        }
      });
    }
    //获取session
    wx.getStorage({
      key: "session",
      success: function(res){
        //获取本地session成功
        var session=res.data.session;
        //console.log("缓存:"+session);
        var url = 'https://shop.llzg.cn/weapp/checkLogin.php?act=checkLogin&'+"session_id="+session;
        //验证session
        wx.request({
          url: url,
          data:{},
          method: 'POST',
          header: {'content-type': 'application/x-www-form-urlencoded'},
          success: function(res){
            if(res.data == 0){
              loginFn();
            }else if(res.data == 1){
    
            }else{
              console.log("服务器故障!!")
            }
          }
        })
      },
      fail: function(res) {
        loginFn();
      }
    })
    
  }

})