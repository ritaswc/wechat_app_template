var config = require('utils/config.js');

App({
    onLaunch: function () {
        var that = this;
        wx.login({
            success: function (res) {
                that.globalData.loginRes = res;
            }
        });
    },

    /* 获取userinfo */
    getUserInfo: function (cb) {
        var that = this;
        if (that.globalData.userInfo) {
            typeof cb == "function" && cb(that.globalData.userInfo)
        } else {
            that.libLogin(cb);
        }
    },

    /* 图书馆登录 */
    libLogin: function (cb) {
        var that = this;
        var res = this.globalData.loginRes;
        wx.getUserInfo({
            success: function (r) {
                wx.request({
                    url: config.baseUrl + '/user/login',
                    data: {
                        code: res.code,
                        iv: r.iv,
                        encryptedData: r.encryptedData
                    },
                    header: { "Content-Type": "application/x-www-form-urlencoded" },
                    method: 'POST',
                    success: function (res) {
                        that.globalData.userInfo = res.data
                        wx.setStorageSync('token', that.globalData.userInfo.token)
                        typeof cb == "function" && cb(that.globalData.userInfo)
                    }
                })
            }
        })
    },
    baseUrl: config.baseUrl,
    provinces: config.provinces,
    schools: config.schools,
    codes: config.codes,
    globalData: {
        userInfo: null,
        loginRes: null,
    }
})
