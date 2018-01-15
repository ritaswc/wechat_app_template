Page({
  data: {
    info: '',
    winWidth: '',
    winHeight: '',
  },
  onLoad: function (options) {
    if(options.info) {
      this.setData({
        info: options.info
      })
    }
    else {
      this.setData({
        info: ''
      })
    }

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
  },
  back_click: function () {
    wx.navigateBack({
      delta: 1, // 回退前 delta(默认为1) 页面
      success: function (res) {
        // success
      }
    })
  }
})