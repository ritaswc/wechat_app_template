var cartId = ''
var addressId = ''
var request = require('../../utils/https.js')
var uri_order_confirm = 'cartapi/subToOrder' //确认订单
var uri_saveorder = 'orderapi/saveorder'  //保存订单
var uri_pay = 'wxh5pay/api/towxpayInfo'
Page({
  data: {
    orderData: {},
    addressInfo: {},
  },
  addressClick: function () {
    wx.navigateTo({
      url: '../addressManager/addressManager',
    })
  },
  // 生命周期函数--监听页面加载
  onLoad: function (options) {
    var that = this;
    cartId = options.cartIds;
    request.req(uri_order_confirm, {
      cartId: options.cartIds,
    }, (err, res) => {
      console.log(res.data);
      if (res.data.result == 1) {
        that.setData({
          //orderData: that.data.orderData.concat(res.data.data[0].cartVoList[0]),//接数组
          orderData: res.data.data[0].cartVoList[0],//接字典
          addressInfo: res.data.data[0].addressList[0],

        })
      }
    });
  },
  onShow: function () {
    // 生命周期函数--监听页面显示
    var addresss = wx.getStorageSync('address');
    if(addresss.trueName != null){
      this.setData({
        addressInfo: addresss,
      })
    }else{
      this.setData({
        addressInfo : {'trueName':'请选择收货地址'},
      })
    }
  },
  paynow: function () { //先跳转到支付成功界面界面  拿到code
    this.saveOrder();

  },
  saveOrder: function (e) {
    var codeinfo = wx.getStorageSync('codeinfo');
    var code = codeinfo.code; //保存订单接口获取ordercode
    var that = this;
    request.req(uri_saveorder, {
      invoiceId: '', //发票id
      isPd: 0, //是否使用余额支付,0:不使用,1:使用
      cartIds: cartId,  // 购物车id
      activityId: '',
      addressId: that.data.addressInfo.addressId, //地址id
      paytype: 1, // 支付方式;在线支付传:"1",货到付款传:"2"
      couponId: '',  //暂时为空  优惠券id
      freight: '' //如果订单有运费,则传字符串,运费信息用"|"隔开,前边是运费的类型,后边是店铺id,多个用","隔开,若没有运费则不传或者穿""空串
    }, (err, res) => {
      console.log(res.data)
      if (res.data.result == 1) {
        //获取paySn
        var orderCode = res.data.data[0].paySn;
        request.req(uri_pay, {
          orderCode: orderCode,
          code: code
        }, (err, res) => {
          console.log(res.data)
          var weval = res.data.Weval;
          if (res.data.result === 1) {
            //调用微信支付
            wx.requestPayment({
              timeStamp: weval.timeStamp,
              nonceStr: weval.nonceStr,
              package: weval.package,
              signType: weval.signType,
              paySign: weval.paySign,
              success: function (res) { //跳转
                wx.redirectTo({
                  url: '../paycomplete/paycomplete',
                })
              },
              fail: function () {
                console.log('fail')
              },
              complete: function () {
                console.log('complete')
              }
            })
          }
        })
      } else {

      }
    })
  },
})