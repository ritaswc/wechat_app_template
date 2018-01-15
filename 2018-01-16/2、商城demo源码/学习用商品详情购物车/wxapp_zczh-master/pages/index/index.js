//index.js
//获取应用实例
var app = getApp()
console.log(app)
console.log(app.globalData.requestId+'-----'+app.globalData.serverUrl)
Page({
   data:{   
      //轮播参数 
      images: [],//轮播图
      limit:3,//轮播数
      bannerUrl:'m.php?g=api&m=wxshop&a=slide&limit=',
      indicatorDots: true,
      vertical: false,
      autoplay: true,
      interval: 4000,
      duration: 1200,
      //end
      goods:[],//商品
      goodsUrl:'m.php?g=api&m=wxshop&a=index&pno=1&ps=',
      darp:4,//显示商品数量
      n:1,//倍数
      par:true,
      serverSrc:app.globalData.serverUrl
  },
  //跳转到详情页页
  detailgoods:function(e){
    var id=e.currentTarget.id;
    app.globalData.requestId=id;
    wx.navigateTo({
      url: '../goods/goods',
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  //跳转个人中心
  goPer:function(){
    wx.navigateTo({
      url: '../personal/personal',
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  }, 
  //事件处理函数
  // bindViewTap: function() {
  //   wx.navigateTo({
  //     url: '../cart/cart'
  //   })
  // },
  onLoad: function () {
     var that = this;
     //获取轮播图
    wx.request({
      url:app.globalData.serverUrl+that.data.bannerUrl+that.data.limit, //首页轮播图数据
      header: {
        'content-type': 'application/json'
      },
      success: function(res) {
        console.log(res);
         that.setData({
           images:res.data
         })
         console.log(that.data.images);   
      }
    })
    // 获取后台产品数据
    wx.request({
      url: app.globalData.serverUrl+that.data.goodsUrl+that.data.darp, //商品数据
      header: {
          'content-type': 'application/json'
      },
      success: function(res) {
        console.log(res.data)
        that.setData({
          goods:res.data
        })
        console.log(that.data.goods)   
      }
    })
    wx.setStorage({
        key:"key",
        data:"value"
    })
    //调用应用实例的方法获取全局数据
    // app.getUserInfo(function(userInfo){
    //   //更新数据
    //   that.setData({
    //     userInfo:userInfo
    //   })
    // })
  },
  //  // 下拉刷新
  // onPullDownRefresh: function () {
  //       // do somthing
  // },
  // toast1Change:function(e){  
  //   this.setData({toast1Hidden:true});  
  // },  
  //上拉加载
  onReachBottom: function () {
    var that=this
    var goodn=that.data.n
     goodn+=1
     this.setData({
     n:goodn
     })  
    //显示加载中
    function showT(a,b,c){
      wx.showToast({
        title:a,
        icon: b,
        duration:c
      })
    }
    if(that.data.par){
      showT("加载中...","loading",2000)
      wx.request({
        url: app.globalData.serverUrl+that.data.goodsUrl+that.data.darp*goodn, //商品数据
        header: {
            'content-type': 'application/json'
        },
        success: function(res) {
          console.log(res.data)
          that.setData({
            goods:res.data
          })
          console.log(that.data.goods) 
          if(res.data.data.length==res.data.all_count){
                showT("已加载完所有商品！","success",1200)
                that.setData({
                  par:false
                })
          }
        }
      });
    }
    console.log(that.data.par)
    
  }
  // 分享
  // onShareAppMessage: function () {
  //   return {
  //     title: '商城',
  //     desc: '为您服务',
  //     path: 'pages/index/index'
  //   }
  // }
})
