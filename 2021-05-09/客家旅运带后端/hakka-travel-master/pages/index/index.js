//index.js

const dateUtil = require('../../utils/date.js');
//获取应用实例
const app = getApp()
Page({
  onLoad: function(options) {
      // 监听页面加载 只调用一次
  },
  onReady: function() {
    let that = this;
      // 监听页面初次渲染完成 (初始化数据)
      // wx.showToast({
      //   title: '正在获取当前地理位置...',
      //   icon: 'loading',
      //   duration: 10000
      // })
      // wx.getLocation({
      //   type: 'wgs84', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标
      //   success: function(res){
      //     // success
      //     console.log('latitude: ' + res.latitude + '    longitude:' + res.longitude + ' accuracy: ' + res.accuracy);
      //     let location = res.latitude + ',' + res.longitude;
      //     wx.request({
      //       url: 'https://apis.map.qq.com/ws/geocoder/v1/?location=' + location + '&key=MSIBZ-OMPE4-GNOUA-XD3ON-OH6Q5-FSBAX',
      //       data: {},
      //       method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      //       // header: {}, // 设置请求的 header
      //       success: function(res){
      //         console.log(res);
      //         that.setData({
      //           startCity: res.data.result.ad_info.city.split('市')[0]
      //         })
      //         wx.setStorage({
      //           key: 'startCity',
      //           data: Object/String,
      //           success: function(res){
      //           }
      //         })
      //       },
      //       complete: function() {
      //         wx.hideToast();
      //       }
      //     })
      //   },
      // })
  },
  onShow: function() {
    // 监听页面显示，每次打开都会调用
    let startCity = app.globalData.startCity;
    let endCity = app.globalData.endCity;
    if(startCity) {
      this.setData({
        startCity: startCity
      })
    }
    if(endCity) {
      this.setData({
        endCity: endCity
      })
    }
  },  
  onHide: function() {
      // 监听页面隐藏 当navigateTo或底部tab切换时调用
  },
  onUnload: function() {
      // 监听页面卸载 当redirectTo或navigateBack的时候调用
  },
  onShareAppMessage: function() {
      // 用户点击右上角分享 此事件需要 return 一个 Object，用于自定义分享内容
      return {
        title: '客家旅运',
        path: '/page/index/index'
      }
  },
  data: {   
    // initial data
    imgUrls: [
      '../../images/lb1.jpg','../../images/lb2.jpg','../../images/lb3.jpg',
    ],
    indicatorDots: true,
    autoPlay: true,
    selectedDate: dateUtil.getToday(),
    startDate: dateUtil.getToday(),
    endDate: dateUtil.getEndDate(),
    startCity: app.globalData.startCity,
    endCity: app.globalData.endCity,
    toast: {
      content: '',
      iconUrl: '../../../images/warning.png',
      showToast: false
    }
  },
  // date picker
  changeDate(e) {
    console.log('selected date: ' + e.detail.value);
    let date = e.detail.value;
    this.setData({
      selectedDate: date
    })
    app.globalData.date = date;
  },

  // select city
  selectStartCity() {
    wx.navigateTo({
      url: '../startCity/startCity'
    })
  },
  selectEndCity() {
    wx.navigateTo({
      url: '../endCity/endCity'
    })
  },
  checkSchedule(){
    let that = this;
    let startCity = this.data.startCity;
    let endCity = this.data.endCity;
    if(startCity && startCity == '请选择出发地点') {
      that.setData({
        'toast.content': '请选择出发地点',
        'toast.showToast': true
      });
      setTimeout(() => {
        that.setData({
          'toast.showToast': false
        })
      }, 1000);
      return;
    }
    
    if(endCity && endCity == '请选择到达地点') {
      that.setData({
        'toast.content': '请选择到达地点',
        'toast.showToast': true
      });
      setTimeout(() => {
        that.setData({
          'toast.showToast': false
        })
      }, 1000);
      return;
    }
    wx.navigateTo({
      url: '../schedule/schedule',
      success: function(res){
        console.log('navigate to schedule page');
      }
    })
  },
  exchangeCity() {  // 出发、到达城市互换
    let startCity = app.globalData.startCity;
    let endCity = app.globalData.endCity;
    let temp = endCity == '请选择到达地点'? '请选择出发地点': endCity;
    app.globalData.endCity = startCity == '请选择出发地点'? '请选择到达地点': startCity;
    app.globalData.startCity = temp;
    this.setData({
      startCity: app.globalData.startCity,
      endCity: app.globalData.endCity
    })
  }
});
