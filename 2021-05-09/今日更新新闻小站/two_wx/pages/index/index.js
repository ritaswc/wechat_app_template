//index.js
//获取应用实例
var request = require('../../utils/request.js');
var app =getApp();
Page({
  data: {
    url:"/pages/details/details?",
    imgUrls: [
      'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      'http://img06.tooopen.com/images/20160818/tooopen_sy_175866434296.jpg',
      'http://img06.tooopen.com/images/20160818/tooopen_sy_175833047715.jpg'
    ],
    indicatorDots: true,
    autoplay: true,
    interval: 5000,
    duration: 1000,
    isTabSelector: false,
    channelId:null,
    page:1,
    isPullDown:false,
    favorData:[
      {
        channelId:"5572a108b3cdc86cf39001cd",
        name:"国内",
        selected:true,
      },{
        channelId:"5572a108b3cdc86cf39001ce",
        name:"国际",
        selected:true,
      },{
        channelId:"5572a108b3cdc86cf39001cf",
        name:"军事",
        selected:true,
      },{
        channelId:"5572a108b3cdc86cf39001d0",
        name:"财经",
        selected:true,
      },{
        "channelId": "5572a108b3cdc86cf39001d1",
        "name": "互联网",
        selected:true,
      },{
        "channelId": "5572a108b3cdc86cf39001d5",
        "name": "娱乐",
        selected:true,
      },{
        "channelId": "5572a108b3cdc86cf39001d6",
        "name": "游戏",
        selected:true,
      },{
        "channelId": "5572a108b3cdc86cf39001d2",
        "name": "房产",
        selected:true,
      },{
        "channelId": "5572a108b3cdc86cf39001d9",
        "name": "科技",
        selected:true,
        page:1
      },{
        "channelId": "5572a10ab3cdc86cf39001ec",
        "name": "电影最新",
        selected:true,
      }
    ],
    newsData:[],
    scrollData:[],
    attrationData:[],
    newestData:[],
    animationData:{}
  },
  //列表
   tourl:function(e){
    // object转换get字符串
    var index=e.target.dataset.index;
    var temp=this.data.newsData[index];
    app.array=temp;
    // var urls=this.data.url;
    // console.log("aa",index,temp);    
    // urls=urls+"&title="+temp.title+
    // "&source="+temp.source+"&pubDate="+temp.pubDate+"&channelName="+temp.channelName;
    // for(var i=0;i<temp.allList.length;i++){
    //   if(temp.allList[i].url!=null){
    //     urls=urls+"&"+i+"="+temp.allList[i].url
    //   }else{
    //     urls=urls+"&"+i+"="+temp.allList[i]
    //   }
    // }
    wx.navigateTo({url:"/pages/details/details"});
    
  },
  //滚动条
  tourls:function(e){
    // object转换get字符串
    var index=e.target.dataset.index;
    var temp=this.data.scrollData[index];
    app.array=temp;
    console.log("bb",app.array);
    wx.navigateTo({url:"/pages/details/details"});
    
  },
  //搜索
  tosearch:function(){
          wx.switchTab({
            url: '/pages/todes/todos'
          })
  },
  //获取目录
   getItems:function(){
    var _this = this;
    wx.request({
      url: 'https://route.showapi.com/109-34', 
      data:{
          showapi_appid:"30851", 
          showapi_sign:"f729add89f4c4851b8da64f6936ff6f6",
      },
      header: {
          'content-type': 'application/json'
      },
      success: function(res) {
        //生成喜好添加页的选项
        var channelList = res.data.showapi_res_body.channelList;
        var favorData = _this.data.favorData;
        channelList.forEach(function(obj){
          for(var i = 0;i < favorData.length;i++){
            if(obj.channelId == favorData[i].channelId){
              obj.selected = true;
              break;
            }
            else
              obj.selected = false;
          }
        })
        channelList.forEach(function(obj){
          if(obj.name.substr(-2) == "焦点"){ 
            obj.name = obj.name.substring(0,obj.name.length-2);
            _this.data.attrationData.push(obj);
          }
          else if(obj.name.substr(-2) == "最新"){
            obj.name = obj.name.substring(0,obj.name.length-2);
            _this.data.newestData.push(obj);
          } 
        })

      //  _this.data.favorData= _this.data.favorData.slice(0,10);
      //  for(var i=0;i<_this.data.favorData.length;i++){
      //     _this.data.favorData[i].name=_this.data.favorData[i].name.slice(0,2);
      //  }
         _this.setData({
           attrationData:_this.data.attrationData,
           newestData:_this.data.newestData
         });
        },
        fail:function(){
           request.error();
        }
    })
  },

  //切换数据
  getNewsdes:function(e){
    var _this=this;
    _this.data.page = 1;
    this.setData({
      channelId:e.target.dataset.channelid,
    })
     wx.request({
        url: 'https://route.showapi.com/109-35', 
      data:{
          channelId:e.target.dataset.channelid,
          channelName:"",
          maxResult:"15",
          needAllList:"",
          needContent:"1",
          needHtml:"",  
          page:"1",
          showapi_appid:"30851",
          title:"",          
          showapi_sign:"f729add89f4c4851b8da64f6936ff6f6",
          showapi_timestamp:"",
      },
      header: {
          'content-type': 'application/json'
      },
      success: function(res) {
          _this.data.scrollData=[];
          var list = res.data.showapi_res_body.pagebean.contentlist;
          var n = 0;
          for(var i = 0;n<3&&i<list.length;i++){
            if(list[i].imageurls.length>0){
              _this.data.scrollData.push(list[i]);
              list.splice(i,1);
              n++;
            } 
          }
          _this.data.newsData=list;
          _this.setData({
            newsData:_this.data.newsData,
            scrollData:_this.data.scrollData,
            page:_this.data.page
          })
      },
      fail:function(){
        request.error();
      }
    })
  },

  //首页
   getNewOne:function(id){
     var _this=this;
     wx.request({
      url: 'https://route.showapi.com/109-35', 
      data:{
          channelId:id,
          channelName:"",
          maxResult:"15",
          needAllList:"",
          needContent:"1",
          needHtml:"",  
          page:"1",
          showapi_appid:"30851",
          title:"",          
          showapi_sign:"f729add89f4c4851b8da64f6936ff6f6",
          showapi_timestamp:"",
      },
      header: {
          'content-type': 'application/json'
      },
      success: function(res) {
        _this.data.scrollData=[];
        var list = res.data.showapi_res_body.pagebean.contentlist;
        var n = 0;
        for(var i = 0;n<3&&i<list.length;i++){
          if(list[i].imageurls.length>0){
            _this.data.scrollData.push(list[i]);
            list.splice(i,1);
            n++;
          } 
        }
        _this.data.newsData=list;
        _this.setData({
          newsData:_this.data.newsData,
          scrollData:_this.data.scrollData,
          channelId:id
          })
      },
      fail:function(){
          request.error();
      }
    })
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    this.getItems();
    this.getNewOne("5572a108b3cdc86cf39001cd");
  },
  onReady:function(){
    // 页面渲染完成
    console.log(2)
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
  //控制喜好选择的页面
  ctrlSelector:function(){
    var _this = this;
    _this.data.favorData = [];
    this.data.attrationData.forEach(function(obj){
      if(obj.selected){
        _this.data.favorData.push(obj)
      }
    })
    this.data.newestData.forEach(function(obj){
      if(obj.selected){
        _this.data.favorData.push(obj)
      }
    })
    this.setData({
      isTabSelector : !this.data.isTabSelector,
      favorData : this.data.favorData,
    })
  },
  //控制焦点频道标签是否选择
  ctrlAttration:function(e){
    var index = e.target.dataset.index;
    this.data.attrationData[index].selected = !this.data.attrationData[index].selected;
    this.setData({
      attrationData : this.data.attrationData
    })
  },
  //控制最新频道标签是否选择
  ctrlNews:function(e){
    var index = e.target.dataset.index;
    console.log(this.data.newestData[index].selected)
    this.data.newestData[index].selected = !this.data.newestData[index].selected;
    this.setData({
      newestData : this.data.newestData
    })
  },
  //下拉刷新
  onPullDownRefresh: function(){
    var _this = this;
    // _this.setData({
    //   isPullDown:true
    // });
    this.getNewOne(this.data.channelId);
    setTimeout(function(){
      // _this.setData({
      //   isPullDown:false
      // })
      wx.stopPullDownRefresh();
    },1500)   
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
      channelId:_this.data.channelId,
      channelName:"",
      maxResult:"15",
      needAllList:"",
      needContent:"1",
      needHtml:"",  
      page:_this.data.page,
      showapi_appid:"30851",
      title:"",          
      showapi_sign:"f729add89f4c4851b8da64f6936ff6f6",
      showapi_timestamp:"",
    },
    header: {
      'content-type': 'application/json'
    },
    success: function(res) {
      var list = res.data.showapi_res_body.pagebean.contentlist;
      list.forEach(function(obj){
        _this.data.newsData.push(obj)
      })
      _this.setData({
        newsData:_this.data.newsData,
        page:_this.data.page
      })
    },
    fail:function(){
        request.error();
    }
    })
  }
})
