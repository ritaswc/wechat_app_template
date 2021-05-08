//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    indicatorDots: false,
    autoplay: false,
    interval: 5000,
    duration: 1000,
    navData : {},
    swiperData : [],
    swiperStatus : false,
    goodsData : {},
    currentCatId : 0
  },
  onLoad : function(options){
    // var page = this
    // //调用应用实例的方法获取全局数据
    // app.getUserInfo(function(userInfo){
    //   //更新数据
    //   page.setData({
    //     userInfo:userInfo
    //   })
    // })  


    
    // if (wx.showLoading) {
    //   wx.showLoading({
    //     title : '加载中',
    //     mask : true
    //   })
    // } else {
    //   // 如果希望用户在最新版本的客户端上体验您的小程序，可以这样子提示
    //   wx.showModal({
    //     title: '提示',
    //     content: '当前微信版本过低，无法使用该功能，请升级到最新微信版本后重试。'
    //   })
    // }
    this.loadNav();
    this.loadSwiper();
    this.loadGoods();
  },
  // onShow : function(){
  //   wx.showLoading({
  //     title : '加载中',
  //     mask : true
  //   })
  // },
  // 加载导航菜单
  loadNav : function(){
    var page = this;
    wx.request({
      url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Goodscate/index',
      data: {data:JSON.stringify({"parent_id":0})},
      method: 'POST',
      header: {
          'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){
         page.setData({navData:res.data.data})
      },
      fail: function(res) {
        // fail
      }
    })
  },
  //加载轮播图
  loadSwiper : function(){
    var page = this;
    var swiperData = [];
    wx.request({
      url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Ad/index',
      success: function(res){
         var data = res.data.data; console.log(data)
        //  for(var i in data) {
        //    swiperData.push(data[i].image)
        //  }
         page.setData({swiperData:data})
      },
      fail: function(res) {
        // fail
      }
    })
  },
  //加载首页商品
  loadGoods : function(){
      var page = this;
      wx.request({
        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Goods/getGoods',
        data: {data:JSON.stringify({"is_goods":1})},
        method: 'POST',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        success: function(res){
          page.setData({goodsData:res.data.data,swiperStatus:false,currentCatId:0})
          // wx.hideLoading()
        },
        fail: function(res) {
          // fail
        }
      })
  },
  getCateGoods : function(e){
     var catid = e.currentTarget.dataset.catid;console.log(e)
     var page = this;
      wx.request({
        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Goods/getGoods',
        data: {data:JSON.stringify({"catid":catid})},
        method: 'POST',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        success: function(res){
          page.setData({goodsData:res.data.data,swiperStatus:true,currentCatId:catid})
        }
      })
  },
  //商品详情页
  showGoods : function(e){
    var goods_id = e.currentTarget.id;
    wx.navigateTo({
      url: '../goods/goods?goods_id=' + goods_id
    })
  },
  onShareAppMessage : function(){
    return {
      title : '搭伴小程序',
      path : '/pages/index/index'
    }
  }
})
