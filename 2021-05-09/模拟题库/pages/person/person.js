//person.js
//获取应用实例
var app = getApp();
Page({
  data: {
		userData: {
			Nickname: '微信小程序',
			Username: 'WeApp',
			AvatarLarge: '../../image/head.jpg'
		},
    infos:[
      {
        content: '错题历史',
        url: "../errorlist/errorlist"
      },
      {
        content: '题目收藏',
        url: "../favorite/favorite"
      },
    ]
  },
  logOff: function(e) {
    wx.showModal({
      title:'注意',
      content:'确认退出登录？',
      success:function(res) {
        if (res.confirm) {
          wx.request({
            url: app.url.host + app.url.logOffUrl,
            method: 'GET',
            success: function(data){
            //清除token
              wx.removeStorageSync('Authorization');
              wx.removeStorageSync('User');
              wx.removeStorageSync('SubjectId');
              var toastDelay = 1000;
              wx.showToast({
                title: '退出登录成功',
                duration: toastDelay
              });              
              setTimeout(function(){
                wx.redirectTo({
                  url: '../login/login'
                });
              },toastDelay);            
              
            },
            complete: null
          });
        }
      }
    });
  }
})
