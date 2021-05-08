// pages/evaluate/evaluate.js
Page({
  data: {
    o_id: '',
    evalength: 0,
    evaContent: '',
    tabArr: '',
    tabArr1: '',
    tabArr2: '',
    strnumber: '',
    strnumber1: '',
    strnumber2: '',
    getcar_score: 5,
    sendcar_score: 5,
    point_score: 5,
    contents: '',
    word: ['很差', '差', '一般', '好', '很好']
  },
  onLoad: function (options) {
    this.setData({
      o_id: options.o_id
    })
  },
  chooseicon: function (e) {

    var strnumber = e.target.dataset.id;

    var _obj = {};
    _obj.curHdIndex = strnumber;
    this.setData({
      tabArr: _obj,
      strnumber: strnumber
    });

  },
  chooseicon1: function (e) {

    var strnumber = e.target.dataset.ids;
    var _obj = {};
    _obj.curHdIndex = strnumber;
    this.setData({
      tabArr1: _obj,
      strnumber1: strnumber
    });

  },
  chooseicon2: function (e) {

    var strnumber = e.target.dataset.idss;
    var _obj = {};
    _obj.curHdIndex = strnumber;
    this.setData({
      tabArr2: _obj,
      strnumber2: strnumber
    });

  },
  charChange: function (e) {
    console.log(e.detail.value);
    console.log(e.detail.value.length);
    this.setData({
      evaContent: e.detail.value,
      evalength: e.detail.value.length
    });

  },
  bindsubmint: function () {
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/comment/write-comment',
      data: {
        openid: wx.getStorageSync('openid'),
        order_id: this.data.o_id,
        getcar_score: this.data.strnumber,
        sendcar_score: this.data.strnumber1,
        point_score: this.data.strnumber2,
        contents: this.data.evaContent,
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        wx.redirectTo({
          url: '../index/index',

        })

      }
    });
  }
})