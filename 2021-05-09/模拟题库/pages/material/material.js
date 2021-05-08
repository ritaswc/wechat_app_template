//material.js
//获取应用实例
var app = getApp();
var WxParse = require('../../wxParse/wxParse.js');
Page({
  data: {
		showBottom: false,
		isDetail: false,
		materials: [],
		details: []
  },
  onLoad: function(){
		this.materialsGet();
  },
  materialsGet: function() {
    var that = this;
    wx.request({
      url: app.url.host + app.url.topicsUrl,
      method: 'GET',
      data: {
        pid: -1,
        cid: wx.getStorageSync('SubjectId'),
        field: 'asc'
      },
      header: {
        Authorization: wx.getStorageSync('Authorization'),
        // 'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){
        if(res.statusCode == '200') {
          var data = res.data, arr = [];
          if(data){
            for (var i = 0; i < data.length; i++) {
              arr.push({
                title: data[i].Title,
                materialId: data[i].Id,
                content: that.contentHandler(data[i].Content),
                avatar: that.avatarHandler(data[i].Attachment)
              });
            }				
            that.setData({materials: arr});		
          }else{
            var toastDelay = 1500;
            wx.showToast({
              title: '该科目没有资料',
              duration: toastDelay
            });
            setTimeout(function(){
              wx.navigateBack({
                delta: 1, // 回退前 delta(默认为1) 页面
              })
            },toastDelay);
          }         
        } else {
          app.unauthorized(res.statusCode);
        } //statusCode-else结束   

      }
    });
  },  
  detailGet: function(e) {
    var id = parseInt(e.currentTarget.id);
    var that = this;
    wx.request({
      url: app.url.host + app.url.contentUrl,
      method: 'GET',
      data: {
        id: id,
        field:'asc'
      },
      header: {
        Authorization: wx.getStorageSync('Authorization'),
        // 'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){
        if(res.statusCode == '200') {
          var data = res.data, arr = [];
          if(data){
            for (var i = 0; i < data.length; i++) {
              arr.push({
                title: data[i].Title,
                materialId: data[i].Id,
                //content: data[i].Content,
                avatar: that.avatarHandler(data[i].Attachment)
              });
            }				
            that.setData({
              details: arr,
              isDetail: true,
              showBottom: true
            });		
            //wxParse插件渲染html
            for (var i = 0; i < data.length; i++) {
              WxParse.wxParse('details['+i+'].content', 'html', data[i].Content, that);
            }			           
          }        
        } else {
          app.unauthorized(res.statusCode);
        } //statusCode-else结束   
      }
    });
  },  
  pageSwitch: function(e){
    this.setData({isDetail: e.currentTarget.dataset.isDetail == 'true'});
  },	  
  avatarHandler: function(attachment) {
    var cover = '../image/book.png';
    if (!attachment.length) return cover;
    if (JSON.parse(attachment).hasOwnProperty('covers')) {
      cover = JSON.parse(attachment).covers[0];
      return app.url.host + cover.substring(1);
    } else {
      return cover;
    }
  },			
  contentHandler: function(str) {
    //去掉标签，截取前30个字符
    str = str.replace(/^\<.*?>(.*)<\/.*?>$/, '$1');
    return str.replace(/\<.*\>/g, '').substring(0, 30)+'...(点击查看详情)';
  },  
});
