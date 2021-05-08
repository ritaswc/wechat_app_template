Page({
  data:{
    city:'',
    cityid:'',
    weatherApikey:'',
    curWd:{},
    indexs:{}
  },
  onLoad:function(options){
    // 生命周期函数--监听页面加载
    // console.log(options.city+"======="+options.query);
    this.setData({city:options.city, cityid:options.cityid, weatherApikey:getApp().globalData.weatherApikey});

    this.loadWeather(this.data.city, this.data.cityid);
  },
  onReady:function(){
    // 生命周期函数--监听页面初次渲染完成
  },
  onShow:function(){
    // 生命周期函数--监听页面显示
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
  loadWeather: function(city, cityid) {
      var page = this;
      var url = "http://apis.baidu.com/apistore/weatherservice/recentweathers";
      wx.request({
        url: url,
        data: {
            cityname:city,
            cityid:cityid
        },
        method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        header: {
            apikey: page.data.weatherApikey
        }, // 设置请求的 header
        success: function(res){
          // success
          console.log(res);
          page.setData({curWd : res.data.retData.today, indexs: res.data.retData.today.index});
        }
      })
  }
})