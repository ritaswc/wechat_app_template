Page({
  data:{
      "rightDataSource":null,
      "leftDataSource":null,
      allDataSouce:null,
      "leftListSelectItem":0,
      rightItemWidth:0,
  },
  onLoad:function(options){
    // 生命周期函数--监听页面加载
    this.requestDataFromServe()
    this.renderControl()
  },
  onReady:function(){
    // 生命周期函数--监听页面初次渲染完成
  },
  onShow:function(){
    // 生命周期函数--监听页面显示
    this.updataRightData()
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
  // 渲染控制
  renderControl:function(){
    var app = getApp();
    var width = app.globalData.systemInfo.windowWidth - 75.0;
    this.setData({
      rightItemWidth : width
    })
  },

// 跳转到商品详情
  pushGoodDetail:function(tap){
    var index = tap.currentTarget.id;
    var good = this.data.rightDataSource[index];
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
   // 右侧列表被点击
  rightListClick(par){
    var index = parseInt(par.currentTarget.id);
    this.setData({
        leftListSelectItem:index,
    })
    this.updataRightData()
  },
  updataRightData:function(){
    if(this.data.allDataSource == null){
      return;
    }
    var selectClassStr = this.data.leftDataSource[this.data.leftListSelectItem];
    var selectData = new Array;
    var app= getApp();
    for(var index in this.data.allDataSource){
      var good = this.data.allDataSource[index]; 
      if(good.category_name == selectClassStr){
        var tempGood = app.globalData.shopCarGoods[good.id];
        if(tempGood != null){
          good["buy"] = tempGood.buy;
        }
        selectData.push(good);
      }
    }
    this.setData({
        rightDataSource:selectData,
    })
  },
// 减少上平
  reduceImageClick(par){
      var index = parseInt(par.currentTarget.id);
      var data = this.data.rightDataSource[index];
      if (data.buy > 0){
        data.buy -= 1;
        this.setData({
            rightDataSource:this.data.rightDataSource
        })
      } 
      var app = getApp();
      app.reduceGoodFromShopCar(data)
    },
    // 添加
  addImageDidClick(par){
    var index = parseInt(par.currentTarget.id);
    var data = this.data.rightDataSource[index];
    if (data.buy < data.stock){
      data.buy += 1;
    }else{
      wx.showToast({
        title:"库存不足",
          duration:2000,
      });
      return;
    }
    this.setData({
      rightDataSource:this.data.rightDataSource
    })
    var app = getApp();
    app.addGoodToShopCar(data)
  },


// 获取数据
  requestDataFromServe(){
    var that = this;
    wx.request({
      url: 'http://www.jiuyunda.net:90/api/v2/product/product_list',
      data: {
        "userinfo_id" : "56c45924c2fb4e2050000022"
      },
      method: 'GET', 
      success: function(res){
        that.updateData(res.data);
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  //更新数据
  updateData:function(data){
    var leftData = new Array;
    for (var key in data.category_contitions){
      var str = data.category_contitions[key][0].category_name;
      leftData.push(str);
    }
    this.setData({
        leftDataSource : leftData,
    })
    var allData = new Array;
    for(var index in data.product_list){
      var good = data.product_list[index]; 
      good.buy = 0;
      allData.push(good);
    }
    this.setData({
        allDataSource:allData
    })
    this.updataRightData();
  },
})