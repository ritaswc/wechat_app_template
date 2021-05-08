Page({
  // 数据
  data:{
    // 我的列表数据
    modalHidden: true,
    myList: [
      {
        iconPath: '../../static/images/my/user_icon.png',
        name: '账户',
        info: '18369956016',
        className: 'count',
        events: 'startRecord'
      }, {
        iconPath: '../../static/images/my/orders_icon.png',
        name: '全部订单',
        info: '使用微信、支付宝或拉卡拉刷卡支付立减2元',
        className: 'all-orders',
        events: 'stopRecord',
        orderList: [{
            name: '待发货',
            count: 19
          }, {
            name: '待收货',
            count: 0
          }, {
            name: '待评价',
            count: 2
          }, {
            name: '已完成',
            count: 0
          }
        ]
      }, {
        iconPath: '../../static/images/my/address_icon.png',
        name: '地址管理',
        info: '',
        className: 'address',
        events: 'getLocation'
      }, {
        iconPath: '../../static/images/my/discount_icon.png',
        name: '优惠券',
        info: '',
        className: 'coupon'
      }, {
        iconPath: '../../static/images/my/cash_coupon_icon.png',
        name: '现金券',
        info: '',
        className: 'cash-coupon'
      }, {
        iconPath: '../../static/images/my/collect_icon.png',
        name: '收藏',
        info: '',
        className: 'collection'
      }, {
        iconPath: '../../static/images/my/about_icon.png',
        name: '关于链商',
        info: '',
        className: 'about'
      }
    ]
  },

  // 退出事件
  logOut: function () {
    this.modalChange();
  },
  modalChange: function () {
    this.setData({
      modalHidden: !this.data.modalHidden
    })
  },
  startRecord: function () {
    wx.startRecord({
      success: function ( res ) {
        console.log( res );
        var tempFilePath = res.tempFilePath;
        console.log( tempFilePath );
      },
      fail: function ( err ) {
        consoel.log( err );
      }
    });
  },
  stopRecord: function () {
    wx.stopRecord();
  },
  getLocation: function () {
    wx.getLocation({
      success: function ( res ) {
        console.log( res );
      },
      fail: function () {
        console.log( res );
      }
    })
  }
})