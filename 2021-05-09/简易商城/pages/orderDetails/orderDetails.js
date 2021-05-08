// pages/orderDetails/orderDetails.js
var md5 = require('../../utils/md5.js');
Page({
  data:{
    goods_total:''
  },
  getfid:function(e){
    this.setData({
      fid:e.detail.formId
    })
  },
  wxpay:function(){
    var that = this;
    wx.getStorage({
        key:"session",
        success: function(res){
          var session=res.data.session;
          var k = 'linlitegongshe123456789000000000';
          var url = 'https://shop.llzg.cn/weapp/WxpayAPI_php_v3/example/WxPay.php?session_id='+session+'&order_id='+that.data.order_id;
          wx.request({
            url: url,
            data:{},
            method: 'POST',
            header: {'content-type': 'application/x-www-form-urlencoded'},
            success: function(res){
              
              if(res.data.result_code == 'SUCCESS'){
                // 发起支付
                var appId = res.data.appid;
                var timeStamp = (Date.parse(new Date()) / 1000).toString();
                var pkg = 'prepay_id=' + res.data.prepay_id;
                var nonceStr = res.data.nonce_str;
                var xcxsign = res.data.sign;
                var paySign = md5.hex_md5('appId='+appId+'&nonceStr='+nonceStr+'&package='+pkg+'&signType=MD5&timeStamp='+timeStamp+"&key=linlitegongshe123456789000000000").toUpperCase();
                wx.requestPayment({
                  'timeStamp': timeStamp,
                  'nonceStr': nonceStr,
                  'package': pkg,
                  'signType': 'MD5',
                  'paySign': paySign,
                  'success':function(res){
                    wx.request({
                      url:'https://shop.llzg.cn/weapp/donepay.php?act=success&order_id='+that.data.order_id+'&session_id='+session+'&form_id='+that.data.fid,
                      data:{},
                      method: 'POST',
                      header: {'content-type': 'application/x-www-form-urlencoded'},
                      success: function(res){
                        wx.navigateBack();
                      }
                    })
                  },
                  'fail':function(res){
                    console.log('order_id='+that.data.order_id+'&form_id='+that.data.fid)
                  }
                });
              }else{
                console.log("服务器故障，请稍后重试！");
              }
            }
          })
        },
        fail: function(res) {
          console.log("用户登录未登录，获取地址失败!")
        }
      })
  },
  buyAgain:function(e){
    wx.navigateTo({
      url:'../details/details?id='+e.currentTarget.dataset.oid
    })
  },
  onLoad:function(options){
    wx.setNavigationBarTitle({
      title: "订单详情"
    })
    var that = this;
    var _url = 'https://shop.llzg.cn/weapp/showorders.php?act=detail&order_id='+options.order_id
    wx.getStorage({
      key:"session",
      success: function(res){
        var session=res.data.session;
        var url = _url+'&session_id='+session;
        wx.request({
          url: url,
          data:{},
          method: 'POST',
          header: {'content-type': 'application/x-www-form-urlencoded'},
          success: function(res){
            that.setData({
              orderInf:res.data,
              order_id:res.data.order_id,
              goods_total:res.data.total_fee.toFixed(2)
            })
          }
        })
      },
      fail: function(res) {
        console.log("用户登录未登录!")
      }
    })
  }
})