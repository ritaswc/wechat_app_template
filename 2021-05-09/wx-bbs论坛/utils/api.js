var Promise = require("./bluebird.js")

/**
 * 获取发帖列表
 */
function myarticles(data, filepath, cb) {
    wx.uploadFile({
        url: 'http://apptest.vzan.com/minisnsapp/myarticles',
        filePath: filePath,
        name: 'file',
        // header: {}, // 设置请求的 header
        formData: data, // HTTP 请求中其他额外的 form data
        success: function (res) {
            let result = JSON.parse(res.data);
            if (result.result) {
                cb.success(result);
            }
        }
    })
}

/**
 * 搜索帖子
 */
function getartlistbykeyword(data, filepath, cb) {
    wx.uploadFile({
        url: 'http://apptest.vzan.com/minisnsapp/getartlistbykeyword',
        filePath: filepath,
        name: 'file',
        // header: {}, // 设置请求的 header
        formData: data, // HTTP 请求中其他额外的 form data
        success: function (res) {
            let result = JSON.parse(res.data);
            if (result.result) { 
                cb.success(result);
            }
        },
        fail: function () {
            cb.fail()
        },
        complete: function () {
            cb.complete();
        }
    })
}

/**
 * 获取用户信息
 */
function userInfo(data, filepath, cb) {
    wx.uploadFile({
      url: 'https://snsapi.vzan.com/minisnsapp/userinfo',
      filePath: filepath,
      name:'file',
      // header: {}, // 设置请求的 header
      formData: data, // HTTP 请求中其他额外的 form data
      success: function(res){
        let result = JSON.parse(res.data)
        if (result.result == true) {
            console.log("获取用户信息成功", result)
            cb(result)
        } else {
            console.log("获取用户信息失败")
        }
      }
    })
}

/**
 * 微信API
 */
function wxApi(wxFn) {
    return function(data = {}) {
        return new Promise((resolve, reject) => {
            data.success = function(res) { // 成功
                resolve(res)
            }
            data.fail = function(res) { // 失败 
                reject(res)
            }
            wxFn(data)
        })
    }
}


module.exports = {
    "myarticles": myarticles,
    "getartlistbykeyword":getartlistbykeyword,
    "userInfo":userInfo,
    "wxApi":wxApi,
}