//index.js
//获取应用实例
var app = getApp()
var util = require('../../utils/util.js')
var promise = require('../../utils/bluebird.core.min.js');

Page({
  data: {
    nowTemperature: 'Helo World',
    pageDataArray: [],

  },
  //事件处理函数
  bindViewTap: function () {
  },
  chooseCity: function (event) {
    //打开选择城市页面
    wx.navigateTo({
      url: '../cityChooser/cityChooser'
    })
  },
  onShareAppMessage: function () {
    return {
      title: '分享天气',
      desc: '关心他人，就是关心自己~',
      path: '/page/user?id=123'
    }
    // wx.showToast("onshareClick");
  },
  onShow: function () {

    console.log('onShow')

    var that = this
    //清空pageDataArray
    that.setData({ pageDataArray: [] });
    initData(that);
    util.getLocation().then(function (data) {//获得坐标
      util.getCity(data.latitude + ":" + data.longitude).then(function (data) {//获得城市
        //获取定位城市的天气信息，放入到pageDataArray数组中
        cacheCheck(data.data.results[0].name, that)
        try {
          var res = wx.getStorageSync('myCity')
          if (res) {
            //有城市，根据城市来填充内容
            for (var i = 0; i < res.length; i++) {
              cacheCheck(res[i], that)
            }
          }
          //流程正常走完
          console.log(that.data);
        } catch (e) {
          //没有城市，开始渲染页面
          console.log(e);
        }
      }).catch(function (reason) {
        console.log(reason);
        console.log("获取CITY失败咯~~");

      });

    }).catch(function (reason) {
      console.log(reason);
      console.log("获取LOCATION失败咯~~");
      //判断是否有城市，直接根据城市来填充内容

      try {
        var res = wx.getStorageSync('myCity')
        if (res) {
          //有城市，根据城市来填充内容
          for (var i = 0; i < res.length; i++) {
            cacheCheck(res[i], that)
          }
        }
      } catch (e) {
        //没有城市，直接跳到设置城市页面
        wx.navigateTo({
          url: '../cityChooser/cityChooser',
          success: function (res) {
          },
          fail: function () {
          },
        })
      }
    });

    console.log("onload到此已经走完");

  },
  onshow: function () {
    //判断有没有城市，没有就返回选择城市，并吐司提示

  }
})


function getInfoByCity(city, that) {

  let pageInfo = { now: {}, daily: {}, index: {} };
  util.getWeatherInfo(city, "/weather/now.json").then(function (data) {//获得天气
    //获得当前天气
    //.data.results["0"].last_update
    console.log("获得当前天气");
    pageInfo.now = data.data;
    //console.log(data.data);
    return util.getWeatherInfo(city, "/weather/daily.json");
  }, function (reason) {
    console.log(reason);
  })
    .then(function (data) {
      //获取今天和未来4天的天气 
      console.log("获得未来3天天气");
      pageInfo.daily = data.data;
      console.log(data.data);
      return util.getWeatherInfo(city, "/life/suggestion.json")
    }, function (reason) {
      console.log(reason);
    })
    .then(function (data) {
      //获得生活指数
      console.log("获得生活指数");
      pageInfo.index = data.data
      // console.log(data.data);
    }, function (reason) {
      console.log(reason);
    }).then(function (data) {
      //最后一个回调，返回一个页面的数据
      console.log(pageInfo);
      that.setData({ pageDataArray: that.data.pageDataArray.concat(pageInfo) });
      //开始缓存
      let date = new Date();
      wx.setStorage({
        key: city,
        data: { insertTimeMs: date.getTime(), insertTimeDay: date.getDate(), pageInfo: pageInfo },
        success: function (res) {
          console.log(`成功写入缓存:${city}`);
        },
        fail: function () {
          console.log(`成功缓存:${city}时失败`);
        },

      })
    })
}


//判断缓存是否有效
function cacheCheck(city, that) {
  //通过城市取出缓存，缓存格式 key：city  value：{insertTime：dateObj,page:pageInfo}
  let cityCache;
  try {

    cityCache = wx.getStorageSync(city);
  } catch (e) {
    console.log(e);
  }

  //判断缓存是否存在
  if (cityCache) {

    let currentTime = new Date();
    let insertTimeMs = cityCache.insertTimeMs;
    let insertTimeDay = cityCache.insertTimeDay;
    let currentTimeMs = currentTime.getTime();
    let currentTimeDay = currentTime.getDate();

    console.log(currentTimeDay);
    console.log(insertTimeMs);
    //判断缓存日期是否是已经过了两个小时或者隔了一天，通过date.getTime()来比较毫秒，1000毫秒==1秒 一分钟==10000毫秒 一小时==600000毫秒
    if (currentTimeMs - insertTimeMs > 1200000) {
      //过期的数据，重新获取数据
      getInfoByCity(city, that);

    } else {
      //没有过去沿用老数据
      that.setData({ pageDataArray: that.data.pageDataArray.concat(cityCache.pageInfo) });
    }

  } else {
    //没有数据，重新获取数据
    getInfoByCity(city, that);
  }
}

function initData(that) {
  //初始化page里的数据，保证页面显示不会错乱

}


function recursion(cityArray, index) {
  let i;
  if (i > cityArray.length) {
    return;
  }
  wx.request({
    url: "https://api.thinkpage.cn/v3/weather/now.json",
    data: {
      location: cityArray[index],
      key: 'aayhploveygoev6r',
      language: 'zh-Hans'
    },
    sucess: function (res) {
      wx.request({
        url: "https://api.thinkpage.cn/v3/weather/daily.json",
        data: {
          location: cityArray[index],
          key: 'aayhploveygoev6r',
          language: 'zh-Hans'
        },
        sucess: function (res) {
          wx.request({
            url: "https://api.thinkpage.cn/v3/life/suggestion.json",
            data: {
              location: cityArray[index],
              key: 'aayhploveygoev6r',
              language: 'zh-Hans'
            },
            sucess: function (res) {
              recursion(cityArray,index+1);
            }
          })

        }

      })

    }


  })

}