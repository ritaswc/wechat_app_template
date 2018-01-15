var util = require('../../common/common.js');
let URLINDEX=util.prefix();
Page({
  data:{
    bg:URLINDEX+"/jmj/cart/line.png",
    img1:URLINDEX+"/jmj/cart/address.png",
    img2:URLINDEX+"/jmj/icon/add.png",
    img3:URLINDEX+"/jmj/cart/plane.png",
    checkState:true,
    imgcheck:URLINDEX+"/jmj/cart/red.png",
    imgcheckno:URLINDEX+"/jmj/cart/uncho.png",
  },
  checkS:function(){
    this.setData({
      checkState:!this.data.checkState
    })
  },
  onLoad:function(op){
    this.setData({
      id:op.id?op.id:null,
      num:op.num?op.num:null,
      type:op.type?op.type:null
    })
  },
  onShow:function(){
    var that=this;
      wx.request({
        url: util.pre()+'/apic/cart2',
        data: {
           token:util.code(), 
           id:that.data.id,
           num:that.data.num,
           type:that.data.type
        },
        success: function(res){
          var lastPay=(parseFloat(res.data.data.sum)+parseFloat(res.data.data.delivery_money)).toFixed(2);
          that.setData({
            order:res.data.data,
            lastPay:lastPay
          })
        }
      });
  }
})