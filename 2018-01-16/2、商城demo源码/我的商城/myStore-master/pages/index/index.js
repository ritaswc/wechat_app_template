//index.js
//获取应用实例
var app = getApp();
var swiperIndex =  0;
Page({
  data: {
    indicatorDots: "true",//是否显示面板指示点
    autoplay: "true",//是否自动切换
    interval: "5000",//自动切换时间间隔
    duration: "1000",//滑动动画时长

    imgUrls: [
      "../../img/huawei_mate9.jpg",
      "../../img/rongyao_v8.jpg",
      "../../img/rongyao_play5c.jpg"
    ],
    navs: [
      {
        icon: "../../img/icon-new-list1.png",
        text: "华为专区"
      },
      {
        icon: "../../img/icon-new-list2.png",
        text: "荣耀专区"
      },
      {
        icon: "../../img/icon-new-list3.png",
        text: "智能家居"
      },
      {
        icon: "../../img/icon-new-list4.png",
        text: "特惠专区"
      },

    ]

  },

//点击轮播图
  swiperClick : function(){
    wx.navigateTo({
       url: '../../pages/shopinfo/shopinfo?id=' + swiperIndex,
    })
  },

 

//轮播图轮播事件
  swiperChange: function (e) {   
      swiperIndex =  e.detail.current
  },
/**
 * 首页导航点击事件
 */
 navClick: function (e) {
    wx.navigateTo({
      url: '../huawei/huawei?itemType=' + e.currentTarget.dataset.type,

    })
  },

/**
 * 最热
 */
volumeClick : function(){
   wx.navigateTo({
       url: '../../pages/shopinfo/shopinfo?id=' + swiperIndex,
    })
},
/**
 * 最新
 */
newClick : function(){
   wx.navigateTo({
       url: '../../pages/shopinfo/shopinfo?id=' + swiperIndex,
    })
},
/**
 * 最火
 */
hotClick : function(){
   wx.navigateTo({
       url: '../../pages/shopinfo/shopinfo?id=' + swiperIndex,
    })
},

  onLoad: function () {
  }
})
