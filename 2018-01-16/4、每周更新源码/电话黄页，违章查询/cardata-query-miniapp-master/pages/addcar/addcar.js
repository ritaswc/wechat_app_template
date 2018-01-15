// pages/addcar/addcar.js

var app = getApp();
Page({
  data:{
    btn: {
      disabled: false,
      loading: false
    },
    carData: {

    }
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var carlist = wx.getStorageSync('carlist');
    this.setData({
      carlist: carlist
    })
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
  testcarnumber: function(e) {
    var val = e.detail.value
    if ( !app.testCarnumber(val) ) {
      wx.blackTip.call(this, "请输入正确的车牌号码");
      return;
    }
  },
  testcarcode: function(e) {

    var val = e.detail.value
    if ( !app.testCode(val) ) {
      wx.blackTip.call(this, "请输入正确的车架号");
    }
  },
  testcardrivenumber: function(e) {
    var val = e.detail.value
    if ( !app.testCode(val) ) {
      wx.blackTip.call(this, "请输入正确的发动机号");
    }
  },


  setCarnumber: function(e) {
    this.setData({
      'carData.carnumber': e.detail.value
    });
  },
  setCarcode: function(e) {
    this.setData({
      'carData.carcode': e.detail.value.toUpperCase()
    });
  },
  setCardrivenumber: function (e) {
    this.setData({
      'carData.cardrivenumber': e.detail.value.toUpperCase()
    });
  },


  confirm: function() {
    var _this = this;
    var data = this.data.carData;
    var carnumber_flag = app.testCarnumber(data.carnumber);
    var carcode_flag = app.testCode(data.carcode);
    var cardrivenumber_flag = app.testCode(data.cardrivenumber);

    if (!carnumber_flag || !carnumber_flag || !carnumber_flag) {
      wx.blackTip.call(this, "请输入正确的车辆信息");
      return;
    }
    this.setData({
      'btn.loading': true,
      'btn.disabled': true
    });

    wx.request({
      url: 'https://comments.cx580.com/illegal/index?c=illegal&a=query',
      data: {
        appkey: app.globalData._appkey,
        carcode: data.carcode,
        carnumber: data.carnumber,
        cardrivenumber: data.cardrivenumber
      },
      header: {'content-type': 'application/json'},
      success: function(res) {

        if (res.data.status != 0 ) {
          wx.blackTip.call(_this, "添加失败，请检查您填写的信息是否正确");
          this.setData({
            'btn.loading': false,
            'btn.disabled': false
          });
          return;
        }
        console.log("请求成功", res);
        var formatData = _this.data.carData;
        formatData.result = res.data.result;

        wx.setStorageSync('allCarData', formatData);

        wx.showToast({
          title: '添加成功',
          icon: 'success',
          duration: 1500
        });
        setTimeout(function(){
          wx.navigateBack({
            delta: 1
          });
        }, 1500);
      },
      error: function (res) {
        console.error("请求失败", res);
        wx.blackTip.call(_this, "添加失败，请检查网络");
        _this.setData({
          'btn.loading': false,
          'btn.disabled': false
        });
      }
    })
  }
})