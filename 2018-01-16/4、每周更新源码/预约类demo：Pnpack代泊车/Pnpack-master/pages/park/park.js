Page({
  data: {
    pointList:[]
  },
  onLoad: function (options) {
    var vm = this;
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/parking/point-list',
      data: {
        openid: wx.getStorageSync('openid'),
        page_index: 1
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        console.log(res.data.data)
        vm.setData({
          pointList:res.data.data
        })
      }
    })
  },
  bindViewTapPark: function (e) {
    var id = e.currentTarget.id;
    wx.navigateTo({
      url: '../park/summary/summary?id='+id
    })
  }
})