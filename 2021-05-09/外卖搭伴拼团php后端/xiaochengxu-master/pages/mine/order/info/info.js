
var app = getApp();

Page({
  data : {
    orderInfo : {}
  },
  onLoad : function(options){//options.order_id
    this.uid = wx.getStorageSync('uid');
    this.loadOrderDetail(options.order_id)
  },
  loadOrderDetail : function(order_id){
    var page = this;
      wx.request({
        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Order/orderInfo',
        data: {data:JSON.stringify({"uid":this.uid,"openid":null,"order_id":order_id})},
        method: 'POST',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        success: function(res){console.log(res.data);
          
          page.setData({orderInfo:res.data.data});
        }
      })
  },
  backHome : function(e){
    app.backHome();
  }
})