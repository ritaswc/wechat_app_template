Page({
  data:{
    islogin:false,
    user_nickname:null,
    avatar_url:null,
    order_list:[{"image":"../resource/ToBePaid.png","title":"待支付"},{"image":"../resource/Deliveries.png","title":"配送中"},{"image":"../resource/ItHasShipped.png","title":"已配送"},{"image":"../resource/Completed.png","title":"待评价"}],
    money_list:[{"image":"../resource/JKSetUpJiFen.png","title":"积分"},{"image":"../resource/JKMSYSetUpWineLib.png","title":"酒库"},{"image":"../resource/JKMSYSetUpRecommend.png","title":"优惠券"},{"image":"../resource/JKMSYSetUpWineRecommend.png","title":"酒券"}],
    other_list:["推荐有奖","意见反馈","客服热线","酒运达"]
  },
  onLoad:function(options){
    // 如果用户没有登录需要用户登录
    
  },
  onReady:function(){
    // 生命周期函数--监听页面初次渲染完成
  },
  onShow:function(){
    // 生命周期函数--监听页面显示
    var app = getApp()
    this.setData({
      islogin:app.globalData.islogin,
    })
    if(this.data.islogin){
      this.requestServiceData()
    }
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
  onShareAppMessage: function() {
    // 用户点击右上角分享
    return {
      title: 'title', // 分享标题
      desc: 'desc', // 分享描述
      path: 'path' // 分享路径
    }
  },
  // 去登陆
  gotologin:function(){
    wx.navigateTo({
        url: '../login/login',
        success: function(res){
        },
        fail: function() {
        },
        complete: function() {
        }
      })
  },
  imageloaderror:function(image){
    console.log(image);
    this.setData({
      avatar_url:"../resource/JKMSYDefaultUserImage.png"
    })
  },
  // 请求我的数据
  requestServiceData:function(){
    var app = getApp();
    var that = this;
    wx.request({
      url: 'http://customer.jiuyunda.net:3000/api/v1/customer/customerInfo?mobile=17638574518',
      data: {
        mobile:app.globalData.user_mobile
      },
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(res){

        that.setData({
            avatar_url : res.data.avatar_url,
            user_nickname : res.data.nick_name,
        })
        console.log(res)
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  }
})