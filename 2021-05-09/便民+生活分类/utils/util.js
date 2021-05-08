var app = getApp()

var qrcode = require('./qrcode');

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

/** 定位 */
function getLocation(successRes, failRes) {
  var that = this
  wx.getLocation({
    type: 'wgs84', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标  
    success: function (res) {
      // success  
      // let longitude = res.longitude
      // let latitude = res.latitude
      // that.loadCity(longitude, latitude)
      successRes(res)

    },
    fail: function (error) {
      // fail  
      failRes(error)
    },
    complete: function () {
      // complete  
    }
  })
}

/** 网络请求-POST */
function RequestManager(url, para, successRes, failRes) {
  wx.request({
    url: url,
    data: para,
    method: 'POST',
    header: {
      'content-type': 'application/json'
    },
    dataType: '',
    success: function (res) {

      var dic = res.data;

      if (dic.hasOwnProperty("mesg")) { //判断JSON数据是否存在某字段的方法
        if (dic.mesg != null && dic.mesg.length) {
          dic["msg"] = dic["mesg"];
        }
      } else if (dic.hasOwnProperty("msg")) {
        if (dic.msg != null && dic.msg.length) {
          dic["mesg"] = dic["msg"];
        }
      } else {
        dic["msg"] = ""
        dic["mesg"] = ""
      }

      if (dic["code"] == "000000") {
        successRes(dic);
      } else {
        //failRes("error" + dic.code)
        //failRes(dic);
        successRes(dic);
      }

      console.log(url + "      ***********->      " + dic["msg"])
    },
    fail: function (error) {
      failRes(error)
      console.log(url + "      ***********->      " + error)
    }
  })
}

function RequestManagerWithToken(url, para, successRes, failRes) {

  var token = wx.getStorageSync('token') //同步获取指定key对应的内容
  if (!token) {
    //登录无效 - 跳转到登录界面
    wx.redirectTo({  //跳转触发后 当前页面就会被销毁
      url: '/pages/login/login',
    })
    return;
  }

  wx.request({
    url: url,
    data: para,
    method: 'POST',
    header: {
      'content-type': 'application/json',
      'token': token
    },
    dataType: '',
    success: function (res) {

      var dic = res.data;

      if (dic.hasOwnProperty("mesg")) { //判断JSON数据是否存在某字段的方法
        if (dic.mesg != null && dic.mesg.length) {
          dic["msg"] = dic["mesg"];
        }
      } else if (dic.hasOwnProperty("msg")) {
        if (dic.msg != null && dic.msg.length) {
          dic["mesg"] = dic["msg"];
        }
      } else {
        dic["msg"] = ""
        dic["mesg"] = ""
      }

      if (dic.code == "000000") {

      }
      else if (dic.code == app.globalData.token_expired || dic.code == app.globalData.token_invalid) {
        //failRes("error" + dic.code)
        //failRes(dic);

        //同步清理本地数据缓存
        wx.clearStorageSync()

        //登录无效 - 跳转到登录界面
        wx.redirectTo({  //跳转触发后 当前页面就会被销毁
          url: '/pages/login/login',
        })
      }
      else {


      }

      successRes(dic);

      console.log(url + "      ***********->      " + dic["msg"])
    },
    fail: function (error) {
      failRes(error)
      console.log(url + "      ***********->      " + error)
    }
  })
}

function convertToStarsArray(stars) {
  var num = stars.toString().substring(0, 1);
  var array = [];
  for (var i = 1; i <= 5; i++) {
    if (i <= num) {
      array.push(1);
    }
    else {
      array.push(0);
    }
  }
  return array;
}

function convertToDistance(dis) {
  var tempDis = parseFloat(dis)

  if (tempDis < 1000) {
    return tempDis.toFixed(0) + "m"

  } else {
    return (tempDis / 1000).toFixed(1) + "km"
  }

}
/** 判断用户名是否正确 */
function verificationUserName(userName) {
  // 账号验证规则
  //    1：以 1 开头
  //    2：11位数字

  if (userName.substring(0, 1) == "1" && userName.length == 11) {
    return true;
  }

  return false;

}
/** 判断用户密码是否正确 */
function verificationPwd(pwd) {

  //var regPwd = new RegExp('^[a-zA-Z0-9 ]{6,20}$', 'g');
  var regPwd = new RegExp('^\\S{6,20}$', 'g');
  var result = regPwd.exec(pwd);
  return result;

}

function convert_length(length) {
  return Math.round(wx.getSystemInfoSync().windowWidth * length / 750);
}

/** 生成二维码 */
function qrc(id, code, width, height) {
  qrcode.api.draw(code, {
    ctx: wx.createCanvasContext(id),
    width: convert_length(width),
    height: convert_length(height)
  })
}

module.exports = {
  formatTime: formatTime,
  getLocation: getLocation,
  RequestManager: RequestManager,
  RequestManagerWithToken: RequestManagerWithToken,
  convertToStarsArray: convertToStarsArray,
  convertToDistance: convertToDistance,
  verificationUserName: verificationUserName,
  verificationPwd: verificationPwd,
  qrcode: qrc
}
