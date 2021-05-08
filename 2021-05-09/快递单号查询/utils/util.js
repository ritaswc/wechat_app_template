const formatTime = date => {
  let year = date.getFullYear()
  let month = date.getMonth() + 1
  let day = date.getDate()

  let hour = date.getHours()
  let minute = date.getMinutes()
  let second = date.getSeconds()

  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}

const formatNumber = n => {
  n = n.toString()
  return n[1] ? n : '0' + n
}

const isFunction = val => (typeof val === 'function');

const showLoading = (text, typeKey, time) => {
  wx.showToast({
    title: text,
    icon: typeKey,
    duration: time,
    complete: function() {}
  })
}

const hideToast = () => wx.hideToast();

const getWindowSize = () => {
  const data = {}
  wx.getSystemInfo({
    success: res => {
      data.wWidth = res.windowWidth
      data.wHeight = res.windowHeight
      data.scale = 750 / res.windowWidth
    }
  })
  return data
}

module.exports = {
  formatTime,
  isFunction,
  showLoading,
  hideToast,
  getWindowSize
}
