
var app = getApp();

Page({
  data : {
    orderList : {},
    active : 0
  },
  onLoad : function(options){
    this.uid = wx.getStorageSync('uid');
    this.loadOrderList();
  },
  loadOrderList : function(){
    var post = {
      uid : this.uid,
      openid : null
    }
    if(arguments.length>0){
      post.pay_status = arguments[0];
    }console.log(post)
    var page = this;
    wx.request({
      url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/order/orderList',
      data: {data:JSON.stringify(post)},
      method: 'POST',
      header: {
          'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){console.log(res.data.data)
         page.setData({orderList:res.data.data})
      },
      fail: function(res) {
        // fail
      }
    })
  },
  cancelOrder : function(e){
    var page = this;
    var order_sn = e.currentTarget.dataset.ordersn;
    wx.showModal({
      title: '确认取消订单吗！',
      success: function(res) {
        if (res.confirm) {
            wx.request({
              url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Order/calOrder',
              data: {data:JSON.stringify({"uid":this.uid,"openid":null,"order_sn":order_sn})},
              method: 'POST',
              header: {
                  'content-type': 'application/x-www-form-urlencoded'
              },
              success: function(res){
                wx.showToast({
                  title : '已取消',
                  icon : 'success'
                })
                var orderList = page.data.orderList;
                for(var i=0;i<orderList.length;i++){
                    if(orderList[i]['order_sn'] == order_sn){
                      orderList.splice(i,1);
                    }
                }
                page.setData({orderList:orderList})
              }
            })
        } 
      }
    })
  },
  pay : function(e){
    var order_id = e.currentTarget.dataset.id;
    var orderList = this.data.orderList;
    for(var i=0;i<orderList.length;i++){
      if(orderList[i]['order_id'] == order_id){
        wx.setStorage({
          key: 'orderInfo',
          data: {
              goods_name:orderList[i].goods_name,
              address : orderList[i].address,
              consignee : orderList[i].consignee,
              tel : orderList[i].tel,
              order_sn : orderList[i].order_sn,
              order_amount : orderList[i].order_amount
          },
          success: function(res){
            wx.navigateTo({
              url: '/pages/order/pay/pay'
            })
          }
        })
      }
    }
  },
  showDetail : function(e){
    var order_id = e.currentTarget.dataset.orderid;
    wx.navigateTo({
      url: '/pages/mine/order/info/info?order_id=' + order_id
    })
  }, 
  changeType : function(e){
    var type = e.currentTarget.dataset.type;
    if(type == 'all'){
        this.setData({active:0});
        this.loadOrderList();
    }else if(type == 'nopay'){
        this.setData({active:1});
        this.loadOrderList(1);
    }else{
        this.setData({active:2});
        this.loadOrderList(2);
    }
  },
  backHome : function(e){
    app.backHome();
  }
})