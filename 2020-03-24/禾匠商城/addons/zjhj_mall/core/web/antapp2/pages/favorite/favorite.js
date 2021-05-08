if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        swiper_current: 0,
        goods: {
            list: null,
            is_more: true,
            is_loading: false,
            page: 1,
        },
        topic: {
            list: null,
            is_more: true,
            is_loading: false,
            page: 1,
        }
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) { getApp().page.onLoad(this, options);
        this.loadGoodsList({
            reload: true,
            page: 1,
        });
        this.loadTopicList({
            reload: true,
            page: 1,
        });

    },


    tabSwitch: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        self.setData({
            swiper_current: index,
        });
    },
    swiperChange: function (e) {
        var self = this;
        self.setData({
            swiper_current: e.detail.current,
        });
    },

    loadGoodsList: function (args) {
        var self = this;
        if (self.data.goods.is_loading)
            return;
        if (args.loadmore && !self.data.goods.is_more)
            return;
        self.data.goods.is_loading = true;
        self.setData({
            goods: self.data.goods,
        });
        getApp().request({
            url: getApp().api.user.favorite_list,
            data: {
                page: args.page,
            },
            success: function (res) {
                if (res.code == 0) {
                    if (args.reload) {
                        self.data.goods.list = res.data.list;
                    }
                    if (args.loadmore) {
                        self.data.goods.list = self.data.goods.list.concat(res.data.list);
                    }
                    self.data.goods.page = args.page;
                    self.data.goods.is_more = res.data.list.length > 0;
                    self.setData({
                        goods: self.data.goods,
                    });
                } else {
                }
            },
            complete: function () {
                self.data.goods.is_loading = false;
                self.setData({
                    goods: self.data.goods,
                });
            }
        });

    },

    goodsScrollBottom: function () {
        var self = this;
        self.loadGoodsList({
            loadmore: true,
            page: self.data.goods.page + 1,
        });
    },

    loadTopicList: function (args) {
        var self = this;
        if (self.data.topic.is_loading)
            return;
        if (args.loadmore && !self.data.topic.is_more)
            return;
        self.data.topic.is_loading = true;
        self.setData({
            topic: self.data.topic,
        });
        getApp().request({
            url: getApp().api.user.topic_favorite_list,
            data: {
                page: args.page,
            },
            success: function (res) {
                if (res.code == 0) {
                    if (args.reload) {
                        self.data.topic.list = res.data.list;
                    }
                    if (args.loadmore) {
                        self.data.topic.list = self.data.topic.list.concat(res.data.list);
                    }
                    self.data.topic.page = args.page;
                    self.data.topic.is_more = res.data.list.length > 0;
                    self.setData({
                        topic: self.data.topic,
                    });
                } else {
                }
            },
            complete: function () {
                self.data.topic.is_loading = false;
                self.setData({
                    topic: self.data.topic,
                });
            }
        });
    },

    topicScrollBottom: function () {
        var self = this;
        self.loadTopicList({
            loadmore: true,
            page: self.data.topic.page + 1,
        });
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function (options) { getApp().page.onReady(this);

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function (options) { getApp().page.onShow(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function (options) { getApp().page.onHide(this);

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function (options) { getApp().page.onUnload(this);

    },

    onReachBottom: function (options) { getApp().page.onReachBottom(this);
        var self = this;
        self.loadMoreGoodsList();
    },
});