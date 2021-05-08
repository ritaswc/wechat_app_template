
var app = getApp();
Page({
  data : {
    goodsInfo : {},
    userAddress : {},
    num : 1,
    isLow : false,
    goodsAmount : 0,
    hasCoupon : false,
    coupon_id : '',
    coupon : ''
  },
  onLoad : function(options){
    //  wx.setStorage({key:'coupon_id',data:''});
    this.group_id = options.group_id;
    wx.setStorageSync('coupon', {});
    this.uid = wx.getStorageSync('uid');
    
    
  },
  onShow : function(){
    // var page = this;  
    // wx.getStorage({
    //   key: 'coupon_id',
    //   success: function(res){
    //     page.coupon_id = res.data
    //   }
    // })
    this.getOrder();
    var coupon = wx.getStorageSync('coupon');
    if(coupon){
      if(coupon.coupon_id && coupon.coupon_money){console.log(this.data.goodsAmount, coupon.coupon_money)
        var goods_amount = parseFloat(this.data.goodsAmount) - parseFloat(coupon.coupon_money);console.log(goods_amount)
        this.setData({coupon_id:coupon.coupon_id,coupon:coupon.coupon_money,goodsAmount:goods_amount});
      }
    }
  },
  changeAddress : function(){
    wx.navigateTo({
      url: '/pages/mine/address/list/list'
    })
  },
  pay : function(e){
    if(this.data.userAddress.address_id == undefined){
      wx.navigateTo({
        url: '/pages/mine/address/add/add'
      })
      return ;
    }
    // wx.setStorageSync('uid', 47);
    // if(!wx.getStorageSync('uid')){
    //   wx.navigateTo({
    //     // url: '/pages/mine/login/login'
    //   })
    //   return ;
    // }

    // if(!this.data.isLow){
    //   var goods_amount = this.data.goodsInfo.goods_now_price;
    // }else{
    //   var goods_amount = this.data.goodsInfo.goods_low_price;
    // }
    var goods_amount = this.data.goodsAmount;

    var order_amount = goods_amount*this.data.num;
    this.order_amount = order_amount;


    var order = {
            uid: this.uid,
            openid : null,
            address: this.data.userAddress.province + this.data.userAddress.address,
            consignee: this.data.userAddress.consignee,
            tel: this.data.userAddress.tel,
            goods_amount: goods_amount,
            group_id: this.group_id,
            group_num: this.data.num,
            coupon_id: this.data.coupon_id,
            coupon: this.data.coupon,
            order_amount: order_amount,
            pay_type: '微信小程序',
            goods_id: this.data.goodsInfo.goods_id,
            group_off_time : wx.getStorageSync('endTime')
         }

     var page = this;
      wx.request({
        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Order/addOrder',
        data: {data:JSON.stringify(order)},
        method: 'POST',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        success: function(res){console.log(res)
          if(res.data.data.status == -5){
            wx.showToast({
              title : res.data.data.msg
            })
            return ;
          }
          var order_sn = res.data.data.order_sn;
// console.log({
//                 goods_name:page.data.goodsInfo.goods_name,
//                 address : order.address,
//                 consignee : order.consignee,
//                 tel : order.tel,
//                 order_sn : order_sn,
//                 order_amount : order.order_amount,
//                 group_id : page.data.goodsInfo.group_id
//             });return;
          wx.setStorage( {
            key : 'orderInfo',
            data : {
                goods_name:page.data.goodsInfo.goods_name,
                address : order.address,
                consignee : order.consignee,
                tel : order.tel,
                order_sn : order_sn,
                order_amount : order.order_amount,
                group_id : page.group_id
            },
            success : function(res){
              wx.navigateTo({
                url: '/pages/order/pay/pay'
              })
            }
          })
          


          // wx.navigateTo({
          //    url: '/pages/order/pay/pay'
          // })
          // wx.setStorageSync('orderInfo', {
          //     goods_name:page.data.goodsInfo.goods_name,
          //     address : order.address,
          //     consignee : order.consignee,
          //     tel : order.tel,
          //     order_sn : order_sn,
          //     order_amount : 0.01//order.order_amount
          //   });
        },
        fail: function(res) {
          // fail
        }
      })    
  },
  optionNum : function(e){
    var goods_low_num = this.data.goodsInfo.goods_low_num,
        goods_low_price = this.data.goodsInfo.goods_low_price,
        goods_now_price = this.data.goodsInfo.goods_now_price,
        goods_num = this.data.goodsInfo.goods_num,
        group_person = this.data.goodsInfo.group_person,
        type = e.currentTarget.dataset.type,
        goods_price = parseFloat(goods_now_price);
        if(this.data.coupon_id && this.data.coupon){
          goods_low_price -= this.data.coupon;
          goods_now_price -= this.data.coupon;
        }
        if(type == 'plus'){
          this.data.num++;
        }else{
          this.data.num > 1 ? this.data.num-- : this.data.num == 1;
        }
        if(goods_low_num > 0){
          if((group_person*1.0 + this.data.num) > goods_low_num){
              goods_price = parseFloat(goods_low_price);
          }else{
              goods_price = parseFloat(goods_now_price); 
          }
        }
        this.setData({goodsAmount:goods_price,num:this.data.num})
  },
  getOrder : function(){
    // wx.showToast({
    //   title : '加载中',
    //   mask : true
    // })
    var goods_id = wx.getStorageSync('goods_id'),
        group_off_time = wx.getStorageSync('endTime');
        
    var $data = {
      uid : this.uid,
      openid : null,
      goods_id : goods_id
    }
    console.log($data)
    if(this.group_id !== undefined){
      $data.group_id = this.group_id;
    }else{
      $data.group_off_time = group_off_time;
    }
    console.log($data)
    var page = this;
      wx.request({
        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Order/getOrder',
        data: {data:JSON.stringify($data)},
        method: 'POST',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        success: function(res){console.log(res.data)
          var coupon = wx.getStorageSync('coupon');
          if(!coupon.coupon_id && !coupon.coupon_money){
            page.setData({
                goodsAmount : parseFloat(res.data.data.goods_info.goods_now_price)
              })
          }
          page.setData({
            goodsInfo : res.data.data.goods_info,
            userAddress : res.data.data.user_address
          })
          page.getCoupon();
        },
        fail: function(res) {
          // fail
        }
      })
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
      success: function(res){
        //wx.hideToast();
        var couponList = res.data.data;console.log(couponList)
        if(couponList.length > 0) {
          page.setData({hasCoupon:true});
          page.couponList = couponList;
        }
      },
      fail: function(res) {
        // fail
      }
    })
  },
  selectCoupon : function(){
    wx.navigateTo({
      url: '/pages/mine/coupon/coupon?isOrder=1&order_amount=' + this.order_amount
    })
  },
  backHome : function(e){
    app.backHome();
  }
})