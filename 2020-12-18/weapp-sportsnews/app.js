//app.js
App({
    onLaunch: function() {
        //调用API从本地缓存中获取数据
        var logs = wx.getStorageSync('logs') || []
        logs.unshift(Date.now())
        wx.setStorageSync('logs', logs)

        //获取用户信息
        // this.getUserInfo(function(userInfo) {
        //     console.log(userInfo);
        // });
        // 
        this.bindNetworkChange();//监听网络变化事件
    },
    getUserInfo: function(cb) {
        var that = this
        if (this.globalData.userInfo) {
            typeof cb == "function" && cb(this.globalData.userInfo)
        } else {
          //调用登录接口
            wx.login({
            success: function() {
                wx.getUserInfo({
                    success: function(res) {
                        that.globalData.userInfo = res.userInfo
                        typeof cb == "function" && cb(that.globalData.userInfo)
                    }
                })
            }
            })
        }
    },
    dealNetworkData: function(res) {
        // console.log(res.networkType)
        this.globalData.networkType = res.networkType;
        if(res.networkType == 'none') {
            wx.showModal({
                title: "提示",
                content: "当前网略异常，请检查网略并重新刷新",
                showCancel: false,
                confirmText: "知道了",
            });
        }    
    },
    bindNetworkChange: function() {
        var that = this;
        wx.onNetworkStatusChange(function(res) {
            that.dealNetworkData(res);
        });
    },
    getNetworkType: function(cb) {
        var that = this;
        wx.getNetworkType({
        success: function(res) {
            typeof cb == "function" ? cb(that.globalData.networkType) : that.dealNetworkData(res)
        }
        });
        return that.globalData.networkType;
    },

    globalData: {
        userInfo: null,
        networkType: 'none',
    }
})