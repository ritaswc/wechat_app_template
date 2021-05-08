if (typeof wx === 'undefined') var wx = getApp().core;
// pages/video/video-list.js
var app = getApp();
var api = app.api;
var is_loading_more = false;
var is_no_more = false;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        page: 1,
        video_list: [],
        url: '',
        hide: 'hide',
        show: false,
        animationData: {},
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        app.page.onLoad(this, options);
        var self = this;
        self.loadMoreGoodsList();
        is_loading_more = false;
        is_no_more = false;
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function() {

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {
        app.page.onShow(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {
        app.page.onHide(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {
        app.page.onUnload(this);
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function() {

    },

    loadMoreGoodsList: function() {
        var self = this;
        if (is_loading_more)
            return;
        self.setData({
            show_loading_bar: true,
        });
        is_loading_more = true;
        var p = self.data.page;
        app.request({
            url: api.default.video_list,
            data: {
                page: p,
            },
            success: function(res) {
                if (res.data.list.length == 0)
                    is_no_more = true;
                var video_list = self.data.video_list.concat(res.data.list);
                self.setData({
                    video_list: video_list,
                    page: (p + 1),
                });
            },
            complete: function() {
                is_loading_more = false;
                self.setData({
                    show_loading_bar: false,
                });
            }
        });
    },
    play: function(e) {
        var index = e.currentTarget.dataset.index; //获取视频链接
        var video_all = getApp().core.createVideoContext('video_' + this.data.show_video);
        video_all.pause();
        this.setData({
            show_video: index,
            show: true
        });
        return;
        var videoContext = getApp().core.createVideoContext('video_' + index);
        videoContext.play();
    },
    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function() {

        var self = this;
        if (is_no_more)
            return;
        self.loadMoreGoodsList();
    },

    /**
     * 用户点击右上角分享
     */
    // onShareAppMessage: function () {

    // }

    more: function(e) {
        var self = this;
        var index = e.target.dataset.index;
        var video_list = self.data.video_list;
        var animation = getApp().core.createAnimation({
            duration: 1000,
            timingFunction: 'ease',
        })

        this.animation = animation
        if (video_list[index].show != -1) {
            animation.rotate(0).step();
            video_list[index].show = -1;
            self.setData({
                video_list: video_list,
                animationData: this.animation.export()
            });
        } else {
            animation.rotate(0).step();
            video_list[index].show = 0;
            self.setData({
                video_list: video_list,
                animationData: this.animation.export()
            });
        }
    }
})