var inpuData = '';
Page({
  data: {
    winWidth: '',
    winHeight: '',
    name: '',
  },
  onLoad: function (options) {

    wx.getSystemInfo({
      success: (res) => {
        this.setData(
          {
            winWidth: res.windowWidth,
            winHeight: res.windowHeight,
            name: options.name,
          });
      }
    })
  },
  bindInput: function(e)
  {
    inpuData = e.detail.value
  }
})