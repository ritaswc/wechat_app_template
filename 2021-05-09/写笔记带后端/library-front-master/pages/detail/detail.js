var app = getApp();
Page({
    data: {
        book_author: "",
        code: "",
        books: [],
        book_name: "加载中...",
        description: {},
        mounted: false,
        school: "",
        is_faved: false,
        marc_no: "",
        ctrl_no: '',
        //页面配置
        scroll_height: 0,
        winWidth: 0,
        winHeight: 0,
        currentTab: 0
    },

    onPullDownRefresh: function () {
        wx.stopPullDownRefresh();
    },
    /* 图书收藏事件 */
    favBook: function () {
        this.setData({
            is_faved: true
        });
        var fav = {}
        fav["marc_no"] = this.data.marc_no;
        fav["book_name"] = this.data.book_name;
        fav["book_author"] = this.data.book_author;
        fav["school"] = this.data.school;
        fav["code"] = this.data.code;
        fav['ctrl_no'] = this.data.ctrl_no;
        var favs = wx.getStorageSync('favs') || []
        favs.unshift(fav)
        wx.setStorageSync('favs', favs)
        wx.showToast({
            title: '收藏成功',
            icon: 'success',
            duration: 1500
        });
    },

    /* 图书取消收藏事件 */
    unfavBook: function () {
        var favs = wx.getStorageSync('favs') || [];
        for (var i = favs.length - 1; i >= 0; i--) {
            if (favs[i].marc_no === this.data.marc_no && favs[i].school === this.data.school) {
                favs.splice(i, 1);
            }
        };
        wx.setStorageSync('favs', favs);
        this.setData({
            is_faved: false
        });
        wx.showToast({
            title: '取消收藏',
            icon: 'success',
            duration: 1500
        });
    },

    /* 图书是否被收藏事件 */
    searchFav: function (marc_no, favs) {
        for (var i = 0; i < favs.length; i++) {
            if (favs[i].marc_no === marc_no) {
                return true;
            }
        };
        return false;
    },

    /* 页面加载 */
    onLoad: function (options) {
        var that = this;
        var favs = wx.getStorageSync('favs') || []
        var queryUrl = app.baseUrl + '/search/detail'
        var headerObj = {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
        that.setData({
            marc_no: options.marc_no,
            is_faved: that.searchFav(options.marc_no, favs),
            school: options.school,
            ctrl_no: options.ctrl_no
        });
        var data = {
            marc_no: options.marc_no,
            school: that.data.school,
            ctrl_no: options.ctrl_no
        }
        setTimeout(function () {
            wx.request({
                url: queryUrl,
                method: "POST",
                data: data,
                header: headerObj,
                success: function (res) {
                    that.setData({
                        books: res.data.books,
                        code: res.data.code,
                        book_author: res.data.book_author,
                        book_name: res.data.book_name,
                        description: res.data.description,
                        mounted: true
                    });
                }
            });
            //获取系统信息
            wx.getSystemInfo({
                success: function (res) {
                    that.setData({
                        winWidth: res.windowWidth,
                        winHeight: res.windowHeight,
                        scroll_height: res.windowHeight * 0.67
                    });
                }
            });
        }, 1000);
    },

    /* tab事件 */
    bindChange: function (e) {
        var that = this;
        that.setData({
            currentTab: e.detail.current
        });
    },

    /* 改变当前tab事件 */
    swichNav: function (e) {
        var that = this;
        if (this.data.currentTab === e.target.dataset.current) {
            return false;
        } else {
            that.setData({
                currentTab: e.target.dataset.current
            })
        }
    },

    /* 返回首页 */
    returnIndex: function () {
        wx.navigateBack()
    }
})