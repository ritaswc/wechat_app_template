
var util = require("../../utils/util.js")
var crypt = require("../../utils/crypt.js")
var app = getApp();

Page({
  data:{
      selected:1,
      scoreList:null, // 积分列表
      exchangeList:null // 兑换列表
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    this.init();
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
  /**
   * 初始化数据
   */
  init:function() {
      var that = this;
      app.getInit(function(result){
        var tmpFile = result.obj.tmpFile;
        var minisId = result.obj._Minisns.Id;
        var unionid = result.obj._LookUser.unionid;
        var verifyModel = util.primaryLoginArgs(unionid);
        wx.uploadFile({
          url: 'https://snsapi.vzan.com/minisnsapp/integralLog',
          filePath: tmpFile,
          name:'file',
          // header: {}, // 设置请求的 header
          formData: {"deviceType": verifyModel.deviceType, "uid": verifyModel.uid, "versionCode":verifyModel.versionCode,
          "timestamp": verifyModel.timestamp, "sign":verifyModel.sign,fid:minisId}, // HTTP 请求中其他额外的 form data
          success: function(res){
                let result = JSON.parse(res.data);
                console.log("Score初始化数据", result);
                that.setData({scoreList:result.objArray})
          }
        })
      })
  },

  /**
   * 切换列表
   */
  showList:function(e){
        var that = this;
        let id = e.currentTarget.dataset.id;
        if (id == 1) {
            that.setData({selected:1})
            // 获取积分记录
            that.getScoreList(); 
        } else {
            that.setData({selected:2})
            // 获取兑换记录记录
            that.getExchangeList();
        }
  },

  /**
   * 获取积分记录 
   */
  getScoreList: function() {
      var that = this;
      app.getInit(function(result){
        var tmpFile = result.obj.tmpFile;
        var minisId = result.obj._Minisns.Id;
        var unionid = result.obj._LookUser.unionid;
        var verifyModel = util.primaryLoginArgs(unionid);
        wx.uploadFile({
          url: 'https://snsapi.vzan.com/minisnsapp/integralLog',
          filePath: tmpFile,
          name:'file',
          // header: {}, // 设置请求的 header
          formData: {"deviceType": verifyModel.deviceType, "uid": verifyModel.uid, "versionCode":verifyModel.versionCode,
          "timestamp": verifyModel.timestamp, "sign":verifyModel.sign,fid:minisId}, // HTTP 请求中其他额外的 form data
          success: function(res){
                let result = JSON.parse(res.data);
                console.log("Score初始化数据", result);
                that.setData({scoreList:result.objArray})
          }
        })
      })
  },
  
  /**
   * 获取兑换记录
   */
  getExchangeList:function(){
      var that = this;      
      app.getInit(function(result){
          var tmpFile = result.obj.tmpFile;
          var minisId = result.obj._Minisns.Id;
          var unionid = result.obj._LookUser.unionid;
          var verifyModel = util.primaryLoginArgs(unionid);
          wx.uploadFile({
            url: 'https://snsapi.vzan.com/minisnsapp/integralexLog',
            filePath: tmpFile,
            name:'file',
            // header: {}, // 设置请求的 header
            formData: {"deviceType": verifyModel.deviceType, "uid": verifyModel.uid, "versionCode":verifyModel.versionCode,
                       "timestamp": verifyModel.timestamp, "sign":verifyModel.sign,fid:minisId}, // HTTP 请求中其他额外的 form data
            success: function(res){
                let result = JSON.parse(res.data);
                console.log("获取积分兑换记录", result)
                // that.setData({exchangeList:result.objArray})
            }
          })
      })
  },

})