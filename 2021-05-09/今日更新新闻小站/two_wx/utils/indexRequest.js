const AV = require('./leancloud.js');
var request = require('./request.js');
//新闻主页，请求目录  在在onload中调用
function getItems(){
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
        _this.data.favorData=res.data.showapi_res_body.channelList;
        //  wx.setNavigationBarTitle({title : "title"});
        console.log(_this.data.favorData);
      //  _this.data.favorData= _this.data.favorData.slice(0,10);
      //  for(var i=0;i<_this.data.favorData.length;i++){
      //     _this.data.favorData[i].name=_this.data.favorData[i].name.slice(0,2);
      //  }
         _this.setData({
           favorData:_this.data.favorData
         });
        },
        fail:function(){
           request.error();
        }
    })
  }
//根据channelId搜索新闻 ，默认第一条的id
function getNewsdes(id){
    var data;
     wx.request({
        url: 'https://route.showapi.com/109-35', 
      data:{
          channelId:id,
          channelName:"",
          maxResult:"10",
          needAllList:"",
           needContent:"",
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
        data=res.data.showapi_res_body
        console.log(data);
        },
        fail:function(){
           request.error();
        }
    })
    return data;
}
module.exports={
    getItems:getItems
}
// var app = getApp()
// Page({
//   data: {
//     motto: 'Hello World',
//     userInfo: {}
//   },
//   //事件处理函数
//   bindViewTap: function() {
//     wx.navigateTo({
//       url: '../logs/logs'
//     })
//   },
//   onLoad: function () {
//     console.log('onLoad')
//     var that = this
//     //调用应用实例的方法获取全局数据
//     app.getUserInfo(function(userInfo){
//       //更新数据
//       that.setData({
//         userInfo:userInfo
//       })
//     })
//   }
// })