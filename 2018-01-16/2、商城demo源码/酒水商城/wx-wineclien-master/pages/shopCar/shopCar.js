Page({
  data:{
    dataSource:null
  },
  onLoad:function(options){

  },
  onReady:function(){
    // 生命周期函数--监听页面初次渲染完成
  },
  onShow:function(){
    // 生命周期函数--监听页面显示
    this.renderData();
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
      title: 'title', // 分享标题
      desc: 'desc', // 分享描述
      path: 'path' // 分享路径
    }
  },
  renderData:function(){
    var app = getApp();
    var data = new Array;
    for(var key in app.globalData.shopCarGoods){
      data.push(app.globalData.shopCarGoods[key])
    }
    this.setData({
      dataSource:data
    })
  },
  // 添加按钮被点击
  addButtonClick:function(tap){
    var index = parseInt(tap.currentTarget.id);
    var good = this.data.dataSource[index];
     if(good.buy < good.stock){
        good.buy ++ ;
    }else{
      wx.showToast({
        title:"库存不足",
        duration:350,
      })
    }
    this.setData({
      dataSource:this.data.dataSource
    })
    var app = getApp();
    app.addGoodToShopCar(good)
  },
  // 减少按钮被点击
  reduceButtonClick:function(tap){
    var id = parseInt(tap.currentTarget.id);
    var good = this.data.dataSource[id];
    if(good.buy == 1){
      good.buy = 0;
      var tempData = this.data.dataSource;
      tempData.splice(id,1);
      this.setData({
        dataSource:tempData
      })
    }else{
      if(good.buy > 1){
          good.buy -- ;
      }
      this.setData({
        dataSource:this.data.dataSource
      })
    }
    var app = getApp();
    app.reduceGoodFromShopCar(good)
  }
})