var app = getApp()
var dialog = require("../../utils/dialog")

Page({
    data:{
        album:[],
        title:'',
        id:'',
        countShow:true,
        currentIndex:1
    },
    onLoad:function(options){
        this.setData({
            title:options.title,
            id:options.id.replace("##","."),
        })
        dialog.loading()
        //请求数据
        var that = this
        wx.request({
          url:app.globalData.api.albumBaseurl.replace("%id%",this.data.id),
          success:function(ret){
            ret = ret['data']
            if(ret['showapi_res_code'] == 0 && ret['showapi_res_body']){
              var imgList = ret['showapi_res_body']['imgList'];
              var imgObjList = [];
              imgList.forEach(function(item,index){
                imgObjList.push({
                      url:item,
                      w:750,
                      h:375
                })
              })
              that.setData({
                album:imgObjList,
                albumUrlList:imgList,
                total:imgList.length,
                loaded:0
              })
            }else{
              dialog.toast("网络出错啦~")
            }
          },
          complete:function(){
            setTimeout(function(){
              dialog.hide()
            },1000)
          }
        })
    },
    onReady:function(){
        wx.setNavigationBarTitle({title:this.data.title})
    },
    imageload:function(e){
      var h = e.detail.height
      var w = e.detail.width
      var index = e.currentTarget.dataset.index
       var album = this.data.album
        album[index].h = parseInt(750 * h / w)
        this.setData({
          album:album
        })
      
      
    },
    preiviewwImage(e){
      wx.previewImage({
        current:e.currentTarget.dataset.src,
        urls:this.data.albumUrlList
      })
    },
    swiperChange:function(e){
      this.setData({currentIndex:parseInt(e.detail.current)+1});
    },
    imageLongTap:function(e){
      wx.showActionSheet({
        itemList:['保存图片'],
        success:function(res){
          if(res.tapIndex == 0){
            var imageSrc = e.currentTarget.dataset.src
            console.log(imageSrc)
            wx.downloadFile({
              url: imageSrc, 
                    success: function(res) {
                      console.log(res)
                        wx.saveFile({
                          tempFilePath: res.tempFilePath,
                          success: function(res){
                            console.log(res.savedFilePath)
                            dialog.toast("保存成功")
                          },
                          fail: function(e) {
                            dialog.toast("保存出错")
                          }
                        })
                    },
                    fail:function(e){
                      dialog.toast("图片下载失败")
                    }
            })
          }
        }
      })
    },
    hideCount:function(){
      this.setData({countShow:false})
    }
})