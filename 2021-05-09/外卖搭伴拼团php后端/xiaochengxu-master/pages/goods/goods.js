
var WxParse = require('../../asset/wxParse/wxParse.js');
var app = getApp();

Page({
  data: {
    animationData : {},
    show : true, //查看详情状态
    goodsInfo : {},
    groupInfo : {},
    endTime : false,
    // imagewidth: 0,//缩放后的宽  
    // imageheight: 0,//缩放后的高  
  },
  onLoad : function(options){
    // wx.showToast({
    //   title : '加载中',
    //   mask : true
    // })
    var goods_id = options.goods_id;
    this.loadGoodsDetail(goods_id);
  },
  loadGoodsDetail : function(goods_id){
    var page = this;
      wx.request({
        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Goods/OpenGroup',
        data: {data:JSON.stringify({"goods_id":goods_id})},
        method: 'POST',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        success: function(res){console.log(res.data.data);
          // wx.hideToast();
          WxParse.wxParse('goods_brief', 'html', res.data.data.goodsinfo.goods_brief,page, 5);
          page.setData({
            goodsInfo : res.data.data.goodsinfo,
            groupInfo : res.data.data.groupInfo
          });
          wx.setStorageSync('goods_id',goods_id);
        },
        fail: function(res) {
          // fail
        }
      })
  },
  showTenant : function(e){
    var mid = e.currentTarget.dataset.mid;
    wx.navigateTo({
      url: '/pages/tenant/tenant?mid=' + mid
    })
  },
  // imageLoad: function (e) {  
  //   var imageSize = imageUtil.imageUtil(e)  
  //   this.setData({  
  //     imagewidth: imageSize.imageWidth,  
  //     imageheight: imageSize.imageHeight  
  //   })  
  // },  
  hideDetail : function(e){
    var animation = wx.createAnimation({
            duration: 400,
            timingFunction: 'linear'
        })
        this.animation = animation;
        if(!this.data.show){
          animation.rotate(0).step();
        }else{
          animation.rotate(180).step();          
        }
        this.setData({
          animationData:animation.export(),
          show:!this.data.show
        })
  },
  chooseEndTime : function(e){
    var endTime = e.currentTarget.dataset.value;
    this.setData({endTime:endTime});
    wx.setStorageSync('endTime', endTime);
  },
  pay : function(e){
      if(!this.data.endTime){
        wx.showToast({
          title : '请选择截团时间',
          mask : true
        })
        return;
      }
      if(!wx.getStorageSync('unionId') && wx.getStorageSync('auth') !== 'success' && !wx.getStorageSync('uid')){
        app.applyNotice();
        return;
      }
      wx.navigateTo({
        url: '/pages/order/checkout/checkout'
      })
  },
  backHome : function(e){
    app.backHome();
  }
})
