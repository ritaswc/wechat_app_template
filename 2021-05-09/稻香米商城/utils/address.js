const API_URL = 'http://127.0.0.1:3000'

function postAddress (data) {
  wx.request({
    method: 'POST',
    url: `${API_URL}/addresses/new`,
    data: Object.assign({}, data),
    header: { 'Content-Type': 'application/json' },
    success (res) {
      console.log('success')
      wx.setStorageSync('addrss', res.data)
      wx.navigateBack()
    },
    fail (e) {
      console.log('error')
      console.error(e)
    }
  })
}

function getLocation () {
  wx.getLocation({
    type: 'gcj02', //返回可以用于 wx.openLocation 的经纬度
    success: function(res) {
      var latitude = res.latitude
      var longitude = res.longitude
      // TODO
    }
  })
}



module.exports = {
}
