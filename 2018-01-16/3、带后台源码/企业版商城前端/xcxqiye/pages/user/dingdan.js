// pages/user/dingdan.js
//index.js  
//获取应用实例  
var app = getApp();
var common = require("../../utils/common.js");
Page( {  
  data: {  
    winWidth: 0,  
    winHeight: 0,  
    // tab切换  
    currentTab: 0,  
    isStatus:1,//1待付款，2待收货，3已完成
    page:1,
    orderList0:[
      {
        dianming:'二恶烷让发顺丰'
      }
    ],
    orderList2:[],
    orderList3:[],
    orderList4:[],
  },  
  onLoad: function(options) {  
    this.initSystemInfo();
    this.setData({
      currentTab: parseInt(options.currentTab),
    });
    this.setData({
      isStatus: this.getOrderStatus(),
    });
    console.log(options);

    if(this.data.currentTab == 4){
      this.loadReturnOrderList();
    }else{
      this.loadOrderList();
    }
  },  
  getOrderStatus:function(){
    return this.data.currentTab == 0 ? 1 : this.data.currentTab == 2 ?2 :this.data.currentTab == 3 ? 3:0;
  },
  loadOrderList: function(){
    var that = this;
    console.log(this.data);
    wx.request({
      url: app.d.hostUrl + '/ztb/orderZBT/GetOrderZBTList',
      method:'post',
      data: {
        userId:app.d.userId,
        isStatus:that.data.isStatus,
        pageindex:that.data.page,
        pagesize:10,
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        //--init data        
        var data = res.data.data;
        console.log(data);
        that.initOrderList(data);
        switch(that.data.currentTab){
          case 0:
            that.setData({
              orderList0: that.data.orderList0.concat(data),
            });
            break;
          case 2:
            that.setData({
              orderList2: that.data.orderList2.concat(data),
            });
            break;
          case 3:
            that.setData({
              orderList3: that.data.orderList3.concat(data),
            });
            break;
        }
        //endInitData
        console.log(that.data);
      },
    });
  },
  initOrderList:function(data){
    for(var i=0; i<data.length; i++){
      console.log(data[i]);
      var item = data[i];

      item.ImgUrl = app.d.hostImg + item.ImgUrl;
      item.price = item.price/100;
      item.BuyMoney = item.BuyMoney/100;
      item.TotalAmount = item.TotalAmount/100;
      item.TotalMoney = item.TotalMoney/100;
      item.ProductImgUrl = app.d.hostImg + item.ProductImgUrl;
    }
  },
  loadReturnOrderList:function(){
    var that = this;
    console.log(this.data);
    wx.request({
      url: app.d.hostUrl + '/ztb/orderZBT/GetReturnListZBT',
      method:'post',
      data: {
        userId:app.d.userId,
        pageindex:that.data.page,
        pagesize:10,
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        //--init data        
        var data = res.data.data;
        console.log(data);
        that.initOrderList(data);
        that.setData({
            orderList4: that.data.orderList4.concat(data),
          });
        //endInitData
      },
    });
  },
  removeOrder:function(e){
    var that = this;
    var orderId = e.currentTarget.dataset.orderId;
    wx.showModal({
      title: '提示',
      content: '你确认移除吗',
      success: function(res) {

        res.confirm && wx.request({
          url: app.d.hostUrl + '/ztb/orderZBT/ReMoveOrderZBT',
          method:'post',
          data: {
            orderId: orderId,
          },
          header: {
            'Content-Type':  'application/x-www-form-urlencoded'
          },
          success: function (res) {
            //--init data
            var data = res.data;
            console.log(data);
            //todo
            if(data.result == 'ok'){
              //that.data.productData.length =0;
              switch(that.data.currentTab){
                case 0:
                  that.data.orderList0.length = 0;
                  break;
                case 2:
                  that.data.orderList2.length = 0;
                  break;
                case 3:
                  that.data.orderList3.length = 0;
                  break;
              }
              that.loadOrderList();
            }
          },
        });

      }
    });
  },
  // returnProduct:function(){
  // },
  initSystemInfo:function(){
    var that = this;  
  
    wx.getSystemInfo( {
      success: function( res ) {  
        that.setData( {  
          winWidth: res.windowWidth,  
          winHeight: res.windowHeight  
        });  
      }    
    });  
  },
  bindChange: function( e ) {  
  
    var that = this;  
    that.setData( { currentTab: e.detail.current });  
  
  },  
  swichNav: function( e ) {  
  
    var that = this;  
  
    if( this.data.currentTab === e.target.dataset.current ) {  
      return false;  
    } else {  
      that.setData({
        currentTab: parseInt(e.target.dataset.current),
      });
      that.setData( {  
        isStatus: this.getOrderStatus(),
      });

      //没有数据就进行加载
      switch(that.data.currentTab){
          case 0:
            !that.data.orderList0.length && that.loadOrderList();
            break;
          case 2:
            !that.data.orderList2.length && that.loadOrderList();
            break;
          case 3:
            !that.data.orderList3.length && that.loadOrderList();
            break;
          case 4:
            that.data.orderList4.length = 0;
            that.loadReturnOrderList();
            break;
        }
    };
  },
  /**
   * 微信支付订单
   */
  payOrderByWechat: function(event){
    var orderId = event.currentTarget.dataset.orderId;
    this.prePayWechatOrder(orderId);
    var successCallback = function(response){
      console.log(response);
    }
    common.doWechatPay("prepayId", successCallback);
  },

  /**
   * 调用服务器微信统一下单接口创建一笔微信预订单
   */
  prePayWechatOrder: function(orderId){
    var uri = "/ztb/userZBT/GetWxOrder";
    var method = "post";
    var dataMap = {
      SessionId: app.globalData.userInfo.sessionId,
      OrderNo: orderId
    }
    console.log(dataMap);
    var successCallback = function (response) {
      console.log(response);
    };
    common.sentHttpRequestToServer(uri, dataMap, method, successCallback);
  }
})