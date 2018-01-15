//支付功能
//常量
 var API_URL_API='https://www.weiyoho.com/api';
var DOMAIN = 'https://www.weiyoho.com';
var mp_id = 'd8d49a5800362843f29833e03038a72a';

function generateOrder() {
    var that = this
    //统一支付
    wx.showToast({
        title: '正在处理订单，请稍候...',
        icon: 'loading',
        duration: 10000
    })
    var login = wx.getStorageSync('login')
    var orderidStorage = wx.getStorageSync('orderid')
    setTimeout(function () {
        wx.request({
            url: DOMAIN + `/mpbase/wxapp/wxpay/mp_id/` + mp_id + '/id/' + orderidStorage,
            method: 'post',
            data: {
                // session_3rd: login.session_3rd
            },
            success: function (res) {
                var pay = res.data;
                //发起支付
                
                //支付
                payFn(pay);
            },
        })
    }, 2000)

}
/* 支付   */
function payFn(param) {
    wx.hideToast();
    wx.requestPayment({
        timeStamp: param.timeStamp,
        nonceStr: param.nonceStr,
        package: param.package,
        signType: param.signType,
        paySign: param.paySign,
        success: function (res) {
            wx.navigateBack({
                delta: 1, // 回退前 delta(默认为1) 页面
                success: function (res) {
                    wx.showToast({
                        title: '支付成功',
                        icon: 'success',
                        duration: 2000
                    })
                },
                fail: function () {
                    // fail
                },
                complete: function () {
                    // complete
                }
            })
        },
        fail: function (res) {
            // fail
            console.log("支付失败")
            console.log(res)
            wx.showModal({ title: '提示', content: '支付失败', })
            return
        },
        complete: function () {
        }
    })
}
module.exports = {
    generateOrder: generateOrder
}