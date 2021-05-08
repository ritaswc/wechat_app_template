// pages/orders/orders.js
var md5 = require('../../utils/md5.js');
Page({
  data:{
    userAddr:"",
    goodsInf:"",
    goods_total:"",
    userMsg:"",
    addr_flag:'',
    warnInf:'',
    count_flag:true,
    minusStatus:false
  },
  bindMinus:function(){
    var that = this;
    if(that.data.count_flag){
      that.setData({
        count_flag:false
      })
      setTimeout(function(){
        that.setData({
          count_flag:true
        })
      },1000)
      var num = that.data.goods_count;
      if(num>2){
        num--;
        wx.showToast({
          title: '计算总额中...',
          icon: 'loading',
          duration: 10000
        })
      }else if(num == 2){
        that.setData({
          minusStatus:false
        });
        num--;
        wx.showToast({
          title: '计算总额中...',
          icon: 'loading',
          duration: 10000
        })
      }
      var url = 'https://shop.llzg.cn/weapp/showgood.php?act=change&order_id='+that.data.order_id+'&goods_number='+num;
      wx.request({
        url: url,
        data:{},
        method: 'POST',
        header: {'content-type': 'application/x-www-form-urlencoded'},
        success: function(res){
          console.log(res);
          if(res.data.goods_number){
            setTimeout(function(){
              wx.hideToast();
              that.setData({
                goods_count:res.data.goods_number,
                goods_freight:res.data.yunfei,
                goods_amount:res.data.amount.toFixed(2),
                goods_total:res.data.total_fee.toFixed(2)
              })
            },300) 
          }else{
            that.myToast("服务器繁忙，请稍后再试！");
          }
        }
      })
    }else{
      that.myToast("您点击的太快了，休息一下再试！");
    }
  },
  bindPlus:function(){
    var that = this;
    if(that.data.count_flag){
      that.setData({
        count_flag:false
      })
      setTimeout(function(){
        that.setData({
          count_flag:true
        })
      },1000)
      wx.showToast({
        title: '计算总额中...',
        icon: 'loading',
        duration: 10000
      })
      var num = that.data.goods_count;
      num++;
      var url = 'https://shop.llzg.cn/weapp/showgood.php?act=change&order_id='+that.data.order_id+'&goods_number='+num;
      wx.request({
        url: url,
        data:{},
        method: 'POST',
        header: {'content-type': 'application/x-www-form-urlencoded'},
        success: function(res){
          console.log(res);
          if(res.data.goods_number){
            setTimeout(function(){
              wx.hideToast();
              that.setData({
                minusStatus:true,
                goods_count:res.data.goods_number,
                goods_freight:res.data.yunfei,
                goods_amount:res.data.amount.toFixed(2),
                goods_total:res.data.total_fee.toFixed(2)
              })
            },300) 
          }else{
            that.myToast("服务器繁忙，请稍后再试！");
          }
        }
      })
    }else{
      that.myToast("您点击的太快了，休息一下再试！");
    }  
  },
  bindUserMsg:function(e){
    console.log(e.detail.value);
    this.setData({
      userMsg:e.detail.value
    })
  },
  myToast:function(inf){
    var that = this;
    if(inf){
      that.setData({
        warnInf:inf
      });
      setTimeout(function(){
        that.setData({
          warnInf:'',
        });
      },2000);
    }
  },
  wxPay:function(e){
    var that = this;
    if(that.data.addr_flag){
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
                      url: 'https://shop.llzg.cn/weapp/donepay.php?act=success&order_id='+that.data.order_id+'&session_id='+session+'&info='+that.data.userMsg+'&form_id='+e.detail.formId,
                      data:{},
                      method: 'POST',
                      header: {'content-type': 'application/x-www-form-urlencoded'},
                      success: function(res){
                        wx.redirectTo({
                          url:'../orderDetails/orderDetails?order_id='+that.data.order_id
                        })
                      }
                    })
                  },
                  'fail':function(res){
                    wx.request({
                      url: 'https://shop.llzg.cn/weapp/donepay.php?act=cancel&order_id='+that.data.order_id+'&session_id='+session+'&info='+that.data.userMsg,
                      data:{},
                      method: 'POST',
                      header: {'content-type': 'application/x-www-form-urlencoded'},
                      success: function(res){
                        wx.redirectTo({
                          url:'../orderDetails/orderDetails?order_id='+that.data.order_id
                        })
                      }
                    })
                  }
                });
              }else if(res.data.result_code == 'FAIL'){
                that.myToast(res.data.err_code_des);
              }
            }
          })
        },
        fail: function(res) {
          console.log("用户登录未登录，获取地址失败!")
        }
      })
    }else{
      that.myToast("请添加收货人地址！");
      setTimeout(function(){
        wx.navigateTo({
          url: '../manageAddrList/manageAddrList'
        })
      },2000);
    }
  },
  onLoad:function(options){
    var that = this;
    wx.setNavigationBarTitle({
      title: "我的订单"
    })
    that.setData({
      order_id:options.order_id
    })
    wx.getStorage({
      key:"session",
      success: function(res){
        var session=res.data.session;
        var url = 'https://shop.llzg.cn/weapp/showgood.php?act=order&session_id='+session+'&order_id='+that.data.order_id;
        wx.request({
          url: url,
          data:{},
          method: 'POST',
          header: {'content-type': 'application/x-www-form-urlencoded'},
          success: function(res){
            if(parseInt(res.data.goods_number)>1){
              that.setData({
                goodsInf:res.data,
                minusStatus:true,
                goods_count:res.data.goods_number,
                goods_freight:res.data.yunfei,
                goods_amount:res.data.amount.toFixed(2),
                goods_total:res.data.total_fee.toFixed(2)
              })
            }else{
              that.setData({
                goodsInf:res.data,
                goods_count:res.data.goods_number,
                goods_freight:res.data.yunfei,
                goods_amount:res.data.amount.toFixed(2),
                goods_total:res.data.total_fee.toFixed(2)
              })
            }
            
          }
        })
      },
      fail: function(res) {
        console.log("用户登录未登录，获取地址失败!")
      }
    })
  },
  onShow:function(){
    var that = this;
    //获取session
    wx.getStorage({
      key:"session",
      success: function(res){
        var session=res.data.session;
        var url = 'https://shop.llzg.cn/weapp/showaddr.php?act=order&'+"session_id="+session;
        wx.request({
          url: url,
          data:{},
          method: 'POST',
          header: {'content-type': 'application/x-www-form-urlencoded'},
          success: function(res) {
            if(res.data.address == ''){
              that.setData({
                addr_flag:false
              });
            }else if(res.data.consignee){
              that.setData({
                userAddr:res.data,
                addr_flag:true
              });
            }else{
              console.log("服务器故障!!")
            }
          }
        })
      },
      fail: function(res) {
        console.log("用户登录未登录，获取地址失败!")
      }
    })
  }
})