//获取应用实例
var app = getApp()
Page( {
  data: {
    userInfo: {},
    userListInfo: [ 
      {
        icon: '../../image/iconfont-dingdan.png',
        text: '我的订单',
        isunread: true,
        unreadNum: 2
      }, 
      {
        icon: '../../image/iconfont-card.png',
        text: '收货地址',
        isunread: false,
        unreadNum: 2
      }, 
      {
        icon: '../../images/iconfont-icontuan.png',
        text: '售后记录',
        isunread: false,
        unreadNum: 1
      }, 
      {
        icon: '../../images/iconfont-shouhuodizhi.png',
        text: '消息通知',
        isunread: true,
        unreadNum: 1
      }, 
      {
        icon: '../../images/iconfont-kefu.png',
        text: '联系客服'
      }, 
      {
        icon: '../../images/iconfont-help.png',
        text: '关于大好'
      }]
  },

  onLoad: function() {
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo( function( userInfo ) {
      //更新数据
      that.setData( {
        userInfo: userInfo
      })
    })
  }
})
