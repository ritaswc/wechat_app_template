// pages/user/user.js
var app = getApp()
Page( {
  data: {
    userInfo: {},
    orderInfo:{},
    projectSource: 'https://github.com/liuxuanqiang/wechat-weapp-mall',
    userListInfo: [ {
        icon: '../../images/iconfont-dingdan.png',
        text: '我的订单',
        isunread: true,
        unreadNum: 2
      }, {
        icon: '../../images/iconfont-card.png',
        text: '我的代金券',
        isunread: false,
        unreadNum: 2
      }, {
        icon: '../../images/iconfont-icontuan.png',
        text: '我的拼团',
        isunread: true,
        unreadNum: 1
      }, {
        icon: '../../images/iconfont-shouhuodizhi.png',
        text: '收货地址管理'
      }, {
        icon: '../../images/iconfont-kefu.png',
        text: '联系客服'
      }, {
        icon: '../../images/iconfont-help.png',
        text: '常见问题'
      }],
       loadingText: '加载中...',
       loadingHidden: false,
  },onLoad: function () {
      var that = this
      //调用应用实例的方法获取全局数据
      app.getUserInfo(function(userInfo){
        //更新数据
        that.setData({
          userInfo:userInfo,
          loadingHidden: true
        })
      });

      this.loadOrderStatus();
  },
  onShow:function(){
    this.loadOrderStatus();
  },
  loadOrderStatus:function(){
    //用户的订单状态
    var that = this;
    console.log(this.data);
    wx.request({
      url: app.d.hostUrl + '/ztb/orderZBT/GetOrderSumZBT',
      method:'post',
      data: {
        userId:app.d.userId,
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        //--init data        
        var data = res.data.data;
        console.log(data);
        that.setData({
          orderInfo:data[0],
        });
        
        //endInitData
      },
    });
  },

})