//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    imgUrls: [
      "http://103.27.4.65/files/banner/20160815/147123112168.jpg",
        "http://103.27.4.65/files/banner/20160815/14712455440.jpg"
    ],
    indicatorDots: true, //是否显示面板指示点
    autoplay: true, //是否自动切换
    interval: 3000, //自动切换时间间隔,3s
    duration: 1000, //	滑动动画时长1s
    
    navItems:[
      {
        name:'待开发',
        url:'bill'
      },
      {
        name:'查询天气',
        url:'weather',
         isSplot:true,
      },
      {
        name:'待开发',
        url:'bill'
      },
      {
        name:'待开发',
        url:'bill'
      }, 
      {
        name:'2048',
        url:'games',
        isSplot:true
      },
      {
        name:'待开发',
        url:'bill'
      }
    ],

    venuesItems:[
        {
            id:"471",
            title:"喜多多 椰果王礼盒 200ml*10罐",
            imgurl:"http://103.27.4.65/files/product/20160926/147485679272_small.jpg",
            price:"30.00",
            money:"31.80"
        },
        {
            id:"470",
            title:"喜多多 牛奶花生 370g*12罐",
            imgurl:"http://103.27.4.65/files/product/20160925/147479458751_small.jpg",
            price:"32.00",
            money:"36.00"
        },
        {
            id:"469",
            title:"喜多多 桂圆莲子八宝粥 360g*12罐",
            imgurl:"http://103.27.4.65/files/product/20160925/147479295775_small.jpg",
            price:"32.00",
            money:"36.00"
        },
        {
            id:"468",
            title:"喜多多 冰糖雪梨椰果 280g*12罐",
            imgurl:"http://103.27.4.65/files/product/20160925/147479161349_small.jpg",
            price:"38.00",
            money:"42.00"
        },
        {
            id:"467",
            title:"喜多多 什锦椰果罐头 370g 整箱12瓶 果肉果粒",
            imgurl:"http://103.27.4.65/files/product/20160925/147478997549_small.jpg",
            price:"38.00",
            money:"42.00"
        },
        {
            id:"463",
            title:"好丽友派 巧克力清新抹茶味216g",
            imgurl:"http://103.27.4.65/files/product/20160821/147177248285_small.jpg",
            price:"10.00",
            money:"11.00"
        },
    ],
    choiceItems:[
       {
            id:"467",
            title:"喜多多 什锦椰果罐头 370g 整箱12瓶 果肉果粒",
            imgurl:"http://103.27.4.65/files/product/20160925/147478997549_small.jpg",
            price:"38.00",
            money:"42.00"
        },
        {
            id:"463",
            title:"好丽友派 巧克力清新抹茶味216g",
            imgurl:"http://103.27.4.65/files/product/20160821/147177248285_small.jpg",
            price:"10.00",
            money:"11.00"
        },
        {
            id:"471",
            title:"喜多多 椰果王礼盒 200ml*10罐",
            imgurl:"http://103.27.4.65/files/product/20160926/147485679272_small.jpg",
            price:"30.00",
            money:"31.80"
        },
        {
            id:"470",
            title:"喜多多 牛奶花生 370g*12罐",
            imgurl:"http://103.27.4.65/files/product/20160925/147479458751_small.jpg",
            price:"32.00",
            money:"36.00"
        },
        {
            id:"469",
            title:"喜多多 桂圆莲子八宝粥 360g*12罐",
            imgurl:"http://103.27.4.65/files/product/20160925/147479295775_small.jpg",
            price:"32.00",
            money:"36.00"
        },
        {
            id:"468",
            title:"喜多多 冰糖雪梨椰果 280g*12罐",
            imgurl:"http://103.27.4.65/files/product/20160925/147479161349_small.jpg",
            price:"38.00",
            money:"42.00"
        }  
    ]

  },
  onLoad: function () {
    console.log('=========onLoad========')
    
  }
    
})
