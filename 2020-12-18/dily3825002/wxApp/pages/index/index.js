var app = getApp();
var index = {

    data: {
        topTitle: "差评黑市",
        flag: true,
        loading: false,
        imgUrls: [
            "../../images/banner_1.jpg",
            "../../images/banner_2.jpg",
            "../../images/banner_3.jpg"
        ],
        swiper: {
            indicatorDots: true,
            autoplay: false,
            interval: 2000,
            duration: 1000
        }
    },

    onReady: function () {

        var _this = this;
        this.changeText(); //标题切换
        const bookName = "css3";
        wx.request({
            url: "https://api.douban.com/v2/book/search?count=4&q=" + bookName,
            success: function (res) {
                _this.setData({
                    initBookData: res.data
                });
                _this.setData({
                    loading: true
                });
            },
            fail: function () {
                console.log("xxx")
            }
        })
    },

    changeText: function () {

        var _this = this;
        var isOkay = true;
        setInterval(function () {
            if (isOkay) {
                _this.setData({
                    flag: false
                })
            } else {
                _this.setData({
                    flag: true
                })
            }
            isOkay = !isOkay;
        }, 1000)
    },

    search: function (e) {

        var _this = this;
        app.pullData = e.detail.value
        this.setData({
            bookName: e.detail.value   //获取搜索的书名
        })
        _this.data.bookName.trim() &&
        wx.request({
            url: "https://api.douban.com/v2/book/search",
            data: {
                q: _this.data.bookName,   //书名
                count: 6                 //显示数量
            },

            success: function (res) {
                app.globalData = res.data;  //数据绑在全局上
                res.data && wx.navigateTo({  //有数据回来再跳转
                    url: "../bookList/bookList"
                })
            },

            fail: function () {
                console.log("checkout")
            }
        })

    }
};

Page(index);
