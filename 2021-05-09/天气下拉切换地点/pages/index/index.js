//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    location: {
      id: "WS10730EM8EV",
      name: "下拉刷新",
      country: "CN",
      path: "深圳,深圳,广东,中国",
      timezone: "Asia/Shanghai",
      timezone_offset: "+08:00"
    },
    now: {
      nowPic: "http://omh7cqphx.bkt.clouddn.com/",
      text: "...",
      code: "99",
      temperature: ""
    },
    last_update: "",
    
    currentQueryObj: {
      key: "bixmpxjottltdf7l",
      location: "shenzhen",
      language: "zh-Hans",
      unit: "c"
    },
    api: {
      nowUrl: "https://api.thinkpage.cn/v3/weather/now.json",
      dailyUrl: "https://api.thinkpage.cn/v3/weather/daily.json",
      suggestion: "https://api.thinkpage.cn/v3/life/suggestion.json"
    }
    
  },

  //发送请求数据
  sendRequest: function(url, queryObj, callback) {
    wx.request({
      url: url,
      data: queryObj,
      method: 'GET',
      success: function(res) {
        callback(res);
      },
      fail: function(e) {
        console.log("******error******: ", e);
      },
      complete: function(e) {
        console.log("******请求完成****** ", e);
      }
    })
  },
  //发送请求数据

  //下拉刷新
  refreashNow: function(e) {
    var that = this;
    console.log(that.data.location);
    //更新实况天气
    this.sendRequest(this.data.api.nowUrl, this.data.currentQueryObj, function(res) {
      var result = res.data.results[0];
      that.refreashData(result); //下拉更新动作后的数据刷新
    });
    //更新实况天气    
  },
  //下拉刷新

  //更新数据
  refreashData: function(result) {
    // 更新实况天气数据
    var result = result;
    result.last_update = result.last_update.slice(-14, -9);
    result.now.nowPic = "http://omh7cqphx.bkt.clouddn.com/";
    // 更新实况天气数据

    //刷新数据
    this.setData({
      location: result.location,
      now: result.now,
      last_update: result.last_update
    });
    //刷新数据
  },
  //更新数据

  //改变地点
  changeLoc: function(e) {
    var that = this;
    wx.chooseLocation({
      success: function(res){
        console.log(JSON.stringify(res)); //{"name":"天安门广场","address":"北京市东城区广场东侧路","latitude":39.90323,"longitude":116.39772,"errMsg":"chooseLocation:ok"}
        var currentQueryObj = {
          key: "bixmpxjottltdf7l",
          location: res.latitude + ":" + res.longitude,
          language: "zh-Hans",
          unit: "c"
        };
        that.setData({
          currentQueryObj: currentQueryObj
        });
        that.refreashNow();
      }
    })
  },
  //改变地点

  //定位地点
  autoLoc: function(e) {
    var that = this;
    wx.getLocation({
      type: 'wgs84', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标
      success: function(res){
        var currentQueryObj = {
          key: "bixmpxjottltdf7l",
          location: res.latitude + ":" + res.longitude,
          language: "zh-Hans",
          unit: "c"
        };
        that.setData({
          currentQueryObj: currentQueryObj
        });
        that.refreashNow();
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  //定位地点

  onShow: function(e) {
    
  },

  logmsg: function(res) {
    console.log(res);
  },

  onPullDownRefresh: function(){
    this.refreashNow();
    wx.stopPullDownRefresh();
  }
  
})
