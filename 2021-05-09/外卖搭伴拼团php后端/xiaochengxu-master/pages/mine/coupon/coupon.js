// pages/coupon/coupon.js
var app = getApp();
Page({
  data:{
    couponList : {}
  },
  isOrder : false,
  onLoad:function(options){
    if(options.isOrder !== undefined){
      this.isOrder = true;
      this.order_amount = options.order_amount;
    } 
    this.uid = wx.getStorageSync('uid')
    this.getCoupon();;
  },
  getCoupon : function(){
    var page = this;
    wx.request({
      url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/User/getCoupon',
      data: {data:JSON.stringify({"uid":this.uid,"openid":null})},
      method: 'POST',
      header: {
          'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){console.log(res.data.data)
        //wx.hideToast();
        page.setData({couponList:res.data.data})
      },
      fail: function(res) {
        // fail
      }
    })
  },
  selectedCoupon : function(e){
    var coupon_id = e.currentTarget.dataset.couponid;
    if(this.isOrder){
      // wx.setStorage({
      //   key: 'coupon_id',
      //   data: coupon_id,
      //   success: function(res){ 
      //     wx.navigateBack()
      //   }
      // })
      var coupon = [];
      var couponList = this.data.couponList;
      for(var i=0;i<couponList.length;i++){
        if(couponList[i]['coupon_id'] == coupon_id){
          coupon = couponList[i];
          break;
        }
      }
      if(coupon){
        if(coupon.min_order_amount > this.order_amount){
          wx.showToast({
            title : '最低消费 '+ coupon.min_order_amount + '元 才能使用'
          })
          return ;
        }
        wx.setStorageSync('coupon', {coupon_id:coupon_id,coupon_money:coupon.type_money});
      }
      wx.navigateBack();
    }
  },
  backHome : function(e){
    app.backHome();
  }
})