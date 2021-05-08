/**
 * 请求网络
 */
function request( url, page, success, fail ) {
  if( typeof success != 'function' || typeof fail != 'function' ) {
    return
  }
  var app = getApp()
  wx.request( {
    url: url,
    data: {
      key: app.globalData.appkey,
      page: page,
      pagesize: app.globalData.pagesize
    },
    success: function( res ) {
      if( res.data.error_code == 0 ) {
        success( res.data )
      } else {
        fail( res.data.reason )
      }
    },
    fail: function() {
      fail( '网络错误' )
    }

  })
}

module.exports = {
  request: request
}
