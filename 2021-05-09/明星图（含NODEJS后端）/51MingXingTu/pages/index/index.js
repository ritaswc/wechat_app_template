//index.js
//获取应用实例
var app = getApp()
var dialog = require("../../utils/dialog.js")
var wxNotificationCenter = require("../../utils/WxNotificationCenter.js")

Page({
   data: {
    contentList:[],
    currentType:wx.getStorageSync('currentType'),
    types:[]
  },
  //加载第一个类型的列表
  onLoad:function(){
    this.setData({
       types:wx.getStorageSync('types') ? wx.getStorageSync('types') : app.globalData.types
    })
    
    if(!this.data.currentType){
      let that = this
      this.data.types.every(function(item){
        if(item.is_show){
            wx.setStorageSync('currentType', item.value)
            that.setData({currentType:item.value})
            return false
          }else{
            return true
          }
      })
    }
    
    if(this.data.currentType){
        this.getList(this.data.currentType)
    }

    //添加通知监听
    wxNotificationCenter.addNotification("typesChangeNotification",this.typesChangeNotificationHandler,this)
  },
  //接收类别编辑页面中修改了类别标签的通知，重新处理
  typesChangeNotificationHandler:function(){
    this.setData({
        types:wx.getStorageSync('types'),
        currentType:wx.getStorageSync('currentType')
      })
      this.getList(wx.getStorageSync('currentType')) 
  },
  getList:function(type){
    dialog.loading()
        var that = this
        //请求数据
        wx.request({
          url:app.globalData.api.dbmeizhiurl+"pic"+"?type="+type,
          success:function(ret){
            ret = ret['data']
            if(ret['code'] == 0 ){
              that.setData({
              contentList:ret['data']
              })
            }else{
              setTimeout(function(){
                dialog.toast("网络超时啦~")
              },1)
            }
          },
          complete:function(){
            wx.stopPullDownRefresh()
            setTimeout(function(){
              dialog.hide()
            },1000)
          }
        })
  },
  onPullDownRefresh:function(){
    this.getList(this.data.currentType)
  },
  //点击某一个title条
  changeType:function(e){
    var type = e.currentTarget.dataset.value
    if(type == this.data.currentType){
      return;
    }
    this.setData({currentType:type})
    app.globalData.currentType = type
    this.getList(type)
  },
  gotoTypeEdit:function(e){
    wx.navigateTo({
      url: '../types/types?id=1',
    })
  },
  gotoAlbum:function(e){
    console.log("gotoAlbum");
    let param = e.currentTarget.dataset, 
    index = param.id.lastIndexOf("\/"),
    title = param.title,
    // id=param.id.replace(/[^0-9]/ig,"")
    id = param.id.substring(index + 1, param.id.length).replace(/[^0-9]/ig,"");
    console.log("param: " + param);
    console.log("title: " + title);
    console.log("id: " + id);
    var url = "../album/album?title="+title+"&id="+id;
    console.log("ready");
    wx.navigateTo({
      url:url,
      success: function(res){
        console.log('跳转到news页面成功')// success
      },
      fail: function() {
      console.log('跳转到news页面失败')  // fail
      },
      complete: function() {
        console.log('跳转到news页面完成') // complete
      }
    })
  }
})
