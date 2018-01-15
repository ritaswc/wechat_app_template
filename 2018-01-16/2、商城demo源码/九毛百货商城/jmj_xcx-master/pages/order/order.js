// pages/order/order.js
var util = require('../../common/common.js');
let URLINDEX=util.prefix();
Page({
  data:{
    empty:{
      emptyCat:URLINDEX+"/jmj/icon/cat_car.png",
      text:"暂无订单消息哦"
    },
    class1:"item active",
    class2:"item",
    page:0,
    orderState:[
      {
        id:0,
        state:"全部订单"
      },
      {
        id:1,
        state:"待支付"
      },
      {
        id:2,
        state:"待发货"
      },
      {
        id:3,
        state:"待收货"
      },
      {
        id:4,
        state:"已完成"
      }
    ]
  },
  changeState:function(e){
    var index=e.currentTarget.dataset.index;
    this.setData({
         id:index,
          page:0,
          orderList:[]
    })
  },
  onLoad:function(options){
    let that=this;
    this.setData({
      id:options.cid
    });
    wx.setNavigationBarTitle({
      title:options.title
    });
    getOrder(that);
  }
})
 function getOrder(that) {
   wx.request({
     url: util.pre()+'/apic/order_list',
     data: {
       token:util.code(),
       class:that.data.id,
       page:++that.data.page,
     },
     success: function(res){
       console.log(res)
       that.setData({
         orderList:res.data.data,
       })
     }
   });
 }