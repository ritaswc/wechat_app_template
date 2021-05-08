// pages/newsItem/newsItem.js
var app=getApp();
Page({
  data:{ newsData:[],page:1
  },
    //搜索页页
  search:function(){
    var title=getApp().title;
    var _this=this;
     wx.request({
        url: 'https://route.showapi.com/109-35', 
      data:{
          channelId:"",
          channelName:"",
          maxResult:"15",
          needAllList:"",
          needContent:"1",
          needHtml:"",  
          page:"1",
          showapi_appid:"30851",
          title:title,          
          showapi_sign:"f729add89f4c4851b8da64f6936ff6f6",
          showapi_timestamp:"",
      },
      header: {
          'content-type': 'application/json'
      },
      success: function(res) {  
       _this.data.newsData=res.data.showapi_res_body.pagebean.contentlist;  
           console.log("数据",res.data.showapi_res_body.pagebean)
   
         _this.setData({
            newsData:_this.data.newsData}) ;
       
     },
        fail:function(){
           request.error();
     }})
  },
  tourl:function(e){
     // object转换get字符串
    var index=e.target.dataset.index;
    var temp=this.data.newsData[index];
    app.array=temp;
    wx.navigateTo({url:"/pages/details/details"});
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    this.search();
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
  }  ,
  //上拉加载
  onReachBottom:function(){
    wx.showToast({
      title: "正在加载",
      icon: 'loading',
      duration: 1000
    })
    var _this=this;
    _this.data.page = _this.data.page+1;
    wx.request({
    url: 'https://route.showapi.com/109-35', 
    data:{
      channelId:"",
      channelName:"",
      maxResult:"15",
      needAllList:"",
      needContent:"1",
      needHtml:"",  
      page:_this.data.page,
      showapi_appid:"30851",
      title:getApp().title,          
      showapi_sign:"f729add89f4c4851b8da64f6936ff6f6",
      showapi_timestamp:"",
    },
    header: {
      'content-type': 'application/json'
    },
    success: function(res) {
      var list = res.data.showapi_res_body.pagebean.contentlist;
      console.log(getApp().title,list);
      if(list.length!=0){
        list.forEach(function(obj){
          _this.data.newsData.push(obj)
        })
        _this.setData({
          newsData:_this.data.newsData,
          page:_this.data.page
        })
      }
      else{
        wx.showToast({
            title: "没有了",
            icon: 'loading',
            duration: 1000
          })
      }
    },
    fail:function(){
        request.error();
    }
    })
  }
})