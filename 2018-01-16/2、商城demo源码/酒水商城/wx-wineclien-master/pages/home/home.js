Page({
  data:{
    "carousel_list" : null,//轮播头信息
    "icon_list" : null, //icon
    "sec_kill_round_info" : null,//秒杀
    "host_good_list":null,//热卖商品列表
    "style":{
          "host_good_image_width":0,//商品主图宽
          "host_good_back_width":0,//商品背景宽
          "host_good_back_height":0,//商品背景高
    }
  },
  onLoad:function(options){
    // 获取轮播图等信息
    this.getDataFromServer();
    this.renderControl();
  },
  onReady:function(){
    // 生命周期函数--监听页面初次渲染完成
  },
  onShow:function(){
    // 生命周期函数--监听页面显示
    this.dataControl(this.data.host_good_list);
  },
  onHide:function(){
    // 生命周期函数--监听页面隐藏
  },
  onUnload:function(){
    // 生命周期函数--监听页面卸载
  },
  onPullDownRefresh: function() {
    // 页面相关事件处理函数--监听用户下拉动作
  },
  onReachBottom: function() {
    // 页面上拉触底事件的处理函数
  },
  onShareAppMessage: function() {
    // 用户点击右上角分享
    return {
      title: '酒运达', // 分享标题
      desc: '酒运达，即刻达达，喝酒就上酒运达', // 分享描述
      path: 'http://www.masyang.com' // 分享路径
    }
  },
  // 轮播头被点击
  scrollimageclick:function(tap){
    var id = parseInt(tap.currentTarget.id);
    var data = this.data.carousel_list[id];
    if(data.event_mark == 3){
      wx.navigateTo({
        url: '../group/group?title=' + data.product_group_title + '&par=' + data.event_memo,
        success: function(res){
        },
        fail: function() {
        },
        complete: function() {
        }
      })
      return;
    }
    wx.showToast({
      title:"对不起暂无此专区",
      duration:350,
    })
  },
  // 跳转到商品详情
  pushGoodDetail:function(tap){
    var index = tap.currentTarget.id;
    var good = this.data.host_good_list[index];
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
// 添加按钮被点击
  addButtonClick:function(tap){
    var id = parseInt(tap.currentTarget.id);
    var good = this.data.host_good_list[id];
    if(good.buy < good.stock){
        good.buy ++ ;
    }else{
      wx.showToast({
        title:"库存不足",
        duration:350,
      })
      return;
    }
    this.setData({
      "host_good_list":this.data.host_good_list
    })
    var app = getApp();
    app.addGoodToShopCar(good)
  },
  // 减少按钮被点击
  reduceButtonClick:function(tap){
    var id = parseInt(tap.currentTarget.id);
    var good = this.data.host_good_list[id];
    if(good.buy >= 1){
        good.buy -- ;
    }
    this.setData({
      "host_good_list":this.data.host_good_list
    })
    var app = getApp();
    app.reduceGoodFromShopCar(good)
  },

  // 组件控制
  renderControl:function(){
    var app = getApp();
    var goodImageWidth =  (app.globalData.systemInfo.windowWidth - 5) / 2.0;
    var host_good_back_width = app.globalData.systemInfo.windowWidth / 2.0 - 2.5; 
    var host_good_back_height = host_good_back_width + 95;
    this.setData({
      "host_good_image_width":goodImageWidth,
      "host_good_back_width" : host_good_back_width,
      "host_good_back_height":host_good_back_height,
    })
  },
  //获取数据从服务器器
  getDataFromServer:function(){
      var app = getApp();
      var that = this;
      app.getUserBid(function(re){
        that.getCarouselIcon(re);
        that.getHostGoodList(re);
      })
  },
  // 获取轮播图，iconn 信息
  getCarouselIcon:function(re){
    var that = this;
       wx.request({
          url: 'http://www.jiuyunda.net:90/api/v2/home_page/index',
          data: {
            "userinfo_id":re._id,
          },
          method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
          // header: {}, // 设置请求的 header
          success: function(res){
            for(var i = 0 ; i < res.data.carousel_list.length; i ++){
              var obj = res.data.carousel_list[i];
              obj.img = "http://www.jiuyunda.net:90" + obj.img;
            }
              that.setData({
              "carousel_list" : res.data.carousel_list,
              "icon_list" : res.data.icon_list,
              "sec_kill_round_info" : res.data.sec_kill_round_info
            })
          },
          fail: function() {
          },
          complete: function() {
          }
    });
  },///获取轮播图 icon信息
  // 获取热卖商品列表
  getHostGoodList:function(re){
    var that = this;
    wx.request({
      url: 'http://www.jiuyunda.net:90/api/v2/product/list_by_sales?id=56c45924c2fb4e2050000022',
      data: {
        id:re._id
      },
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(res){
        that.dataControl(res.data);       
      },
      fail: function() {
      },
      complete: function() {
      }
    })
  },/// 获取热卖商品列表
  dataControl:function(data){
    if(data == null){
      return;
    }
    var app = getApp();
    for(var i = 0 ; i < data.length;i++){
      var good = data[i];
      var tempGood = app.globalData.shopCarGoods[good.id];
      if(tempGood != null){
        good["buy"] = tempGood.buy;
      }else{
        good["buy"] = 0;
      }
    }
    this.setData({
        "host_good_list":data
    })
  }
})