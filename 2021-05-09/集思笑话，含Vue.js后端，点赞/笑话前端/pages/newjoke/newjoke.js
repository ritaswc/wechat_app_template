var wxParse = require('../../wxParse/wxParse')
var WXRequest = require('../../utils/util').WXRequest
var app = getApp()
Page({
  data: {
    images:[],
    content:'',
    userInfo: {}
  },
  bindFormSubmit: function(e) {
    wx.showToast({
      title: '正在发送，请稍后',
      icon: 'success',
      duration: 2000
    })

    var content = e.detail.value.content
    
    var images = this.data.images
    var reg = new RegExp("\n","g")
    content = content.replace(reg,'</p>')

    for (var i =0 ;i <images.length;i++){
      content +='<img src="' + images[i] +'" >'
    }
    console.log(content)
    
    WXRequest({
      url:'https://jsjoke.net/api/jokes',
      method:'POST',
      data:{content:content},
      success: function(res){
        //console.log(res)
        wx.switchTab({
          url: '../index/index'
        })
      }
    }) 
  },
  chooseImage: function (){
    var that = this
    wx.chooseImage({
      success: function (res){
        wx.uploadFile({
          url:'https://jsjoke.net/uploader/uploadimage?responseType=json',
          filePath: res.tempFilePaths[0],
          name:'upload',
          formData:{
          },
          success: function (res){
            var data = JSON.parse(res.data)
            var images = that.data.images;
            images.push(data["url"])
            that.setData({
              images:images
            })

          } //uploadfile success
        })
      } //choose image success
    })
  },
  onLoad: function (){
    var that = this
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
    })
  }
})
