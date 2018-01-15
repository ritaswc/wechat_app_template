var app = getApp()
var WxParse = require('../../../wxParse/wxParse.js');
Page({
  data: {
    detailgood: {},
    shoppingCartInfo: {
      goodId: "",
      goodImg: "",
      goodName: "",
      goodPrice: "",
      goodSum: 1,
      gooddetail: ''
    },
    current: 0,
    listgoods: [],
    swiper: {
      indicatorDots: false,
      autoplay: false,
      interval: 5000,
      duration: 1000
    },
    items: [
      { name: '0', value: '官方标配', checked: 'true' },
      { name: '1', value: '套餐一' },
      { name: '2', value: '套餐二' },
    ],
    listgood: []
  },
  radioChange: function (e) {
    console.log('radio发生change事件，携带value值为：', e.detail.value)
  },
  switchSlider: function (e) {
    this.setData({
      current: e.target.dataset.index
    })
  },
  changeSlider: function (e) {
    this.setData({
      current: e.detail.current
    })
  },
  onLoad: function (options) {
    var that = this;
    // 页面初始化 options为页面跳转所带来的参数
    var id = options.id;
    console.log(id)
    let list = this.data.listgood;
    console.log(list, '列表')
    wx.request({
      url: `${app.globalData.API_URL}/goods?id=` + id,
      data: {},
      method: 'GET',
      success: function (res) {
        console.log(res.data, 'getData')
        var article = res.data[0].goods_content
        /**
          * WxParse.wxParse(bindName , type, data, target,imagePadding)
          * 1.bindName绑定的数据名(必填)
          * 2.type可以为html或者md(必填)
          * 3.data为传入的具体数据(必填)
          * 4.target为Page对象,一般为this(必填)
          * 5.imagePadding为当图片自适应是左右的单一padding(默认为0,可选)
          */
        WxParse.wxParse('article', 'html', article, that, 5)

        var shoppingCartInfodata = {
          goodId: res.data[0].id,
          goodImg: res.data[0].goods_img,
          goodName: res.data[0].goods_name,
          goodPrice: res.data[0].market_price,
          shop_name: res.data[0].shop_name,
          goodSum: 1
        }
        console.log(shoppingCartInfodata)

        that.setData({
          shoppingCartInfo: shoppingCartInfodata
        })
        console.log(that.data.shoppingCartInfo)
      },

    })

    list.forEach(function (arr) {
      if (id == arr.id) {
        that.setData({
          detailgood: arr
        })
      }
    })

  },
  //加入购物车
  addToCar: function () {
    if (wx.getStorageSync('shoppingCartInfo')) {
      var arrInfo = wx.getStorageSync('shoppingCartInfo');
      var flag = true;
      for (var i = 0; i < arrInfo.length; i++) {
        if (this.data.shoppingCartInfo.goodId == arrInfo[i].goodId) {
          arrInfo[i].goodSum++;
          flag = false;
        }
      }
      if (flag) {
        arrInfo.push(this.data.shoppingCartInfo);
      }
    } else {
      var arrInfo = [];
      arrInfo.push(this.data.shoppingCartInfo);
    }
    wx.setStorageSync('shoppingCartInfo', arrInfo);
    wx.showModal({
      title: '添加购物车成功！',
      content: '',
      success: function (res) {
        if (res.confirm) {
          wx.switchTab({
            url: "../../shoppingCart/shoppingCart"
          })
        }
      }
    })
  },

})
