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

const showWxLoading = function(title = '加载中...', icon = 'loading', duration = 10000) {
  wx.showToast({
    title: title,
    icon: icon,
    duration: duration
  })
};

const hideWxLoading = function() {
  wx.hideToast();
}

module.exports = {
  formatTime: formatTime,
  showWxLoading: showWxLoading,
  hideWxLoading: hideWxLoading
}
