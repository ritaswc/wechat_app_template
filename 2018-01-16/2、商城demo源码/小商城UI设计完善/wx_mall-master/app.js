//app.js
App({
    onLaunch: function () {
        /*监听小程序初始化	当小程序初始化完成时，会触发onLaunch（全局只触发一次）*/
        //调用API从本地缓存中获取数据
        var logs = wx.getStorageSync('logs') || []
        logs.unshift(Date.now())
        wx.setStorageSync('logs', logs)
    },
    onShow: function(){
        /*监听小程序显示	当小程序启动，或从后台进入前台显示，会触发onShow*/
        console.log("小程序启动，或从后台进入前台显示")
    },
    onHide: function(){
        /*监听小程序隐藏	当小程序从前台进入后台，会触发onHide*/
        console.log("小程序从前台进入后台")
    },
    getUserInfo:function(cb){
        var that = this
        if(this.globalData.userInfo){
            typeof cb == "function" && cb(this.globalData.userInfo)
        }else{
            //调用登录接口
            wx.login({
                success: function () {
                    wx.getUserInfo({
                        success: function (res) {
                            that.globalData.userInfo = res.userInfo
                            typeof cb == "function" && cb(that.globalData.userInfo)
                        }
                    })
                }
            })
        }
    },
    globalData:{
        userInfo:null
    },
    data : {

        mask : true,
        cart : true
    }
});