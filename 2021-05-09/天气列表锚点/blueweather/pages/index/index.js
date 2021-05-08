//index.js
//获取应用实例
var app = getApp();
var util = require('../../utils/util.js');
var promise = require('../../utils/bluebird.core.min.js');
const api_url = "https://api.thinkpage.cn/v3";
const api_key = "aayhploveygoev6r";

Page({
  data: {
    pageDataArray: [],
  },

  onShareAppMessage: function () {
    return {
      title: '分享天气',
      desc: '关心他人，就是关心自己~',
      path: '/pages/index/index'
    }
    // wx.showToast("onshareClick");
  },
  onPullDownRefresh: function () {
    this.onShow();
    wx.stopPullDownRefresh();
    wx.showToast({
      title: "刷新成功！", icon: "sucess",

    });
  },
  onShow: function () {
    console.log('onShow')

    var that = this
    //清空pageDataArray
    that.setData({ pageDataArray: [] });
    initData(that);
    util.getLocation().then(function (data) {//获得坐标
      console.log(`已经获得坐标:${data.latitude} : ${data.longitude}`);
      util.getCity(data.latitude + ":" + data.longitude).then(function (data) {//获得城市
        console.log(`已经通过定位获得城市:${data.data.results[0].name}`);
        //获取到定位城市，
        let locationCity = data.data.results[0].name;

        //判断定位和上次定位是不是一样，如果不一样，要删除上一次定位的缓存
        let lastTimeLocationCity;
        try {
          lastTimeLocationCity = wx.getStorageSync("locationCity");

        } catch (e) {
          console.log(e);
        }

        if (lastTimeLocationCity) {
          console.log(`检测到缓存里保存有上次定位的城市名称：${lastTimeLocationCity}`);
          if (lastTimeLocationCity != locationCity) {
            console.log("检测到上次定位的城市和这次定位的城市是不一样的，开始移除上次定位的缓存");
            wx.removeStorage({
              key: locationCity,
              sucess: function (r) {
                console.log("成功移除上次定位城市的缓存");
                wx.setStorage({
                  key: 'locationCity',
                  data: locationCity,

                })
              }
            })
            console.log("开始把本次定位到的城市写入到locationCity");
            wx.setStorage({
              key: 'locationCity',
              data: locationCity,
            })

          }
        } else {
          console.log("开始把本次定位到的城市写入到location");
          wx.setStorage({
            key: 'locationCity',
            data: locationCity,
          })
        }


        //将定位到的城市插入到mycity中，一并开始递归
        let mycity;
        try {
          mycity = wx.getStorageSync('myCity')
        } catch (e) {
          //缓存里根本就没有内容
          console.log("检测到根本没有mycity");
          console.log(e);
          recursion([locationCity], 0, that);
        }
        if (mycity) {
          //判断定位的城市是不是已经存在于mycity中了
          if (!util.contains(mycity, locationCity)) {
            //有城市，将定位到的城市unshift到mycity中
            console.log("检测到当前定位的城市不包含在mycity中");
            mycity.unshift(locationCity);

          }//将mycity数组传入到递归中
          recursion(mycity, 0, that);
        } else {

          recursion([locationCity], 0, that);
        }

      })
        .catch(function (reason) {
          console.log(reason);
          console.log("获取city的时候失败了~~");
          //直接从缓存拿数据把

        });

    })
      .catch(function (reason) {
        console.log(reason);
        console.log("获取LOCATION失败咯，开始根据mycity来填充数据~~");
        //判断是否有城市，直接根据城市来填充内容
        let mycity2;
        try {
          mycity2 = wx.getStorageSync('myCity')

        } catch (e) {
          //没有城市，直接跳到设置城市页面
          console.log("定位失败就算了，mycity里面也没有内容，直接送你去添加城市吧");
          wx.navigateTo({
            url: '../cityChooser/cityChooser',
          })
        }
        if (mycity2) {
          //将mycity数组传入到递归中
          recursion(mycity2, 0, that);
        }
      });

    console.log("onload到此已经走完");
  }

})

function initData(that) {
  //初始化page里的数据，保证页面显示不会错乱


}


function recursion(cityArray, index, that) {

  if (index >= cityArray.length) {
    console.log("递归索引大于了数组长度，直接停止递归");
    return;
  }
  //有缓存，走缓存，并且recursion下一次次
  //通过城市取出缓存，缓存格式 key：city  value：{insertTime：dateObj,page:pageInfo}
  let cityCache;
  try {
    console.log("开始取出缓存");
    cityCache = wx.getStorageSync(cityArray[index]);
  } catch (e) {
    console.log(e);
  }

  //判断缓存是否存在
  if (cityCache) {
    console.log("缓存存在，开始判断缓存日期");
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
      console.log("缓存大于两个小时，重新获取数据");
      requestData(cityArray, index, that);

    } else {
      //没有过去沿用老数据
      console.log("缓存没有过期，直接从缓存拿数据");
      that.setData({ pageDataArray: that.data.pageDataArray.concat(cityCache.pageInfo) });
      return recursion(cityArray, index + 1, that);
    }

  } else {
    //没有数据，重新获取数据
    console.log(`${cityArray[index]}:缓存不存在，重新获取数据`);
    requestData(cityArray, index, that);

  }

  //------------------------------------citycache------------------------------------------------------------------


}

function requestData(cityArray, index, that) {
  let city = cityArray[index];
  let pageInfo = { now: {}, daily: {}, index: {} };
  wx.request({
    url: `${api_url}/weather/now.json`,
    data: {
      location: city,
      key: api_key,
      language: 'zh-Hans'
    },
    fail: function (e) {
      console.log(e);
    },
    success: function (res) {
      console.log(`已经获得了now数据：${res.data}`);

      pageInfo.now = res.data;
      wx.request({
        url: `${api_url}/weather/daily.json`,
        data: {
          location: city,
          key: api_key,
          language: 'zh-Hans'
        },
        fail: function (e) {
          console.log(e);
        },
        success: function (res) {
          console.log(`已经获得了daily数据：${res.data}`);

          pageInfo.daily = res.data;
          wx.request({
            url: `${api_url}/life/suggestion.json`,
            data: {
              location: city,
              key: api_key,
              language: 'zh-Hans'
            },
            fail: function (e) {
              console.log(e);
            },
            success: function (res) {
              console.log(`已经获得了index数据：${res.data}`);

              pageInfo.index = res.data;
              that.setData({ pageDataArray: that.data.pageDataArray.concat(pageInfo) });
              console.log("开始写入缓存");
              let date = new Date();
              wx.setStorage({
                key: city,
                data: { insertTimeMs: date.getTime(), insertTimeDay: date.getDate(), pageInfo: pageInfo },
                success: function (res) {
                  console.log(`成功写入缓存:${city}`);
                },
                fail: function (e) {
                  console.log(`成功缓存:${city}时失败`);
                },

              })
              recursion(cityArray, index + 1, that);
            }
          })

        }
      })

    }
  })
}