//index.js
//获取应用实例
var app = getApp()
var dialog = require("../../utils/dialog.js")
var wxNotificationCenter = require("../../utils/WxNotificationCenter.js")

Page({
  //加载第一个类型的列表
  onLoad:function(){
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
    this.getList(this.data.currentType)
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
          url:app.globalData.api.listBaseUrl+type,
          success:function(ret){
            ret = ret['data']
            if(ret['showapi_res_code'] == 0 && ret['showapi_res_body'] &&  ret['showapi_res_body'] ['ret_code']==0){
              that.setData({
              contentList:ret['showapi_res_body']['pagebean']['contentlist']
              })
            }else{
              setTimeout(function(){
                dialog.toast("网络出错啦~")
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
    let param = e.currentTarget.dataset, title = param.title, id=param.id
    var url = "../album/album?title="+title+"&id="+id.replace(".","##");
    wx.navigateTo({url:url})
  },
  data: {
    contentList:[],
    currentType:wx.getStorageSync('currentType'), 
    types:wx.getStorageSync('types') ? wx.getStorageSync('types') : app.globalData.types
  }
})
