// pages/chew/chew.js
var app = getApp();

Page({
  data : {
    tenant : {},
    goodsData : {}
  },
  onLoad : function(options){
    // wx.showLoading({
    //   title : '加载中',
    //   mask : true
    // })
    this.loadGoods(options.mid);
  },
  loadGoods : function(mid){
      var page = this;
      wx.request({
        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Goods/tenant',
        data: {data:JSON.stringify({"mid":mid})},
        method: 'POST',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        success: function(res){console.log(res.data.data)
          page.setData({goodsData:res.data.data.goodsInfo,tenant:res.data.data.tenant})
          // wx.hideLoading()
        },
        fail: function(res) {
          // fail
        }
      })
  },
  //商品详情页
  showGoods : function(e){
    var goods_id = e.currentTarget.id;
    wx.navigateTo({
      url: '../goods/goods?goods_id=' + goods_id
    })
  },
  backHome : function(e){
    app.backHome();
  }
})