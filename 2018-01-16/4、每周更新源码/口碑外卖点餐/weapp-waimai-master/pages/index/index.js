import Shop from '../../component/shop/shop.js';
var app = getApp();
Page({
  data: { 
    indicatorDots: true,
    autoplay: true,
    interval: 5000,
    duration: 1000,
    circular: true,
    show:{}
  },
  
  onLoad: function () {
    console.log('onLoad');
  },

  onReady : function(){
     var that = this;
     this.setData({
        activityIconMap : app.globalData.activityIconMap
     });
     
     wx.request({
       url: 'http://chang20159.coding.me/wx/index',
       method: 'GET', 
       success: function(res){
         var data = res.data;
         that.setData({
            bannerUrls : data.bannerUrls,
            categoryList : data.categoryList,
            nearbyInfos : data.nearbyInfos
         });
       }
     });
     
     wx.request({
       url: 'http://chang20159.coding.me/wx/recommendshoplist',
       method: 'GET', 
       success: function(res){
         if(res.statusCode != 200){
             wx.showToast({
                title: res.errMsg,
                icon: 'loading',
                duration: 2000
              });
              return;
         }
        var data = res.data;
         that.setData({
            shopInfoList : data     
         });  
       },
       fail: function(res) {
         wx.showToast({
            title: '网络请求失败，刷新重试一下吧',
            icon: 'loading',
            duration: 2000
          })
       },
       complete: function() {
         // complete
       }
     })     
  },

  toggleShopActivity : function(e){
      Shop.toggleShopActivity.call(this,e);
  }
})
