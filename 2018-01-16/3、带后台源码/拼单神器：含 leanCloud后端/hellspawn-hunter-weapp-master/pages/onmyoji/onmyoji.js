// pages/onmyoji/onmyoji.js
var http = require("../../utils/http.js");
Page({
  data:{
    id: "",
    hellspawn: {},
    sceneList: [],
    haveScene: true
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    wx.showToast({
        title: '加载中',
        icon: 'loading',
        duration: 100000
    });
    this.setData({
      id: options.id
    })
    if(options.id){
    this.getHellspawnInfo();
    this.getHellspawnScenes();}
  },
  searchRecord: function(id, records){
    for(var i=0;i<records.length;i++){
      if (records[i].id == id){
        return i;
      }
    }
    return -1;
  },
  addHellspawnToLocalStoage: function(){
    var oldRecord = wx.getStorageSync('search_record');
    if(oldRecord){
      var index = this.searchRecord(this.data.id, oldRecord);
      var weight = wx.getStorageSync('weight') + 1;
      var hs = this.data.hellspawn;
      hs.weight = weight;
      if(index>=0){
        oldRecord[index] = hs;
        oldRecord.sort(function(a, b){
        return b.weight - a.weight});
      }else{
        oldRecord.unshift(hs);
      }
      wx.setStorage({
        key: 'search_record',
        data: oldRecord});
    }else{
      var weight = 1;
      var hs = this.data.hellspawn;
      hs.weight = weight;
      wx.setStorage({
        key: 'search_record',
        data: new Array(hs)
      });
    }
    wx.setStorage({
      key: 'weight',
      data: weight
      });
  },
  getHellspawnInfo: function() {
    var url = http.generateUrl("api/v1/hellspawn/" + this.data.id);
    var context = this;
    wx.request({
      url: url,
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(res){
        // success
        if(res.data.status==1){
          context.setData({
              hellspawn: res.data.body.hellspawn
          })
          context.addHellspawnToLocalStoage();
          wx.setNavigationBarTitle({
            title: context.data.hellspawn.name,
            success: function(res) {
              // success
            }
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
  },
  getHellspawnScenes: function(){
    var url = http.generateUrl("api/v1/hellspawn/" + this.data.id + '/scenes');
    var context = this;
    wx.request({
      url: url,
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(res){
        // success
        wx.hideToast();
        if(res.data.status==1){
          context.setData({
            sceneList: res.data.body.scene_list
          });
          if(context.data.sceneList.length == 0){
            context.setData({
              haveScene: false
            });
          }
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
  onShareAppMessage: function () {
    var title = '式神猎手 | ' + this.data.hellspawn.name + ' ' + this.data.hellspawn.clue1;
    return {
      title: title,
      path: 'pages/onmyoji/onmyoji?id=' + this.data.id
    }
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
  }
})