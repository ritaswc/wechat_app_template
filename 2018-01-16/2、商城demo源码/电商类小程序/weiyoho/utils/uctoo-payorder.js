  //常量
 var API_URL_API='https://www.weiyoho.com/api';
var DOMAIN = 'https://www.weiyoho.com';
var mp_id = 'd8d49a5800362843f29833e03038a72a';
 //提交订单数据给后台
 function  payorder() {
   
    // var that = this;
    // var session_3rd = wx.getStorageSync('session_3rd')
    // console.log(session_3rd)
    // wx.request({
    //   url: API_URL_API+`/order`,
    //   data: {
    //     product_id: "1",
    //     product_name: "xiaok",
    //     product_price: "12",
    //     product_sku: "123",
    //     product_count: "123",
    //     receiver_address: "123",
    //     receiver_mobile: "123",
    //     receiver_name: "123",
    //     receiver_province: "123",
    //     order_total_price:'99',//支付金额
    //     session_3rd: session_3rd,
    //     mp_id: mp_id
    //   },
    //   method: 'post',
    //   success: function (res) {
    //     var orderid = res.data.order_id
    //     wx.setStorageSync("orderid", orderid);
    //     wx.navigateTo({
    //       url: `../payorder/payorder?orderid=` + orderid
    //     })
    //     console.log("GGGGGGGGGGGGG", res)
    //   },
    //   fail: function () {
    //     // fail
    //   },
    //   complete: function () {
    //     // complete
    //   }
    // })
  }
module.exports = {
    payorder:payorder
}