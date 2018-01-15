var app = getApp();
Page({
  data: {
    imageCtx: app.globalData.imageCtx,
    hidden: false,
    emptyShow: false,
    emptySizeShow: false,
    styleName: '',
    supplierId: '',
    styleId: '',
    secondId: '',
    detailId: '',
    supplier: [],
    style: [],
    styleSecond: [],
    styleColor: [],
    styleSize: [],
    supplierIndex: 0,
    styleIndex: 0,
    styleSecondIndex: 0,
    styleColorIndex: 0

  },
  onLoad: function () {
    var that = this, adminObj = app.globalData.adminObj;
    that.setData({
      params: {
        phone: adminObj.phone,
        password: app.globalData.password,
        sessionId: adminObj.sessionId
      }
    })
    that.getSupplier(that);
  },
  styleInput: function (e) {
    this.setData({
      styleName: e.detail.value
    })
  },
  srarchStyle: function () {
    var that = this;
    that.setData({
      hidden: false
    })
    that.getStyle(that);
  },
  //获取供应商列表
  getSupplier: function (that) {
    wx.request({
      url: app.globalData.requestUrl + 'weixinMerchant/stockForSupplier',
      data: that.data.params,
      success: function (res) {
        if (res.data.code == '0') {
          var supplier = res.data.results;
          if (supplier != null && supplier.length > 0) {
            that.setData({
              supplier: supplier,
              supplierId: supplier[0].id,
              supplierIndex: 0
            })
            that.getStyle(that);
          } else {
            that.setData({
              emptyShow: true,
              hidden: true
            })
          }
        } else {
          that.setData({
            hidden: true
          })
          app.noLogin(res.data.msg);
        }
      },
      //第一次请求失败就给出提示
      fail: function (res) {
        that.setData({
          hidden: true
        })
        app.warning("服务器无响应");
      }
    })
  },
  //获取款式列表
  getStyle: function (that) {
    var params = that.data.params, styleName = that.data.styleName, reg = /^\s*|\s*$/g;
    styleName = styleName.replace(reg, '');
    params.supplierId = that.data.supplierId;
    params.styleName = styleName;
    wx.request({
      url: app.globalData.requestUrl + 'weixinMerchant/stockForStyle',
      data: params,
      success: function (res) {
        if (res.data.code == '0') {
          that.setData({
            styleName: styleName
          })
          var style = res.data.results;
          if (style != null && style.length > 0) {
            that.setData({
              emptySizeShow: false,
              style: style,
              styleId: style[0].id,
              styleIndex: 0
            })
            that.getStyleSecond(that);
          } else {
            //无款式信息和带名称查询后无款式时置空供应商下面的显示的所有款式信息
            that.setData({
              emptySizeShow: true,
              style: [],
              styleId: '',
              styleIndex: 0,
              styleSecond: [],
              secondId: '',
              styleSecondIndex: 0,
              styleColor: [],
              detailId: '',
              styleColorIndex: 0,
              styleSize: [],
              hidden: true
            })
          }
        } else {
          that.setData({
            hidden: true
          })
          app.noLogin(res.data.msg);
        }
      }
    })
  },
  //获取款式二级列表
  getStyleSecond: function (that) {
    var params = that.data.params;
    params.styleId = that.data.styleId;
    wx.request({
      url: app.globalData.requestUrl + 'weixinMerchant/stockForStyleSecond',
      data: params,
      success: function (res) {
        if (res.data.code == '0') {
          var styleSecond = res.data.results;
          if (styleSecond != null && styleSecond.length > 0) {
            that.setData({
              emptySizeShow: false,
              styleSecond: styleSecond,
              secondId: styleSecond[0].id,
              styleSecondIndex: 0
            })
            that.getStyleColor(that);
          } else {
            that.setData({
              emptySizeShow: true,
              styleSecond: [],
              secondId: '',
              styleSecondIndex: 0,
              styleColor: [],
              detailId: '',
              styleColorIndex: 0,
              styleSize: [],
              hidden: true
            })
          }
        } else {
          that.setData({
            hidden: true
          })
          app.noLogin(res.data.msg);
        }
      }
    })
  },
  //获取款式二级颜色列表
  getStyleColor: function (that) {
    var params = that.data.params;
    params.styleId = that.data.styleId;
    params.secondId = that.data.secondId;
    wx.request({
      url: app.globalData.requestUrl + 'weixinMerchant/stockForStyleColor',
      data: params,
      success: function (res) {
        if (res.data.code == '0') {
          var styleColor = res.data.results;
          if (styleColor != null && styleColor.length > 0) {
            that.setData({
              emptySizeShow: false,
              styleColor: styleColor,
              detailId: styleColor[0].id,
              styleColorIndex: 0
            })
            that.getSizeStore(that);
          } else {
            that.setData({
              emptySizeShow: true,
              styleColor: [],
              detailId: '',
              styleColorIndex: 0,
              styleSize: [],
              hidden: true
            })
          }
        } else {
          that.setData({
            hidden: true
          })
          app.noLogin(res.data.msg);
        }
      }
    })
  },
  //获取款式二级颜色尺码列表
  getSizeStore: function (that) {
    var params = that.data.params;
    params.detailId = that.data.detailId;
    wx.request({
      url: app.globalData.requestUrl + 'weixinMerchant/stockForStyleSize',
      data: params,
      success: function (res) {
        that.setData({
          hidden: true
        })
        if (res.data.code == '0') {
          var sizeStore = res.data.results;
          if (sizeStore != null && sizeStore.length > 0) {
            that.setData({
              styleSize: sizeStore,
              emptySizeShow: false
            })
          } else {
            that.setData({
              styleSize: [],
              emptySizeShow: true
            })
          }
        } else {
          app.noLogin(res.data.msg);
        }
      }
    })
  },
  bindPickerChange1: function (e) {
    console.log('picker发送选择改变，切换供应商,携带值为', e.detail.value)
    var that = this, supplier = that.data.supplier, supplierIndex = e.detail.value;
    that.setData({
      hidden: false,
      supplierIndex: supplierIndex,
      supplierId: supplier[supplierIndex].id
    })
    that.getStyle(that);
  },
  bindPickerChange2: function (e) {
    console.log('picker发送选择改变，切换款式,携带值为', e.detail.value)
    var that = this, style = that.data.style, styleIndex = e.detail.value;
    that.setData({
      hidden: false,
      styleIndex: styleIndex,
      styleId: style[styleIndex].id
    })
    that.getStyleSecond(that);
  },
  bindPickerChange3: function (e) {
    console.log('picker发送选择改变，切换款色二级,携带值为', e.detail.value)
    var that = this, styleSecond = that.data.styleSecond, styleSecondIndex = e.detail.value;
    that.setData({
      hidden: false,
      styleSecondIndex: styleSecondIndex,
      secondId: styleSecond[styleSecondIndex].id
    })
    that.getStyleColor(that);
  },
  bindPickerChange4: function (e) {
    console.log('picker发送选择改变，切换款式颜色,携带值为', e.detail.value)
    var that = this, styleColor = that.data.styleColor, styleColorIndex = e.detail.value;
    that.setData({
      hidden: false,
      styleColorIndex: styleColorIndex,
      detailId: styleColor[styleColorIndex].id
    })
    that.getSizeStore(that);
  }
})