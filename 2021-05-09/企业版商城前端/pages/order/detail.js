var app = getApp();
// pages/order/detail.js
Page({
  data:{
    orderId:0,
    orderData:{},
  },
  onLoad:function(options){
    console.log(options);
    this.setData({
      orderId: options.orderId,
    })
    this.loadProductDetail();
  },
  loadProductDetail:function(){
    var that = this;

    wx.request({
      url: app.d.hostUrl + '/ztb/orderZBT/OrderDetilZBT',
      method:'post',
      data: {
        orderId: that.data.orderId,
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        console.log(res)
        //--init data        
        var data = res.data.data[0];
        console.log(data);
        that.initProductData(data);
        that.setData({
          orderData: data,
        });
        //endInitData
      },
    });
  },
  initProductData: function(data){
    // data["LunBoProductImageUrl"] = [];

    // var imgs = data.LunBoProductImage.split(';');
    // for(let url of imgs){
    //   url && data["LunBoProductImageUrl"].push(app.d.hostImg + url);
    // }

    // data.Price = data.Price/100;
    // data.VedioImagePath = app.d.hostImg + '/' +data.VedioImagePath;
    // data.videoPath = app.d.hostImg + '/' +data.videoPath;
    data.ImgUrl = app.d.hostImg + data.ImgUrl;
    data.Price = data.Price/100;
  },
  onShareAppMessage: function () {
    return {
      title: '微信小程序联盟',
      desc: '最具人气的小程序开发联盟!',
      path: '/page/user?id=123'
    }
  },
})