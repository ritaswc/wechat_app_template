var app = getApp();
// pages/order/downline.js
Page({
  data:{
    orderId:0,
    reason:'',
    remark:'',
    imgUrl:'',
  },
  onLoad:function(options){
    console.log(options);
    this.setData({
      orderId: options.orderId,
    });
  },
  submitReturnData:function(){
    console.log(this.data);
    //数据验证
    if(!this.data.reason){
      wx.showToast({
        title: '请填写退货原因',
        icon: 'success',
        duration: 2000
      });
      return;
    }
    if(!this.data.remark){
      wx.showToast({
        title: '请填写退货描述',
        icon: 'success',
        duration: 2000
      });
      return;
    }

    var that = this;
    console.log(this.data);
    wx.request({
      url: app.d.hostUrl + '/ztb/orderZBT/ApplicationReturnZBT',
      method:'post',
      data: {
        orderId: that.data.orderId,
        reason:that.data.reason,
        remark:that.data.remark,
        imgUrl:that.data.imgUrl,
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        //--init data        
        var data = res.data;
        console.log(data);
        if(data.result == 'ok'){
          wx.navigateTo({
            url: '/pages/user/dingdan?currentTab=4',
          });
        }//endok
        //endInitData
      },
    });


  },
  reasonInput:function(e){
    this.setData({
      reason: e.detail.value,
    });
  },
  remarkInput:function(e){
    this.setData({
      remark: e.detail.value,
    });
  },
  uploadImgs:function(){

    wx.chooseImage({
      success: function(res) {
        console.log(res);
        var tempFilePaths = res.tempFilePaths
        wx.uploadFile({
          url: 'http://example.weixin.qq.com/upload', //仅为示例，非真实的接口地址
          filePath: tempFilePaths[0],
          name: 'file',
          formData:{
            'user': 'test'
          },
          success: function(res){
            var data = res.data
            //do something
          }
        })
      }
    });
  },
})