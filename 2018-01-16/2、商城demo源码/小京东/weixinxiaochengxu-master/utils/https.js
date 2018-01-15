var rootDocment = 'https://www.leimingtech.com/front/';//  前缀
var auto = 'loginapi/getToken'
function req(url, data, callback) {
  var CuserInfo = wx.getStorageSync('CuserInfo');
  if (CuserInfo.token) {  //如果有token，就把token放到header
    wx.request({
      url: rootDocment + url,
      data: data,
      method: 'GET',    //大写
      header: { 'Content-Type': 'application/json', 'token': CuserInfo.token },
      success(res) {
        if (res.data.result == 0) {  //token失效 用code换下token
          console.log("token失效啦啦啦啦!")
          var code = CuserInfo.code
          if (code) {
            wx.request({  //获取新token
              url: rootDocment + auto,
              data: {code: code},
              method: 'GET',
              success: function (res) {
                if (res.data == 0) {  //跳转到login
                  wx.redirectTo({   //不一定走
                    url: '../login/login',
                  })
                } else {
                  console.log(res)
                  var newtoken = res.data.token  //重新请求 并保存新token
                  CuserInfo.token=newtoken
                   wx.setStorageSync('CuserInfo', CuserInfo);   //重新保存用户信息
                  wx.request({
                    url: rootDocment + url,
                    data:  data ,
                    method: 'GET',
                    header: { 'Content-Type': 'application/json', 'token': newtoken },
                    success: function (res) {
                      console.log("成功")
                      callback(null, res)
                    },
                    fail(e) {
                       console.log("失败")
                      console.error(e)
                      callback(e)
                    }
                  })
                }
              },
            })
          }
        }else{
        callback(null, res)
        }
      },
      fail(e) {
        console.error(e)
        callback(e)
      }
    })
  } else {
    wx.request({
      url: rootDocment + url,
      data: data,
      method: 'GET',    //大写
      header: { 'Content-Type': 'application/json' },
      success(res) {
        callback(null, res)
      },
      fail(e) {
        console.error(e)
        callback(e)
      }
    })
  }
}


//不需要token的请求
function req2(url, data, callback) {
    wx.request({
      url: rootDocment + url,
      data: data,
      method: 'GET',    //大写
      header: { 'Content-Type': 'application/json'},
      success(res) {
        callback(null, res)
      },
      fail(e) {
        console.error(e)
        callback(e)
      }
    })
}
module.exports = {
  req: req
}  