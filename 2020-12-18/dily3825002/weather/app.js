App( {
  onLaunch: function() {
    wx.login( {
      success: function( res ) {
        wx.getUserInfo( {
          success: function( res ) {
            console.log('res',res);
          }
        })
        console.log( res )
        if( res.code ) {
          //发起网络请求
          wx.request( {
            url: 'https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code',
            success: function( res ) {
              console.log( res );
            }
          })
        } else {
          console.log( '获取用户登录态失败！' + res.errMsg )
        }
      }
    });
  },
  onShow: function() {
    console.log( 'App Show' )
  },
  onHide: function() {
    console.log( 'App Hide' )
  }
})