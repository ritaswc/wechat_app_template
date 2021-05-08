Page({
    data: {
        
    },
    onUnload: function () {

    },
    onLoad: function () {
        
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

    },
    handleHome: function () {
        wx.switchTab({
            url: '/view/index/index'
        })
    },
    handleOrder: function () {
        wx.redirectTo({
            url: '/view/order/index'
        });
    }
});
