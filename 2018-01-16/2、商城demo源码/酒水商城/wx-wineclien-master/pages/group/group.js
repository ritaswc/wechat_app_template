Page({
  data:{
    dataSource:[],
    product_group_id:'',
    title:''
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    this.setData({
        product_group_id:options.par,
        title:options.title
    })
    wx.setNavigationBarTitle({
      title: options.title
    })
    this.getDataFromServe();
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
    
  },
  onShareAppMessage: function() {
    // 用户点击右上角分享
    return {
      title: '酒运达', // 分享标题
      desc: '酒运达，即刻达达，喝酒就上酒运达', // 分享描述
      path: 'http://www.masyang.com' // 分享路径
    }
  },
  reduceImageClick(par){
     var index = parseInt(par.currentTarget.id);
     var obj = this.data.dataSource[index];
     if (obj.buy > 0){
       obj.buy -= 1;
       this.setData({
          dataSource:this.data.dataSource
      })
     }
     var app = getApp();
    app.reduceGoodFromShopCar(obj)
     
  },
   // 跳转到商品详情
  pushGoodDetail:function(tap){
    var index = tap.currentTarget.id;
    var good = this.data.dataSource[index];
    wx.navigateTo({
      url: '../shopDetail/shopDetail?product_id='+ good.id + "&title=" + good.title,
      success: function(res){
      },
      fail: function() {
      },
      complete: function() {
      }
    })
  },
  addImageDidClick(par){
    var index = parseInt(par.currentTarget.id);
    var obj = this.data.dataSource[index];
    if (obj.buy < obj.stock){
       obj.buy += 1;
     }else{
       wx.showToast({
         title:"库存不足",
          duration:2000,
       });
       return;
     }
    this.setData({
      dataSource:this.data.dataSource
    })
    var app = getApp();
    app.addGoodToShopCar(obj)
  },
//   获取商品分组数据
  getDataFromServe(){
      if(this.data.product_group_id.length == 0){
          wx.showToast({
              title:"参数错误",
              duration:2000,
          })
      }
      var that = this;
      wx.request({
        url: 'http://www.jiuyunda.net:90/api/v1/HomePage/product_group_product_list',
        data: {
            "product_group_id":that.data.product_group_id,
            "sort_type":0
        },
        method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        // header: {}, // 设置请求的 header
        success: function(res){



          var app = getApp();
          for(var i = 0 ; i < res.data.length;i++){
            var obj = res.data[i];
            var tempGood = app.globalData.shopCarGoods[obj.id];
             if(tempGood != null){
                obj["buy"] = tempGood.buy;
              }else{
                obj["buy"] = 0;
              }
          }
          that.setData({
              dataSource:res.data,
          })
        },
        fail: function() {
          // fail
        },
        complete: function() {
          // complete
        }
      })
  }
})