Page({
  data:{
    logintopheight:0,//上面图片的高
    name_focus:false,
    pass_focus:false,
    user_name:null,
    user_pass:null,
    send_time:60,
  },
  onLoad:function(options){
    // 生命周期函数--监听页面加载
    this.render_control();
  },
  onReady:function(){
    // 生命周期函数--监听页面初次渲染完成
  },
  onShow:function(){
    // 生命周期函数--监听页面显示
  },
  onHide:function(){
    // 生命周期函数--监听页面隐藏
  },
  onUnload:function(){
    // 生命周期函数--监听页面卸载
  },
  onPullDownRefresh: function() {
    // 页面相关事件处理函数--监听用户下拉动作
  },
  onReachBottom: function() {
    // 页面上拉触底事件的处理函数
  },
  // 渲染控制
  render_control:function(){
    
  },
  image_load_fin:function(image){
    var app = getApp();
    var loginimageheight = app.globalData.systemInfo.windowWidth / image.detail.width * image.detail.height;
    this.setData({
      logintopheight : loginimageheight
    })
  },
  user_name_input_focus:function(input){
    var content = input.detail.value;
    if(content.length == 11){
      this.setData({
        pass_focus:true
      })
    }
    this.setData({
      user_name:input.detail.value,
    })
  },
  pass_word_input_focus:function(input){
    if( input.detail.value.length >= 4){
      wx.hideKeyboard()
    }
    this.setData({
      user_pass:input.detail.value,
    })
  },
  // 发送按钮被点击
  send_button_click:function(button){
    this.requestServiceSendVerifycodes(this.data.user_name)
    var that = this;
    this.setData({
        send_time: 59
      })
    var timer =  setInterval(function(){
      var time = that.data.send_time - 1;
      if(time == -1){
        time = 60;
        clearInterval(timer)
      }
      that.setData({
        send_time: time
      })
    },1000)
  },
  // 登录按钮被点击
  login_button_click:function(button){
    this.requestServiceLogin()
  },
  requestServiceSendVerifycodes:function(phoneNumber){
    wx.request({
      url: "http://customer.jiuyunda.net:3000/api/v1/customer_v1/verifycode",
      data: {
        mobile:phoneNumber
      },
      method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(res){
        wx.showToast({
          title:res.data.message,
          duration:350,
        })
      },
      fail: function() {
      },
      complete: function() {
      }
    })
  },
  // 请求服务器进行登录
  requestServiceLogin:function(){
    var that = this;
    var customer = {"mobile":this.data.user_name};
    console.log(customer);
    wx.request({
      url: 'http://customer.jiuyunda.net:3000/api/v1/customer_v1/registration',
      data: {
        customer:  JSON.stringify(customer),
        code:this.data.user_pass
      },
      method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(res){
        that.loginsuccess(res.data,that.data.user_name)
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  loginsuccess:function(data,mobile){
    console.log("登录成功了")
    if(data.success){
        wx.showToast({
          title:"登录成功",
          duration:350,
        })
      var app = getApp();
      app.globalData.islogin = true;
      app.globalData.user_mobile = mobile;
      console.log("登录状态")
      console.log(app.globalData.islogin)
      wx.navigateBack({
        delta: 1,
        success: function(res){
        },
        fail: function() {
        },
        complete: function() {
        }
      })
    }else{
      wx.showToast({
          title:"登录失败",
          duration:350,
        })
    }
  }
})