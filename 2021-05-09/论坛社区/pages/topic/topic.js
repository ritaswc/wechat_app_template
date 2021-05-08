// pages/topic/topic.js
Page({
  data:{
    topics:[],
    page:1,

  },
  onLoad:function(options){
    this.loadTopics();
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  onReachBottom:function(){
    this.setData({
      "page":this.data.page + 1
    });
    this.loadTopics();
  },
  showDetail:function(event){
    wx.navigateTo({
      url: '../topics/detail/detail?id=' + event.target.dataset.id
    })
  },
  loadTopics:function(){
    var that = this;
    var old = this.data.topics;
    // 页面初始化 options为页面跳转所带来的参数
    wx.showToast({
      title: '加载中',
      icon: 'loading',
      duration: 10000
    })
    wx.request({
      url: 'https://nutz.cn/yvr/api/v1/topics', 
      data: {
        page: that.data.page 
      },
      success: function(res) {
        wx.hideToast();
        that.setData({
          'topics':old.concat(res.data.data)
        });
      }
    })
  }
})