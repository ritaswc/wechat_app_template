// pages/todos/todos.js
const AV = require('../../utils/leancloud.js');
var app =getApp();
Page({
  data: {
    hidden:true,
    content:0,
    search:"",
    todos: [],
    searchs:[],
    indicatorDots: true,
    autoplay: true,
    interval: 5000,
    duration: 1000,
    newsData:[
    
    ],
    page:1
  }
  ,
  hidden:function(){
      this.data.hidden=!this.data.hidden;
      this.setData({hidden:this.data.hidden});
  },
  kong:function(e){
    
    this.data.search='';
    this.setData({search:this.data.search});
  }
  ,
  //搜索页页
  search:function(e){
    var title=e.detail.value||e.target.dataset.title;    
    var _this=this;
    if(e.target.dataset.title){
      _this.setData({search:title});
    }
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
        
      if(res.data.showapi_res_body.pagebean.allNum!=0){
          _this.data.newsData=res.data.showapi_res_body.pagebean.contentlist;  
          _this.setData({content:2});
           console.log("数据",res.data.showapi_res_body.pagebean)
          // 添加历史记录
          if(_this.data.searchs.length>8){
            _this.data.searchs.pop();
          }
         _this.data.searchs.unshift({title:e.detail.value});
         _this.setData({
            newsData:_this.data.newsData,
            searchs: _this.data.searchs}) ;
          // 缓存
          wx.setStorage({
                key:"searchs",
                data:_this.data.searchs
          })     
      }else{
         _this.setData({content:1});
      }
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
  onReady: function() {

  },
  onLoad:function(options){
     console.log("k")
     var _this=this;
    // 页面初始化 options为页面跳转所带来的参数
    wx.getStorage({
        key: 'searchs',
        success: function(res) {
            _this.setData({searchs:res.data});
        } 
    });
  },
  onReady:function(){
    // 页面渲染完成
    console.log(2);
  },
  onShow: function(){
    console.log(3)
  },
  onHide:function(){
    console.log(4)
    // 页面隐藏
  },
  onUnload:function(){
    console.log(5)
    // 页面关闭
  },
  toRecom:function(){
    wx.switchTab({
            url: '/pages/recommendation/recommendation'
          })
  },
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
      title:_this.data.search,          
      showapi_sign:"f729add89f4c4851b8da64f6936ff6f6",
      showapi_timestamp:"",
    },
    header: {
      'content-type': 'application/json'
    },
    success: function(res) {
      var list = res.data.showapi_res_body.pagebean.contentlist;
      console.log(_this.data.search,list);
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
});