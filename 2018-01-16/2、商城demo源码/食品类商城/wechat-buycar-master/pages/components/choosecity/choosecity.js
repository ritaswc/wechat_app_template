//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    indicatorDots: true, //是否显示面板指示点
    autoplay: true, //是否自动切换
    interval: 3000, //自动切换时间间隔,3s
    duration: 1000, //	滑动动画时长1s
    
    navItems:[
      {
        name:'厦门',
        url:'weather'
      },
      {
        name:'北京',
        url:'weather',
         isSplot:true,
      },
      {
        name:'上海',
        url:'weather'
      },
      {
        name:'广州',
        url:'weather'
      }, 
      {
        name:'深圳',
        url:'weather',
        isSplot:true
      },
      {
        name:'杭州',
        url:'weather'
      }
    ],

  },

  choosecity: function(event)
  {
      console.log("=======city========");
      console.log(event.currentTarget.dataset.classify);
      try {
          wx.setStorageSync('city', event.currentTarget.dataset.city)
      } catch (e) {    
      }
      //存储位置
      wx.setStorage({
        key:"city",
        data:event.currentTarget.dataset.city
        });
      //界面跳转
      wx.navigateTo({
        url :'../../components/weather/weather'
      });

  },
  
  onLoad: function () {
    console.log('=========onLoad========')
  },

  
    
  
  
})
