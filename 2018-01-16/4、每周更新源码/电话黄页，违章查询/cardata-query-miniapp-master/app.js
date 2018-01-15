//app.js
App({
  onLaunch: function () {
    //调用API从本地缓存中获取数据
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    wx.setStorageSync('logs', logs);
    

    // 获取本地车辆信息
    var carlist = wx.getStorageSync('carlist');
    this.globalData.carlist = carlist || [];


    wx.blackTip = function(str, duration = 2500, cb) {
      let pages = getCurrentPages();
      let curPage = pages[pages.length - 1]; 
      this.setData({
        "blackTip.show": true,
        "blackTip.str": str,
      });
      setTimeout(() => {
        this.setData({
          "blackTip.show": false,
        });
        typeof cb === "function" && cb();
      }, duration);
    }
  },
  getUserInfo:function(cb){
    var that = this
    if(this.globalData.userInfo){
      typeof cb == "function" && cb(this.globalData.userInfo)
    }else{
      //调用登录接口
      wx.login({
        success: function () {
          wx.getUserInfo({
            success: function (res) {console.log(res.userInfo);
              that.globalData.userInfo = res.userInfo
              typeof cb == "function" && cb(that.globalData.userInfo)
            }
          })
        }
      })
    }
  },
  setGlobalData:function(data){
    resultData:data
  },
  globalData:{
    userInfo:null,
    appKey:"949444c44b29aaa96b7e976dae276025",
    _appkey: '6153a559d2972c39',
    // appKey: " 6153a559d2972c39",
    cphm : "",
    cjh : "",
    fdjh : "",
    lstype : "",
    lsprefix : "",
    carorg : "",
    resultData: [
            {
                "time": "2016-07-08 07:16:32",
                "address": "[西湖区]长江路_长江路竞舟北路口(长江路)",
                "content": "不按规定停放影响其他车辆和行人通行的",
                "legalnum": "7003",
                "price": "150",
                "score": "0",
                "number": "",
                "illegalid": "4821518"
            }
        ],
    hphm:null,
    carlist: []

  },
  testCarnumber: function (str) {
    var a = /^[京津沪渝冀豫云辽黑湘皖鲁新苏浙赣鄂桂甘晋蒙陕吉闽贵粤青藏川宁琼使领A-Z]{1}[A-Z]{1}[A-Z0-9]{4}[A-Z0-9挂学警港澳]{1}$/
    if (a.test(str || '')) {
      return true;
    } else {
      return false;
    }
  },
  // 检测车架号和 发动机号
  testCode: function(str) {
    if (str.length === 6) {
      return true;
    } else {
      return false;
    }
  }
})