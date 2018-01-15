//app.js
App({
  onLaunch: function () {
    this.getUserInfo()
    //调用API从本地缓存中获取数据
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    wx.setStorageSync('logs', logs)
  },
  getUserInfo:function(cb){
    var that = this
    if(this.globalData.userInfo){
      typeof cb == "function" && cb(this.globalData.userInfo)
    }else{
      //调用登录接口
      wx.login({
        success: function (res) {
          console.log(res.code);
          console.log('index');
          if(res.code){
            wx.request({
              url: 'https://www.520hhy.cn/m.php?g=api&m=wxshop&a=send_code',
              // url:'https://www.520hhy.cn/m.php?g=api&m=wxshop&a=testlogin',
              data: {
                code: res.code,
                sess_id:wx.getStorageSync(
                 'sess_id'
                )
              },
              success: function(res){
                // success
                console.log(res)
                  wx.setStorageSync("openid", res.data.openid);
                  wx.setStorageSync('sess_id', res.data.sess_id);
                  wx.getUserInfo({
                  success: function (res) {
                    that.globalData.userInfo = res.userInfo
                    typeof cb == "function" && cb(that.globalData.userInfo)
                  }
                })
              },
              fail: function() {
                // fail
              },
              complete: function() {
                // complete
              }
            })
          }else{
            console.log('获取用户登录态失败！' + res.errMsg)
          }
        }
      })
    }
  },
  globalData:{
    userInfo:null,
    requestId:"0",//商品ID
    serverUrl:'https://www.520hhy.cn/',
    // serverUrl:'https://www.ahzczh.com/',
    pic_index:0,//预览大图当前图片的index
    adrKey:1,
    edit_type:"",//编辑的类型：修改或增加新的
    choose_address:{},//选择的地址作为默认地址
    old_address:{},//要编辑的旧地址
    order_id:"",//订单列表跳转时的订单id
    order_k:false,//是否是处理未完成的订单

  },  
})