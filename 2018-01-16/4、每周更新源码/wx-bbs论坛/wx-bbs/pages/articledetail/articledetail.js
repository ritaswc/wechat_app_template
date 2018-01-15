
var app = getApp()
Page({
  data:{
     audioIcon:"http://i.pengxun.cn/content/images/voice/voiceplaying.png",
     articleId:1
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var articleId = options.id;
    // 根据ID获取帖子详情
    showDetail(articleId);
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

// 展示页面
  showDetail: function(articleId) {
    
  }



})