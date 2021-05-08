// pages/userCenter/userCenter.js
var service = require('../../utils/service.js')
Page({
  data:{
    isLogin:true,
    userPhoto:'',
    userNick:'',
    userVip:'',
    orderItems:[
        {
          orderNum:0,
          name:'待付款',
          url:'order_pay'
        },
        {
          orderNum:0,
          name:'代发货',
          url:'order_send'
        },
        {
          orderNum:0,
          name:'待收货',
          url:'order_get'
        },
        {
          orderNum:0,
          name:'退款',
          url:'order_refund'
        }
         
    ],
    navItems:[
      {
        name:'我的钱包',
        imgUrl:'icon_01.png',
        url:''
      },
            {
        name:'我的红包',
        imgUrl:'icon_02.png',
        url:''
      },
       {
        name:'推荐奖励',
        imgUrl:'icon_03.png',
        url:''
      },
       {
        name:'我的收藏',
        imgUrl:'icon_04.png',
        url:''
      },
       {
        name:'我的账号',
        imgUrl:'icon_05.png',
        url:'myAccount'
      },
       {
        name:'关于我们',
        imgUrl:'icon_01.png',
        url:''
      }
    ]
  },
  loginAction:function(){
     wx.navigateTo({
       url: '../toLogin/toLogin'
     })
  },
  telEvent:function(){
      wx.makePhoneCall({
        phoneNumber: '400-600-2063',
        success: function(res) {
          console.log('打电话成功')
        }
      })
  },
  onLoad:function(options){
      console.log('userCenterLauching....')
      var that=this;
      var userData=wx.getStorageSync('userData');
      console.log(userData);
      if(userData==null || userData=="" || userData==undefined){
          that.setData({
               isLogin:true
          })
      }else{
           that.setData({
               isLogin:false
          })
      }

      that.setData({
        userPhoto:userData.photo,
        userNick:userData.nick,
        userVip:userData.vip
      })
  }
})