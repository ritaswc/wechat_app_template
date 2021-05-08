//index.js
var util = require('../../utils/util.js')
var app = getApp()

Page({
  onShareAppMessage: function () {//分享
    return {
      title:'找货就上定制链商城',
      path: '/pages/index/index'
    }
  },
  data: {
    ctx: util.ctx,
    weixinCtx: util.weixinCtx,
    chaImgSrc: '',
    isShow: false,
    styleName: '',
    imageUrl: ''
  },
  onLoad: function () {
    var that = this;

    wx.login({
        success: function (e) {
          console.log(e)
        }
    })

    util.requestSupply("getSupplierProduct", "",
      function (res) {
        that.setData({
          productSorts: res.results
        });
      }, function (res) {
        console.log(res);
      });
  },
  onShow: function () {
    //调用API从本地缓存中获取数据
    this.setData({
      history: wx.getStorageSync('searchStyle') || []
    })
  },
  styleName: function (e) {
    this.setData({
      styleName: e.detail.value
    })
  },
  searchStyle: function (e) {
    var that = this
    var style = that.data.styleName, image = that.data.imageUrl, chaImgSrc = that.data.chaImgSrc;
    wx.navigateTo({
      url: 'searchpros/searchpros?style=' + style + '&imageUrl=' + image + '&chaImgSrc=' + chaImgSrc
    })
    //保存搜索记录
    if (style != null && style != '') {
      var searchStyle = wx.getStorageSync('searchStyle') || []
      searchStyle.unshift(style)

      wx.setStorageSync('searchStyle', util.getArray(searchStyle))
    }

  },
  chooseImageTap: function () {
    var that = this;
    wx.showActionSheet({
      itemList: ['从相册中选择', '拍照'],
      itemColor: "#f7982a",
      success: function (res) {
        if (!res.cancel) {
          if (res.tapIndex == 0) {
            that.chooseWxImage('album')
          } else if (res.tapIndex == 1) {
            that.chooseWxImage('camera')
          }
        }
      }
    })
  },
  chooseWxImage: function (type) {
    var that = this;
    wx.chooseImage({
      count: 1,
      sizeType: ['original'], // 可以指定是原图还是压缩图，默认二者都有  
      sourceType: [type], // 可以指定来源是相册还是相机，默认二者都有  
      success: function (res) {
        var picPath = res.tempFilePaths[0];
        // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片  
        that.setData({
          chaImgSrc: picPath,
          isShow: true
        })
        //图片上传到定制链服务器进行款式搜索
        util.uploadFile(picPath,
          function (path) {//path为定制链图片链接
            that.setData({
              imageUrl: path
            })
            var style = that.data.styleName
            wx.navigateTo({
              url: 'searchpros/searchpros?style=' + style + '&imageUrl=' + path + '&chaImgSrc=' + picPath
            })
          })
      }
    })
  },
  deleteImage: function () {
    this.setData({
      isShow: false,
      imageUrl: '',
      chaImgSrc: ''
    })
  },
  //删除搜索记录
  removeHistory: function () {
    var that = this;
    wx.showModal({
      content: '删除历史记录?',
      success: function (res) {
        if (res.confirm) {
          wx.removeStorage({
            key: 'searchStyle',
            success: function (res) {
              that.setData({
                history: []
              })
            }
          })
        }
      }
    })

  }
})


