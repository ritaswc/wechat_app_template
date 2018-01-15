var app = getApp();
Page({
  data:{
      product_id:"",//商品id
      good_detail:{},//商品详情数据
      loop_image_height:0,//轮播图高
      good_detail_image_height:0//商品详情图高
  },
  onLoad:function(options){
    // 生命周期函数--监听页面加载
    this.setData({
        product_id:options.product_id,
    })
    //
    wx.setNavigationBarTitle({
      title: options.title,
      success: function(res) {
      }
    })
    var that = this;
    app.getUserBid(function(res){
      that.requestFromService(res)
    })
  },
  onReady:function(){
    // 生命周期函数--监听页面初次渲染完成
  },
  onShow:function(){
    // 生命周期函数--监听页面显示
    this.setData({
      loop_image_height : app.globalData.systemInfo.windowWidth,
    })
  },
  // 图片加载
  imageLoad:function(image){
    var app = getApp();
    console.log(image)
    var imageHeight = app.globalData.systemInfo.windowWidth / image.detail.width * image.detail.height;
    this.setData({
      good_detail_image_height:imageHeight
    }) 
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
//   从服务器请求数据
  requestFromService:function(re){
      var that = this;
      wx.request({
        url: 'http://www.jiuyunda.net:90/api/v2/product/product_details',
        data: {
            userinfo_id:re._id,
            product_id:that.data.product_id
        },
        method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        // header: {}, // 设置请求的 header
        success: function(res){
          that.setData({
            good_detail:res.data
          })
        },
        fail: function() {
          // fail
        },
        complete: function() {
          // complete
        }
      })
  },
})