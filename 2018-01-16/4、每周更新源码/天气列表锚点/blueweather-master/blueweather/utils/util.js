const api_url = "https://api.thinkpage.cn/v3";
var promise = require('bluebird.core.min.js');
//https://api.thinkpage.cn/v3/weather/now.json 当前天气
//https://api.thinkpage.cn/v3/location/search.json?key=your_api_key&q=39.93:116.40 //根据坐标获得城市
function formatTime(date) {
  var year = date.getFullYear()
  var month = date.getMonth() + 1
  var day = date.getDate()

  var hour = date.getHours()
  var minute = date.getMinutes()
  var second = date.getSeconds()


  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}

function formatNumber(n) {
  n = n.toString()
  return n[1] ? n : '0' + n
}


//封装版本
function getWeatherInfo(city, suffix) {
  //IF 是两个小时内请求的，则不请求
  //ELSE 发起请求，重新填充数据
  return new promise(function (resolve, reject) {
    wx.request({
      url: api_url + suffix,
      data: {
        location: city,
        key: 'aayhploveygoev6r',
        language: 'zh-Hans'
      },
      header: {
        'content-type': 'application/json'
      },
      success: resolve,
      fail: reject
    })
  })
}

//通过坐标获得城市
function getCity(location) {
  return new promise(function (resolve, reject) {
    wx.request({
      url: "https://api.thinkpage.cn/v3/location/search.json",
      data: {
        q: location,
        key: 'aayhploveygoev6r',
        language: 'zh-Hans'
      },
      header: {
        'content-type': 'application/json'
      },
      success: resolve,
      fail: reject
    })
  })
}


//获得坐标 并根据坐标获得城市
function getLocation() {
  return new promise(function (resolve, reject) {
    wx.getLocation({
      success: resolve,
      fail: reject
    })
  })
}


function getImg(code) {
  switch (code) {
    case "0", "2", "38": return "0.png"; break;
    case "1", "3": return "1.png"; break;
    case "4": return "4.png"; break;
    case "5": return "5.png"; break;
    case "6": return "6.png"; break;
    case "7": return "7.png"; break;
    case "8": return "8.png"; break;
    case "9": return "9.png"; break;
    case "10", "11", "13", "19": return "10.png"; break;
    case "12", "20": return "12.png"; break;
    case "14": return "14.png"; break;
    case "15": return "15.png"; break;
    case "16", "17", "18": return "16.png"; break;
    case "21", "22": return "21.png"; break;
    case "23", "24", "25", "37": return "23.png"; break;
    case "26", "27", "28", "29": return "26.png"; break;
    case "30": return "30.png"; break;
    case "31": return "31.png"; break;
    case "32", "33": return "32.png"; break;
    case "34", "35", "36": return "34.png"; break;
    case "31": return "31.png"; break;
    case "31": return "31.png"; break;
    default: return "99.png";
  }


}

function contains(arr, obj) {
  var i = arr.length;
  while (i--) {
    if (arr[i] === obj) {
      return true;
    }
  }
  return false;
}
function remove(arr, obj) {
  console.log(arr);
  var i = arr.length;
  while (i--) {
    if (arr[i] === obj) {
      arr.splice(i,1);
    }
  }
  //console.log(arr);
  return arr;

}
module.exports = {
  formatTime: formatTime,
  getLocation: getLocation,
  getCity: getCity,
  getWeatherInfo: getWeatherInfo,
  getImg: getImg,
  contains: contains,
  remove:remove

}