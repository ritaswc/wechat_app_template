//index.js
//获取应用实例
var http = require("../../utils/http.js");
var app = getApp()
Page({
  data: {
    hellspawnList: [],
    popularList: [],
    searchText: ""
  },
  //事件处理函数
  getPopular: function(){
    var url = http.generateUrl('api/v1/populars');
    var context = this;
    wx.request({
      url: url,
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(res){
        // success
        if(res.data.status==1){
          context.setData({
            popularList: res.data.body.popular_list
          });
        }
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  bindSearch: function(e) {
    var context = this;
    this.setData({
      searchText: e.detail.value
    })
    if(this.data.searchText){
        var url = http.generateUrl('api/v1/search/' + this.data.searchText);
        wx.request({
          url: url,
          method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
          // header: {}, // 设置请求的 header
          success: function(res){
            // success
            if (res.data.status==1){
              context.setData({
                hellspawnList: res.data.body.hellspawn_list
              })
            }
          },
          fail: function() {
            // fail
          },
          complete: function() {
            // complete
          }
        })
    }
  },
  onShareAppMessage: function () {
    var title = '式神猎手 | 快速查寻阴阳师妖怪'
    return {
      title: title,
      path: 'pages/index/index'
    }
  },
  onShow:function(){
    this.getPopular();
  }
  
})
