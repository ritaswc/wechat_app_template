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



function getWeek(len) {
  var weekday = new Array(7)
  weekday[0] = "周日";
  weekday[1] = "周一";
  weekday[2] = "周二";
  weekday[3] = "周三";
  weekday[4] = "周四";
  weekday[5] = "周五";
  weekday[6] = "周六";
  var result = [];
  var now = new Date();
  Date.prototype.getMonthDay = function () {
    return weekday[this.getDay()] + formatNumber(this.getMonth() + 1) + '月' + formatNumber(this.getDate()) + '日';
  }
  var str = '今天' + formatNumber(now.getMonth() + 1) + '月' + now.getDate() + '日';
  result.push(str);
  for (var i = 0; i < len - 1; i++) {
    now.setDate(now.getDate() + 1);
    result.push(now.getMonthDay())
  };
  return result;
  
}





module.exports = {
  formatTime: formatTime,
  getWeek:getWeek
}
