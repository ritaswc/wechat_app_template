var app = getApp()
Page( {
  data: {
    userInfo: {},
 projectSource: 'https://github.com/liuxuanqiang/wechat-weapp-mall',
    userListInfo: [ {
      icon: '../../images/self-icon01.png',
      text: '个人信息',
      isunread: false,
      
    }, {
        icon: '../../images/self-icon02.png',
        text: '我的收藏',
        isunread: false,
      
      }, {
        icon: '../../images/self-icon012.png',
        text: '消费记录',
        isunread: false,
        unreadNum: 1
      }, {
        icon: '../../images/self-icon04.png',
        text: '地址管理'
      }, {
        icon: '../../images/self-icon05.png',
        text: '我的分销'
      }, {
        icon: '../../images/self-icon013.png',
        text: '修改密码'
      }],
        user:[{
          icon:"../../images/self-icon.png",
          text:'全部订单',  
        }],
        navs: [{icon: "../../images/order-icon01.png",       name: "待发货"},
           {icon: "../../images/order-icon02.png", name: "待付款"},
           {icon: "../../images/order-icon03.png",  name: "待收货"},
           {icon: "../../images/order-icon04.png",     name: "待评价"}
           ],
       
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