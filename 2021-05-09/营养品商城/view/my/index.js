Page({
    data: {
        userInfo: {}
    },
    onUnload: function () {

    },
    onLoad: function () {
        this.setData({
            userInfo: getApp().globalData.userInfo
        });
    },
    onReady: function () {

    },
    onShow: function () {

    },
    onHide: function () {

    },
    onPullDownRefresh: function () {

    },
    onReachBottom: function () {

    },
    onShareAppMessage: function () {

    }
});
