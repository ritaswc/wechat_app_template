var app = getApp();
// pages/order/downline.js
Page({
  data:{
    orderId:0,
    payBankName:'支付宝',
    payAccountName:'',
    payMethod:'银行转帐',
    payNo:'',
    payRemark:'',
    payBankNameList: ['支付宝', '中国农业银行', '中国建设银行', '中国银行', '中国工商银行', '兴业银行'],
    payMethodList: ['银行转帐', '支付宝'],
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    this.setData({
      orderId: options.orderId,
    })
  },
  submitPayInfo:function(){
    var that = this;
    if(!this.data.payNo){
      wx.showToast({
        title: '请输入支付流水号',
        icon: 'success',
        duration: 2000
      });
      return;
    }
    if(!this.data.payAccountName){
      wx.showToast({
        title: '请输入支付人',
        icon: 'success',
        duration: 2000
      });
      return;
    }
    //post data

    wx.request({
      url: app.d.hostUrl + '/ztb/orderZBT/AddpaymentInfo',
      method:'post',
      data: {
         orderId: that.data.orderId,
        payBankName: that.data.payBankName,
        payAccountName: that.data.payAccountName,
        payMethod:that.data.payMethod,
        payNo:that.data.payNo,
        payRemark:that.data.payRemark,
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        console.log(res)
        //--init data        
        var data = res.data;
        console.log(data);
        //创建订单成功
        wx.showToast({
          title: data.message,
          icon: 'success',
          duration: 2000
        });
        if(data.result == 'ok'){
          wx.navigateTo({
            url: '/pages/user/dingdan?currentTab=2',
          });
        }//endok
        //endInitData
      },
    });

  },
  bindPickerPayBankNameChange: function(e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      payBankName: this.data.payBankNameList[parseInt(e.detail.value)]
    });
  },
  bindPickerPayMethodChange: function(e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      payMethod: this.data.payMethodList[parseInt(e.detail.value)]
    });
  },
  bindKeyInputPayNo:function(e){
    this.setData({
      payNo: e.detail.value
    });
  },
  bindKeyInputPayUser:function(e){
    this.setData({
      payAccountName: e.detail.value
    });
  },

});