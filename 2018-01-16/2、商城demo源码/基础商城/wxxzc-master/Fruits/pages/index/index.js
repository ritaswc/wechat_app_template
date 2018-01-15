//index.js
//获取应用实例
var app = getApp()


  

Page({
  data: {
    //motto: 'Hello World',
    //userInfo: {},
    lunbo_src: [
      '../../images/banner.png',
      '../../images/banner.png'
    ],
    lunbo_src2: [
      '../../images/img4.png',
      '../../images/img4.png'
    ],
    indicatorDots: true,
    autoplay: true,
    interval: 5000,
    duration: 1000,
    cate_src: [
      {
        mode: 'scaleToFill',
        text: '全部商品',
        picture: '../../images/i_1.png',
      },
      {
        mode: 'scaleToFill',
        text: '上新商品',
        picture: '../../images/i_2.png',
      },
      {
        mode: 'scaleToFill',
        text: '产品分类',
        picture: '../../images/i_3.png',
      },
      {
        mode: 'scaleToFill',
        text: '会员签到',
        picture: '../../images/i_4.png',
      },
      {
        mode: 'scaleToFill',
        text: '购买须知',
        picture: '../../images/i_5.png',
      },
      {
        mode: 'scaleToFill',
        text: '我的订单',
        picture: '../../images/i_6.png',
      },
      {
        mode: 'scaleToFill',
        text: '抽奖活动',
        picture: '../../images/i_7.png',
      },
      {
        mode: 'scaleToFill',
        text: '在线客服',
        picture: '../../images/i_8.png',
      }
    ],
    tc_src: [
      {
        text: '新疆特产',
        picture: '../../images/img5.png',
      },
      {
        text: '新疆特产',
        picture: '../../images/img5.png',
      },
      {
        text: '新疆特产',
        picture: '../../images/img5.png',
      },
      {
        text: '新疆特产',
        picture: '../../images/tus.jpg',
      }
    ],
    new_srcs: [
        '../../images/tus.jpg',
        '../../images/img-2.jpg',
        '../../images/img-2.jpg'
    ],
    ad_src1: '../../images/img1.png',
    ad_src2: '../../images/img2.png',
    ad_src3: '../../images/img3.png',
    new_src: '../../images/img9.jpg',
    end_src1: '../../images/f1.png',
    end_src2: '../../images/f2.png',
    end_src3: '../../images/f3.png',
    footer_src: '../../images/foote.png',
    
    
    
    
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
 onLoad:function() {
   var that = this; 
   wx.request({
     url: 'http://123.207.143.171/wxxzc/index.php',
     method: 'GET',
     success:function(res) {
        console.log(res.data[0].src)
       that.setData({
         result: res.data
         
       })
     }
 
   })
 },
  
 

 
})




