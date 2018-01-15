// pages/user/main.js
Page({
  data:{
    loading:true,
    items:[{
      mode: 'aspectFit',
      text: 'aspectFit：保持纵横比缩放图片，使图片的长边能完全显示出来'
    }],
     // nav
    navs: [
      {
        image: '../../image/payicon.png',
        text: '待付款'
      }, {
        image: '../../image/caricon.jpg',
        text: '待发货'
      }, {
        image: '../../image/takeicon.jpg',
        text: '待收货'
      }, {
        image: '../../image/remarkicon.jpg',
        text: '待评价'
      }
    ],
    
      image: '../../image/mytitle.jpg',
      image0:'../../image/ordericon.jpg',
      image1:'../../image/lbsicon.jpg',
      image2:'../../image/usericon.png',
      image3:'../../image/collecticon.png',
      image4:'../../image/couponicon.png',
      image5:'../../image/serviceicon.png',

     
  },

  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }
})