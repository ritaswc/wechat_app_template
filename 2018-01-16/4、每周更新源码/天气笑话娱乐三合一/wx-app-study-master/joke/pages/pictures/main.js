var app = getApp();
Page({
  data:{
    pics:[],
    page:1,
    pagesize:15
  },
  onLoad:function(options){
    // 生命周期函数--监听页面加载
    console.log(app.globalData.appkey);
    this.loadPics();
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
    this.setData({page: this.data.page+1});
    this.loadPics();
  },
  onShareAppMessage: function() {
    // 用户点击右上角分享
    return {
      title: 'title', // 分享标题
      desc: 'desc', // 分享描述
      path: 'path' // 分享路径
    }
  },

  loadPics: function() {
      var that = this;
      var key = app.globalData.appkey;
      var url = "http://japi.juhe.cn/joke/img/text.from";
      wx.request({
        url: url,
        data: {
            key: key,
            page: that.data.page,
            pagesize: that.data.pagesize
        },
        method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        // header: {}, // 设置请求的 header
        success: function(res){
          // success
          console.log(res);
          that.setData({pics: that.data.pics.concat(res.data.result.data)});
        }
      })
  }
})