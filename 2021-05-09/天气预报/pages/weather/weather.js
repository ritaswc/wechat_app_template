// pages/weather/weather.js
// 引用百度地图微信小程序JSAPI模块 
let bmap = require('../../libs/bmap-wx.js');
let tools = require('../../utils/tools.js');
let utils = require('./util.js');
Page({
  data: {
    style: '',
    show: 'hide',
    mapIconSrc: '../../src/img/map.png',
    todyWeather: '', //今天天气
    futureThreeDay: [], //未来三天
    variousIndex: [] //各项指数
  },
  onLoad: function(options) {
    // 页面初始化 options为页面跳转所带来的参数
    let _ = this;
    tools.loading('加载中...');
    let BMap = new bmap.BMapWX({
      ak: 'g4I2oOxpdnhxmuQwYaDrrLayDqZBft78'
    });
    let fail = function(data) {
      tools.loadingEnd();
      tools.errorDialog('数据获取失败，重新加载', query);
    };
    let success = function(data) {
      //处理数据，返回自定义格式数据
      let _tody = _.dealTodayData(data.currentWeather[0]);
      let _future = _.dealFuture(data.originalData.results[0].weather_data);
      let _index = _.dealIndex(data.originalData.results[0].index);
      console.log(data.originalData.results[0].index);
      _.setData({
        show: 'show',
        todyWeather: _._addItemData(_tody),
        futureThreeDay: _future,
        variousIndex: _index
      });
      tools.loadingEnd();
    }
    let query = function() {
      // 发起weather请求 
      BMap.weather({
        fail: fail,
        success: success
      });
    }
    query();
  },
  dealTodayData: function(data) {
    let _date = data.date.split('(')[0];
    let _now = parseInt(data.date.split('：')[1].replace(/[\(-\)]/g, '')) + '°';
    let _result = {
      city: data.currentCity,
      pm25: data.pm25,
      date: _date,
      realtimeTemperature: _now,
      temperature: utils.dealTemperature(data.temperature),
      weather: data.weatherDesc,
      wind: data.wind,
      iconSrc: utils.weatherLevel(data.weatherDesc),
    };
    return _result;
  },
  dealFuture: function(data) {
    let _ = this;
    let _result = [];
    for (let i = 1; i < data.length; i++) {
      let _item = {
        weather: data[i].weather,
        date: data[i].date,
        temperature: utils.dealTemperature(data[i].temperature),
        iconSrc: utils.weatherMoreLevel(data[i].weather)
      };
      _result.push(_item);
    }
    return _result;
  },
  dealIndex: (data) => {
    let _result = [];
    for (let i = 1; i < data.length; i++) {
      let _item = {
        title: data[i].title,
        value: data[i].zs,
        desc: data[i].des
      };
      _result.push(_item);
    }
    return _result;
  },
  // 返回背景颜色，并设置背景色
  _addItemData: function(item) {
    item.style = utils.returnStyle(item.weather);
    return item;
  },
  onReady: function() {
    // 页面渲染完成
  },
  onShow: function() {
    // 页面显示
  },
  onHide: function() {
    // 页面隐藏
  },
  onUnload: function() {
    // 页面关闭
  }
})