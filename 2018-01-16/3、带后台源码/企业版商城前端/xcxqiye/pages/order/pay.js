var app = getApp();
// pages/order/downline.js
Page({
  data:{
    itemData:{},
    userId:app.d.userId,
    supplierId:0,
    productId:0,//proId
    buyCount:0,
    paytype:0,//0线下1微信
    remark:'',
    cartId:0,
    addrId:122,//收货e地址//测试--
    btnDisabled:false,
    ture:false,
array:[
  {name: 'Jave', value: 'Android', checked: 'true'},
  {name: 'Object-C', value: 'IOS'},
  // {name: 'jsx', value: 'ReactNative'},
  // {name: 'js', value: 'WeChat'},
  // {name: 'Python', value: 'Web'},
 ]
  },
  
  onLoad:function(options){
    console.log(options);
    this.setData({
      cartId: options.cartId,
      productId: options.productId,
      buyCount: options.buyCount,
    });

    this.loadProductDetail();
  },
  loadProductDetail:function(){
    var that = this;

    wx.request({
      url: app.d.hostUrl + '/ztb/productDetailsZBT/GetProductDetails',
      method:'post',
      data: {
        ProID: that.data.productId,
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        console.log(res)
        //--init data        
        var data = res.data.data[0];
        console.log(data);
        that.initProductData(data);
        that.setData({
          itemData:data,
          supplierId: data.SupplierID,
        });
        //endInitData
      },
    });
  },
  initProductData: function(data){
    data["LunBoProductImageUrl"] = [];

    var imgs = data.LunBoProductImage.split(';');
    for(let url of imgs){
      url && data["LunBoProductImageUrl"].push(app.d.hostImg + url);
    }

    data.Price = data.Price/100;
    data.VedioImagePath = app.d.hostImg + '/' +data.VedioImagePath;
    data.videoPath = app.d.hostImg + '/' +data.videoPath;
  },
  remarkInput:function(e){
    this.setData({
      remark: e.detail.value,
    })
  },
  tu:function(){
    this.setData({
      ture:true
    })
  },
  createProductOrderByWX:function(e){
    this.setData({
      paytype: 1,
    });

    this.createProductOrder();
  },
  createProductOrderByWX:function(e){
    this.setData({
      paytype: 1,
    });

    this.createProductOrder();
  },
  createProductOrderByXX:function(e){
    this.setData({
      paytype: 0,
    });

    this.createProductOrder();
  },
  createProductOrder:function(){
    this.setData({
      btnDisabled:true,
    })
    //创建订单
    var that = this;
    console.log(this.data);
    wx.request({
      url: app.d.hostUrl + '/ztb/orderZBT/AddOrderZBT',
      method:'post',
      data: {
        userId:app.d.userId,
        supplierId:that.data.supplierId,
        proId:that.data.productId,//proId
        buyCount:that.data.buyCount,
        paytype:that.data.paytype,//0线下1微信
        remark:that.data.remark,
        cartId:that.data.cartId,
        id:that.data.addrId,//收货地址//测试--
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        //--init data        
        var data = res.data;
        console.log(data);
        
        if(data.result == 'ok'){
          //创建订单成功
          if(that.data.paytype == 0){
            //线下支付
            
          }
          if(that.data.paytype == 1){
            //微信支付
            
          }
          //跳转到订单详情//不能跳转此处，因为没有orderID，只能跳转到待支付
          // wx.navigateTo({
          //   url: '/pages/order/detail?orderId='+data.OrderID,
          // });
          wx.navigateTo({
            url: '/pages/user/dingdan?currentTab=0',
          });
        }//endok
        //endInitData
      },
    });

  },


});