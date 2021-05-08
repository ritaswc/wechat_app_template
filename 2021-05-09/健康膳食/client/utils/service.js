const Promise = require('../libs/bluebird');
function promisify() {
    wx.pro = {} // wx.pro 下面挂载着返回 promise 的 wx.API

    // 普通的要转换的函数
    const functionNames = [
        'login',
        'getUserInfo',
        'navigateTo',
        'checkSession',
        'getStorageInfo',
        'removeStorage',
        'clearStorage',
        'getNetworkType',
        'getSystemInfo'
    ];
    functionNames.forEach(fnName => {
        wx.pro[fnName] = (obj = {}) => {
            return new Promise((resolve, reject) => {
                obj.success = function(res) {
                    console.log(`wx.${fnName} success`, res)
                    resolve(res)
                }
                obj.fail = function(err) {
                    console.error(`wx.${fnName} fail`, err)
                    reject(err)
                }
                wx[fnName](obj)
            })
        }
    })

    // 特殊改造的函数

    wx.pro.getStorage = key => {
        return new Promise((resolve, reject) => {
            wx.getStorage({
                key: key,
                success: res => {
                    resolve(res.data) // unwrap data
                },
                fail: err => {
                    resolve() // not reject, resolve undefined
                }
            })
        })
    }

    wx.pro.setStorage = (key, value) => {
        return new Promise((resolve, reject) => {
            wx.setStorage({
                key: key,
                data: value,
                success: res => {
                    resolve(value) // 将数据返回
                },
                fail: err => {
                    reject(err)
                }
            })
        })
    }

    wx.pro.request = options => {
        if (options.toast) {
            wx.showToast({
                title: options.toast.title || '加载中',
                icon: 'loading',
                duration: 10 * 60 * 1000
            })
        }

        return new Promise((resolve, reject) => {
            wx.request({
                url: options.url,
                method: options.method || 'GET',
                data: options.data,
                success: res => {
                    if (options.toast) {
                        wx.hideToast();
                    }
                    if (res.statusCode >= 400) {
                        console.error('wx.request fail [business]', options, res.statusCode, res.data)
                        reject(res)
                    } else {
                        console.log('wx.request success', options, res.data)
                        resolve(res.data)
                    }
                },
                fail: err => {
                    if (options.toast) {
                        wx.hideToast();
                    }
                    console.error('wx.request fail [network]', options, err)
                    reject(err)
                }
            })
        })

    }
}

promisify()

module.exports = Promise
