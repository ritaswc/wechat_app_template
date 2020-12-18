var app = getApp();
var bookNum = 6;
Page({
    data: {
        loading: true
    },
    onShow: function () {
        var _this = this;
        this.setData({
            bookData: app.globalData
        })
        wx.getSystemInfo({
            success: function (res) {
                _this.setData({windowHeight: res.windowHeight}) //获取设备高度

            }
        })
        this.setData({
            initLoading: true
        })
    },

    pullLoad: function () {
        var _this = this;
        this.setData({
            loading: false
        })
        bookNum += 6;
        wx.request({
            url: "https://api.douban.com/v2/book/search?count=" + bookNum + "&q=" + app.pullData,
            success: function (res) {
                _this.setData({
                    bookData: res.data.books
                })
                _this.setData({
                    loading: true
                })
            }
        })
    }
})