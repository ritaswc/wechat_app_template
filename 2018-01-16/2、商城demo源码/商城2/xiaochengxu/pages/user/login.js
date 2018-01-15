//获取应用实例
var app = getApp()
Page({
  data: {
    motto: '欢迎登录',
    userInfo: {},
    userName:'',
    userPassword:'',
    id_token:'',
    responseData:'',
    boo:false
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../index/index'
    })
  },
  userNameInput:function(e){
    this.setData({
      userName: e.detail.value
    })
  },
  userNameClick:function(e){
    this.value="";
  },
  userPasswordInput:function(e){
    this.setData({
      userPassword: e.detail.value
    })
    console.log(e.detail.value)
  },
  logIn:function(){
    var that = this
    wx.request({
      url: 'http://localhost:8000/index/ajax_dict',
      data: {
        username: this.data.userName,
        password: this.data.userPassword,
      },
      method: 'GET',
      success: function (res) {
        that.setData({
          responseData:res.data.result[0].body
        });
        wx.setStorage({
            key:"responseData",
             data:that.data.responseData
        });
        try {
          wx.setStorageSync('id_token', res.data.id_token)
        } catch (e) {
          console.log('there is no id_token')
        }
      
        wx.navigateTo({
          url: '../components/welcome/welcome'
        })
        console.log(res.data);
      },
      fail: function (res) {
        console.log(res.data);
        console.log('is failed')
      }
    })
  },
   register:function(e){
      // consoloe.log();
    }
  

})