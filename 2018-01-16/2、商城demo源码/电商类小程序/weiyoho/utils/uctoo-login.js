//常量
var DOMAIN = 'https://www.weiyoho.com';
var mp_id = 'd8d49a5800362843f29833e03038a72a';

function login() {
  var that = this
  wx.checkSession({
    success: function (res) {
      var login = wx.getStorageSync('login')
      if (!login) {
        console.log('!login')
         that.loginUct()
      } else {
        //获取用户信息
        console.log('@login')
        // var userInfo = wx.getStorageSync('userInfo')
        // checklogin();
        //that.loginUct();
      }

    },
    fail: function (res) {
      //登录态过期
      console.log('@!login')
      console.log(res)
      that.loginUct();
    }
  })
}
//向后台检验登录过期情况
function checklogin() {
  //登录态未过期
  var that=this;
  var login = wx.getStorageSync('login')
  wx.request({
    url: DOMAIN + `/mpbase/wxapp/checkSession?` + 'session_3rd=' + login.session_3rd,
    method: 'GET',
    success: function (res) {
      console.log(res)
      if (!res.data) {
        that.loginUct()
      } else {
        // var userInfo = wx.getStorageSync('userInfo')
      }
    },
    fail: function () {

    },
    complete: function () {
      // complete
    }
  })
}
//登录函数
function loginUct() {
  console.log('login')
  var that = this;
  var code;
  wx.login({
    success: function (res) {
      console.log(res.code)
      code = res.code
      wx.getUserInfo({
        success: function (res) {
          wx.request({
            url: DOMAIN + '/mpbase/wxapp/onLogin/mp_id/' + mp_id,
            data: {
              code: code,
              encryptedData: res.encryptedData,
              encryptData: res.encryptData,
              iv: res.iv,
              rawData: res.rawData,
              signature: res.signature
            },
            header: {
              'content-type': 'application/json',
            },
            method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
            // header: {}, // 设置请求的 header
            success: function (res) {
              console.log(res)
              wx.setStorageSync('login',res.data)
            },
            fail: function () {
              // fail
            },
            complete: function () {
              // complete
            }
          })
        },
        fail: function () {
          // fail
        },
        complete: function () {
          // complete
        }
      })
    },
    fail: function () {
      // fail
    },
    complete: function () {
      // complete
    }
  })
  // 老万登录
  //调用登录接口
  // wx.login({
  //   success: function (res) {
  //     if (res.code) {
  //       //发起网络请求，通过jscode获取session
  //       wx.request({
  //         url: DOMAIN+`/mpbase/wxapp/onLogin`,
  //         data: {
  //           mp_id: mp_id,
  //           code: res.code
  //         },
  //         success: function (res) {
  //           console.log("onLogin", res)
  //           wx.setStorageSync("session_3rd", res.data.session_3rd);
  //         }
  //       })



  //       wx.getUserInfo({
  //         success: function (res) {
  //           wx.setStorageSync('userInfo', res.userInfo)
  //           var userInfo = res.userInfo
  //           var session_3rd = wx.getStorageSync('session_3rd')
  //           console.log("session_3rd", session_3rd)
  //           wx.request({
  //             url: DOMAIN+`/mpbase/wxapp/setUserInfo`,
  //             data: {
  //               mp_id: mp_id,
  //               userInfo: userInfo,
  //               encryptedData: res.encryptedData,
  //               iv: res.iv,
  //               rawData: res.rawData,
  //               signature: res.signature,
  //               session_3rd: session_3rd
  //             },
  //             method: 'POST',
  //             success: function (res) {
  //               console.log("setUserInfo", res)
  //             },
  //             fail: function () {
  //               // fail
  //             },
  //             complete: function () {
  //               // complete
  //             }
  //           })
  //         }
  //       })
  //     } else {
  //       console.log('获取用户登录态失败！' + res.errMsg)
  //     }
  //   }
  // })
}

module.exports = {
  login: login,
  loginUct: loginUct
}