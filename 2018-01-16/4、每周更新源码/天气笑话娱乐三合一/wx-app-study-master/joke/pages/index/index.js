var util = require("../../utils/util.js");
var app = getApp();
Page({
  data:{
    jokes:[],
    page: 1,
    pagesize:15
  },
  onLoad:function(options){
    // 生命周期函数--监听页面加载
    this.loadJokes();
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
    this.loadJokes();
  },
  onShareAppMessage: function() {
    // 用户点击右上角分享
    return {
      title: 'title', // 分享标题
      desc: 'desc', // 分享描述
      path: 'path' // 分享路径
    }
  },
  
  loadJokes: function() {
    var that = this;
    var key = app.globalData.appkey;
    var url = "http://japi.juhe.cn/joke/content/text.from";

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
        //console.log(res);
        
        that.rebuildData(res.data.result.data);
      }
    })
  },

  rebuildData: function(data) {
    var tmp_data = [];
    for(var i=0; i<data.length; i++) {
      var d = data[i];
      tmp_data.push({"updatetime":d.updatetime, "content":util.replaceAll(d.content, "　　", "\r\n")});
    }
    //console.log(tmp_data);
    //tmp_data = tmp_data.push(this.data.jokes);
    this.setData({jokes: this.data.jokes.concat(tmp_data)});
  }
})