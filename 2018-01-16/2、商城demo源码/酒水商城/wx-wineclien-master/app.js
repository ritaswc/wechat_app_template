//app.js
App({
  // 全局变量
  globalData:{
    userInfo:null,//用户信息
    location:null,//经纬度信息
    city:{"city":"郑州","province":"河南","district":""},//用户城市信息
    bid:null,//用户bid
    systemInfo : null,//系统信息
    shopCarGoods:{},//购物车商品
    islogin : false,//是否登录
    userInfo:null,//用户信息
  },
  onLaunch: function () {
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    wx.setStorageSync('logs', logs)
    var that=this;
    // 获取用户定位信息
    this.getUserLocation(function(res){
      // 获取用户bid信息
        that.getUserBid(function(resC){
        })
    })
    this.globalData.systemInfo = wx.getSystemInfoSync();
    this.userIsLogin()
  },
  onShow:function(){
    var that = this;
      wx.getStorage({
      key: 'globalData',
      success: function(res){
        that.globalData = res.data;
        console.log(that.globalData);
      },
      fail: function() {
      },
      complete: function() {
      }
    })
  },
  onHide:function(){
    var that = this;
    wx.setStorage({
      key: 'globalData',
      data: that.globalData,
      success: function(res){
        console.log(res);
      },
      fail: function() {
      },
      complete: function() {
      }
    })
  },
  onError:function(){
    var that = this;
    wx.setStorage({
      key: 'shopCarGoods',
      data: that.globalData.shopCarGoods,
      success: function(res){
        console.log(res)
      },
      fail: function() {
      },
      complete: function() {
      }
    })
  },
  //获取用户的登录状态
  userIsLogin:function(){
     wx.getStorage({
      key: 'login',
      success: function(res){
       
        if(res == null || res == {}){
           that.globalData.islogin = false;
        }else{
          that.globalData.islogin = true;
        }
        console.log(that.globalData.islogin)
      },
      fail: function() {
      },
      complete: function() {
      }
    })
  },
  // 获取用户bid信息
  getUserBid:function(res){
    if(this.globalData.bid){
      res(this.globalData.bid);
      return;
    }
    var that = this;
    wx.request({
      url: 'http://www.jiuyunda.net:90/api/v1/city/current_city',
      data: {
        "province":that.globalData.city.province,
        "city":that.globalData.city.city,
        "district":that.globalData.city.district,
      },
      method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(bid){
        that.globalData.bid = bid.data;
        res(bid.data);
      },
      fail: function() {
      },
      complete: function() {
      }
    })
  },
  // 获取位置信息
  getUserLocation:function(res){
   var that = this;
   if(this.globalData.location){
     res(this.globalData.location);
     return;
   }
    wx.getLocation({
      type: 'gcj02', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标
      success: function(location){
        that.globalData.location = location;
        res(that.globalData.location);
      },
      fail: function() {
        wx.showToast({
          title:"获取位置失败",
          icon:"success",
          duration:350
        })
      },
      complete: function() {
      }
    })
  },
  // 获取用户信息
   getUserInfo:function(cb){
    var that = this
    if(this.globalData.userInfo){
      typeof cb == "function" && cb(this.globalData.userInfo)
    }else{
      //调用登录接口
      wx.login({
        success: function () {
          wx.getUserInfo({
            success: function (res) {
              that.globalData.userInfo = res.userInfo
              typeof cb == "function" && cb(that.globalData.userInfo)
            }
          })
        }
      })
    }
  },
  // 添加商品到购物车车
  addGoodToShopCar:function(good){
    var tempGood = this.globalData.shopCarGoods[good.id];
    if(tempGood != null){
        tempGood.buy = good.buy;
        this.globalData.shopCarGoods[good.id] = tempGood;
    }else{
      this.globalData.shopCarGoods[good.id] = good
    }
  },
  // 从购物车减少商品
  reduceGoodFromShopCar:function(good){
        console.log(this.globalData.shopCarGoods)
    if(good.buy == 0){
      delete(this.globalData.shopCarGoods[good.id])
      console.log("删除")
      console.log(this.globalData.shopCarGoods)
      return;
    }
    var tempGood = this.globalData.shopCarGoods[good.id];
    if(tempGood){
      tempGood.buy = good.buy;
      this.globalData.shopCarGoods[good.id] = tempGood;
    }else{
      this.globalData.shopCarGoods[good.id] = good
    }
    console.log(this.globalData.shopCarGoods[good.id])
  }
})