/**
 * 发送网络请求
 */
function HttpGet(url, parm, response) {
  wx.request({
    url: url,
    data: parm,
    header: {
      //'Content-Type': 'application/json'
    },
    success: function (res) {
      return typeof response == "function" && response(res.data)
    },
    fail: function (res) {
      return typeof response == "function" && response(false)
    }
  })
}


/**
 * 发送网络请求
 */
function HttpPost(url, parm, response) {
  wx.request({
    url: url,
    data: this.json2Form(parm),
    header: {

      "Content-Type": "application/x-www-form-urlencoded"
    },
    method: "POST",
    success: function (res) {
      return typeof response == "function" && response(res.data)
    },
    fail: function (res) {
      return typeof response == "function" && response(false)
    }
  })
}

/**
 * 判空
 */
function isNull(data) {
  if (data == "" || data == undefined || data == null) {
    return true;
  } else {
    return false;
  }
}

/**
 * 时间格式转化date对象->其他
 */
function formatTime(date, format) {
  var year = date.getFullYear()
  var month = date.getMonth() + 1
  var day = date.getDate()
  if (day < 10) {
    day = "0" + day;
  }
  if (month < 10) {
    month = "0" + month;
  }

  var hour = date.getHours()
  var minute = date.getMinutes()
  var second = date.getSeconds()

  var week = date.getUTCDay();


  switch (format) {
    case "yyyy-MM-dd":
      return [year, month, day].map(formatNumber).join('-');
      break;
    case "yyyy年MM月dd日":
      return year + "年" + month + "月" + day + "日";
      break;
    case "yyyy年MM月dd日 hh:mm":
      return year + "年" + month + "月" + day + "日" + " " + hour + ":" + minute;
      break;
    case "MM.dd":
      return month + "." + day;
      break;
    case "yyyy-MM":
      return year + "-" + month;
      break;
    case "yyyy":
      return year;
      break;
    case "hh:mm:ss":
      return [hour, minute, second].map(formatNumber).join(':');
      break;
    case "hh:mm":
      return [hour, minute].map(formatNumber).join(':');
      break;
    case "week":
      var weekDay = "";
      switch (week) {
        case 0:
          weekDay = "星期天";
          break;
        case 1:
          weekDay = "星期一";
          break;
        case 2:
          weekDay = "星期二";
          break;
        case 3:
          weekDay = "星期三";
          break;
        case 4:
          weekDay = "星期四";
          break;
        case 5:
          weekDay = "星期五";
          break;
        case 6:
          weekDay = "星期六";
          break;

      }
      return weekDay;
      break;
    default:
      return [year, month, day].map(formatNumber).join('-') + ' ' + [hour, minute, second].map(formatNumber).join(':');
      break;
  }

}




function formatNumber(n) {
  n = n.toString()
  return n[1] ? n : '0' + n
}

/**
 * 时间字符串转时间戳
 */
function formatToDate(dateString) {
  var timestamp2 = Date.parse(new Date(dateString));
  console.log(dateString + "的时间戳为：" + timestamp2);
  return timestamp2;

}

/**
 * 判断时间是否相同
 */
function dateIsDifference(startDate, endDate, dateType) {
  //转化为一个格式

  switch (dateType) {
    case "day":
    case "d"://同一天
      let startDay = formatTime(new Date(startDate), "yyyy-MM-dd");
      let endDay = formatTime(new Date(endDate), "yyyy-MM-dd");
      if (startDay == endDay) {
        return true;
        break;
      } else {
        return false;
        break;
      };
    case "month":
    case "n"://同一月
      let startMonth = formatTime(new Date(startDate), "yyyy-MM");
      let endMonth = formatTime(new Date(endDate), "yyyy-MM");
      if (startMonth == endMonth) {
        return true;
        break;
      } else {
        return false;
        break;
      };
    case "year":
    case "y"://同一年
      let startYear = formatTime(new Date(startDate), "yyyy");
      let endYear = formatTime(new Date(endDate), "yyyy");
      if (startYear == endYear) {
        return true;
        break;
      } else {
        return false;
        break;
      };
    default:
      return false;

  }

}


/**
 * 求时间差
 */
function timeDifference(startDate, endDate, dateType) {

  var date3 = new Date(endDate).getTime() - new Date(startDate).getTime();   //时间差的毫秒数
  //计算出相差天数 
  var days = Math.floor(date3 / (24 * 3600 * 1000));
  var hours = (date3 / (3600 * 1000)).toFixed(1);
  var minutes = Math.floor(date3 / (60 * 1000));
  var seconds = Math.round(date3 / 1000);

  // var leave1 = date3 % (24 * 3600 * 1000)    //计算天数后剩余的毫秒数  
  // var hours = Math.floor(leave1 / (3600 * 1000))
  //计算相差分钟数  
  // var leave2 = leave1 % (3600 * 1000)        //计算小时数后剩余的毫秒数  
  // var minutes = Math.floor(leave2 / (60 * 1000))
  //计算相差秒数  
  // var leave3 = leave2 % (60 * 1000)      //计算分钟数后剩余的毫秒数  
  // var seconds = Math.round(leave3 / 1000)

  switch (dateType) {
    case "D":
      return day;
      break;
    case "H":
      return hours + "h";
      break;
    case "M":
      return minutes;
      break;
    default:
      return seconds;
      break;
  }

}


function json2Form(json) {
  var str = [];
  for (var p in json) {
    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(json[p]));
  }
  return str.join("&");
}




module.exports = {
  isNull: isNull,
  HttpGet: HttpGet,
  HttpPost: HttpPost,
  formatTime: formatTime,
  formatToDate: formatToDate,
  timeDifference: timeDifference,
  json2Form: json2Form,
  dateIsDifference: dateIsDifference,

}


