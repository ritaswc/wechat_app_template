// pages/order/pay/pay.js

var app = getApp();

Page({
  data:{
    order_amount : 0,
    orderInfo : {}
  },
  onLoad:function(options){
    //  this.setData({
    //    orderInfo : wx.getStorageSync('orderInfo')
    //  }) 
    var page = this;  
    wx.getStorage({
      key: 'orderInfo',
      success: function(res){console.log(res)
        page.setData({orderInfo:res.data})
      }
    })
  },
  pay : function(){
    var page = this;
    wx.login({
        success: function (res) {
          if(res.code){
            wx.request({
              url: 'https://daban2017.leanapp.cn/pay.php',
              data: {
                code : res.code,
                goods_name : page.data.orderInfo.goods_name,
                order_sn : page.data.orderInfo.order_sn,
                order_amount :  page.data.orderInfo.order_amount
              },
              method: 'POST',
              header: {
                'content-type': 'application/x-www-form-urlencoded'
              },
              success: function(response){
                // 发起支付
                wx.requestPayment({
                    'timeStamp': response.data.timeStamp,
                    'nonceStr': response.data.nonceStr,
                    'package': response.data.package,
                    'signType': 'MD5',
                    'paySign': response.data.paySign,
                    'success':function(res){
                        // wx.showToast({
                        //     title: '支付成功'
                        // });
                        var url = url = '/pages/order/done/done?order_sn=' + page.data.orderInfo.order_sn;
                        if(page.data.orderInfo.group_id != undefined){
                          url = '/pages/order/done/done?order_sn=' + page.data.orderInfo.order_sn + '&pinsucess=1';
                        }
                        wx.navigateTo({
                          url: url
                        })

                         console.log(res);
                    },
                    'fail' : function(res){
                      console.log('3333')
                      console.log(res)
                    }
                });
              },
              fail: function(res) {
                console.log('3333')
                console.log(res)
              }
            })
          }else{
            console.log('登录失败')
          }
        }
      })
  },
  backHome : function(e){
    app.backHome();
  }
})