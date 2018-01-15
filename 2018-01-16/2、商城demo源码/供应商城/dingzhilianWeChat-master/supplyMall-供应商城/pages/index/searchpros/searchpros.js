var util = require('../../../utils/util.js')
var page = 1
var app = getApp()

//获取款式信息列表
var GetList = function (that) {
  page = 1
  that.setData({
    hidden: false,
    scrollTop: 0
  });
  var style = that.data.styleName, image = that.data.imageUrl, params, method = that.data.method, productId = that.data.productId;

  if (productId != null) {
    params = "?id=" + productId + "&pageNumber=" + page;
  } else {
    params = "?style=" + style + "&picture=" + image + "&pageNumber=" + page;
  }
  util.requestSupply(method, params,
    function (res) {
      var reqList = res.pageResults.list;
      if (reqList != null && reqList.length > 0) {
        that.setData({
          list: reqList,
          emptyShow: false,
        });
        page = 2;
      } else {
        that.setData({
          list: [],
          scrollTop: 0,
          emptyShow: true
        });
      }
      that.setData({
        hidden: true
      });
    }, function (res) {
      console.log(res);
    });
}

//上拉获取更多款式信息
var loadMore = function (that) {
  var style = that.data.styleName, image = that.data.imageUrl, params, method = that.data.method, productId = that.data.productId;

  if (productId != null) {
    params = "?id=" + productId + "&pageNumber=" + page;
  } else {
    params = "?style=" + style + "&picture=" + image + "&pageNumber=" + page;
  }
  util.requestSupply(method, params,
    function (res) {
      var scrollHeight = that.data.scrollHeight, scrollTop = that.data.scrollTop;
      var reqList = res.pageResults.list;
      if (reqList != null && reqList.length > 0) {
        var listNew = that.data.list.concat(reqList);

        that.setData({
          list: listNew,
          hasMore: true,
          emptyShow: false
        });
        page++;
      } else {
        that.setData({
          hasMore: false
        });
      }
    }, function (res) {
      console.log(res);
    });
}

Page({
  onShareAppMessage: function () {//分享
    return {
      title:'找货就上定制链商城',
      path: '/pages/index/searchpros/searchpros?style=&imageUrl=&chaImgSrc='
    }
  },
  data: {
    ctx: util.ctx,
    weixinCtx: util.weixinCtx,
    isShow: false,
    emptyShow: false,
    hasMore: true,
    styleName: '',
    imageUrl: '',

    method: 'searchStyle',
    productId: null,
    hidden: true,
    scrollTop: 0,
    scrollHeight: 0,
    list: []
  },

  onLoad: function (option) {
    var that = this;
    wx.getSystemInfo({
      success: function (res) {
        that.setData({
          scrollHeight: res.windowHeight
        });
      }
    });

    if (option.id != undefined) {//从产品分类点击
      that.setData({
        styleName: option.style,
        method: 'getSupplierProductDetail',
        productId: option.id
      })
    } else {//点击历史记录和直接搜索
      var chaImgSrc = option.chaImgSrc
      if (option.chaImgSrc != null && option.chaImgSrc != "") {
        that.setData({
          chaImgSrc: chaImgSrc,
          imageUrl: option.imageUrl,
          isShow: true
        })
      }
      that.setData({
        styleName: option.style,
        method: 'searchStyle'
      })
    }

    GetList(that);
  },
  bindDownLoad: function () {
    //  该方法绑定了页面滑动到底部的事件
    var that = this
    //图片搜索不进行上拉加载请求
    var image = that.data.imageUrl
    if (image != '') {
      that.setData({
        hasMore: false
      });
      return;
    } else {
      that.setData({
        hasMore: true
      });
    }
    loadMore(that);
  },
  scroll: function (event) {
    //  该方法绑定了页面滚动时的事件，这里记录了当前的position.y的值,为了请求数据之后把页面定位到这里来。
    this.setData({
      scrollTop: event.detail.scrollTop
    });
  },
  styleName: function (e) {
    this.setData({
      styleName: e.detail.value
    })
  },
  searchStyle: function (e) {
    page = 1;
    var that = this;
    var style = that.data.styleName, image = that.data.imageUrl, show = that.data.isShow;
    if (!show) {//如果图片被X掉则置空
      image = ''
      that.setData({
        imageUrl: ''
      })
    }
    that.setData({
      list: [],
      scrollTop: 0,
      productId: null,
      hasMore: true,
      hidden: false,
      emptyShow: false,
      method: 'searchStyle'
    })

    //保存搜索记录
    if (style != null && style != '') {
      var searchStyle = wx.getStorageSync('searchStyle') || []
      searchStyle.unshift(style)
      wx.setStorageSync('searchStyle', util.getArray(searchStyle))
    }

    util.requestSupply("searchStyle", "?style=" + style + "&picture=" + image + "&pageNumber=" + page, function (res) {
      var reqList = res.pageResults.list;
      if (reqList != null && reqList.length > 0) {
        that.setData({
          list: reqList,
          emptyShow:false
        });
        page = 2;
      } else {
        that.setData({
          list: [],
          emptyShow: true
        });
      }
      that.setData({
        hidden: true
      })
    }), function (res) {
      console.log(res)
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
            page = 1;
            that.setData({
              list: [],
              method: 'searchStyle',
              imageUrl: path,
              hidden: false
            })
            var style = that.data.styleName
            util.requestSupply("searchStyle", "?style=" + style + "&picture=" + path + "&pageNumber=" + page, function (res) {
              var req = res.pageResults.list;
              if (req != null && req.length > 0) {
                that.setData({
                  list: req,
                  hidden: true,
                  hasMore: false,
                  emptyShow:false
                });
              } else {
                that.setData({
                  list: [],
                  hidden: true,
                  hasMore: false,
                  emptyShow:true
                });
              }
            })
          },
          function (message) {
            console.log(message);
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
  }

})