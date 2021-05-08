import request from '../../lib/request';
import resource from '../../lib/resource';

const app = getApp();
Page({
  data: {
    loading: true
  },
  onLoad({ subOrderSn }) {
    request({ path: `/clientOrderDetail/${subOrderSn}` }).then(({ data }) => {
      let address = { hasData: false };
      address = data.consignee ? {
        hasData: true,
        consignee: data.consignee,
        mobile: data.mobile,
        detail_address: data.detail_address,
        arrowShow: 'display-none'
      } : { hasData: false };
      this.setData({
        address,
        goods: data.goods,
        order: data
      });
    });
  },
  cancleOrder() {
    const outTradeNo = this.data.order.order_sn;
    wx.showModal({
      title: '',
      content: '你是否需要取消订单',
      success(res) {
        const data = {
          out_trade_no: outTradeNo
        };
        if (res.confirm) {
          request({ path: '/abolishment', data, method: 'post' }).then((re) => {
            if (Number(re.statusCode) === 200) {
              wx.navigateTo({
                url: '../orders/orders'
              });
            }
          });
        }
      }
    });
  },
  payOrder() {
    app.globalData.subOrderSn = this.data.order.sub_order_sn;
    app.globalData.price = this.data.order.total_amount;
    resource.getPaySign({ out_trade_no: this.data.order.order_sn, AppID: app.globalData.appId })
      .then((payRes) => {
        if (Number(payRes.statusCode) === 200) {
          const wechatData = payRes.data.payment;
          wx.requestPayment({
            appId: wechatData.appId,
            timeStamp: wechatData.timeStamp,
            nonceStr: wechatData.nonceStr,
            package: wechatData.package,
            signType: 'MD5',
            paySign: wechatData.paySign,
            success(res) {
              if (res.errMsg === 'requestPayment:ok') {
                app.globalData.type = 'success';
                wx.navigateTo({
                  url: '../result/result'
                });
              } else if (res.errMsg === 'requestPayment:cancel') {
                app.globalData.type = 'fail';
                wx.navigateTo({
                  url: '../orders/orders?t=unpaid'
                });
              }
            },
            fail() {
            },
            complete(res) {
               console.log(res);
            }
          });
        } else {
          this.setData({
            toast: {
              toastClass: 'yatoast',
              toastMessage: '获取支付验证错误!'
            }
          });
          setTimeout(() => {
            this.setData({
              toast: {
                toastClass: '',
                toastMessage: ''
              }
            });
          }, 2000);
        }
      });
  }
});
