//var weatherApikey = "77c92a1703a2d96a86b9a60c593481a4";
Page({

  data:{
    weatherApikey:'', //天气apikey，在http://apistore.baidu.com 上申请
    city:'', //城市名称
    areaid:'', //城市对应的id
    curWd:{}, //当天天气情况
    indexs:{}, //当天天气详情说明
    forecast:{} //未来4天的天气情况
  },
  onLoad:function(options){
    // 生命周期函数--监听页面加载
    this.setData({weatherApikey:getApp().globalData.weatherApikey});
    this.loadLocation();
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
      title: '天气-小程序', // 分享标题
      desc: '今天天气怎么样？', // 分享描述
      path: 'path' // 分享路径
    }
  },
  //获取当前的位置信息，即经纬度
  loadLocation: function() {
      var page = this;
      wx.getLocation({
        type: 'gcj02', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标
        success: function(res){
          // success
          var latitude = res.latitude;
          var longitude = res.longitude;
          
          //获取城市
          page.loadCity(latitude, longitude);
        }
      })
  }, 

//通过经纬度获取城市
  loadCity: function(latitude, longitude) {
      var page = this;
      var url = "http://apis.map.qq.com/ws/geocoder/v1/?location="+latitude+","+longitude+"&key=XSWBZ-EVQ3V-UMLPA-U4TP6-6MQFZ-UUFSL&get_poi=1";
      wx.request({
        url: url,
        data: {},
        method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        // header: {}, // 设置请求的 header
        success: function(res){
          // success
            var city = res.data.result.address_component.city;
            city = city.replace("市", ""); //将“市”去掉，要不然取不了
            page.setData({city: city});
            page.loadId(city);
        }
      })
  },

  //通过城市名称获取城市的唯一ID
  loadId: function(city) {
      var page = this;
      var url = "http://apis.baidu.com/apistore/weatherservice/citylist";
      wx.request({
        url: url,
        data: {
            cityname: city
        },
        header: {
            apikey:page.data.weatherApikey
        }, 
        method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        success: function(res){
          // success
          var cityid = res.data.retData[0].area_id;

          page.setData({areaid: cityid});
          page.loadWeather(city, cityid);
        }
      })
  },

  //通过城市名称和城市ID获取天气情况
  loadWeather: function(city, areaId) {
      var page = this;
      var url = "http://apis.baidu.com/apistore/weatherservice/recentweathers";
      wx.request({
        url: url,
        data: {
            cityname:city,
            cityid: areaId
        },
        header: {
            apikey: page.data.weatherApikey
        },
        method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        success: function(res){
          // success
          page.setData({curWd : res.data.retData.today, indexs: res.data.retData.today.index, forecast:res.data.retData.forecast});
        }
      })
  },

  gotoDetail: function(event) {
    // console.log(this.data.areaid+"==在这里跳转=="+this.data.city);
    wx.navigateTo({
      url: '../detail/detail?city='+this.data.city+"&cityid="+this.data.areaid
    })
  }
})