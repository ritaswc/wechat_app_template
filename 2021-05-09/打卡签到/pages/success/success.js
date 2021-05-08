Page({
  data: {
    offwork: true,
    time: '',
    location: '',
    winWidth: '',
    winHeight: '',
  },
  onLoad: function (options) {
    wx.getSystemInfo({
      success: (res) => {
        this.setData(
          {
            winWidth: res.windowWidth,
            winHeight: res.windowHeight,
            errMsg: options.errmsg
          });
      }
    })
    if (options.status != 'work') {

        this.setData({
          time: options.time,
          offwork: false,
          location: options.place
      })
    } else {
        this.setData({
          time: options.time,
          location: options.place
      })
    }
  },
  timeCheck: function (timeStr, callback) {
    var hour = '', minute = ''
    if (timeStr.getHours() < 10)
      hour = '0'

    if (timeStr.getMinutes() < 10)
      minute = '0'
    typeof callback == "function" && callback(hour + timeStr.getHours() + ':' + minute + timeStr.getMinutes())
  },
  back: function()
  {
    wx.navigateBack({})
  }
})