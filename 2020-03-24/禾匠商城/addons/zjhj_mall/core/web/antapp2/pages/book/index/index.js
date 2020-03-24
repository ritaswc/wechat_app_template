if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        cid: 0,
        scrollLeft: 600,
        scrollTop: 0,
        emptyGoods: 0,
        page: 1,
        pageCount: 0,
        cat_show: 1,
        cid_url: false,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) { getApp().page.onLoad(this, options);
        var self = this;
        self.systemInfo = getApp().core.getSystemInfoSync();

        if (options.cid) {
            var cid = options.cid;
            this.setData({
                cid_url: false
            })
            this.switchNav({ 'currentTarget': { 'dataset': { 'id': options.cid } } });
            return;
        } else {
            this.setData({
                cid_url: true
            })
        }
        this.loadIndexInfo(this);

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

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function (options) { getApp().page.onPullDownRefresh(this);

    },
    
    /**
     * 预约首页加载
     */
    loadIndexInfo: function () {
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.book.index,
            method: "get",
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.hideLoading();
                    self.setData({
                        cat: res.data.cat,
                        goods: res.data.goods.list,
                        cat_show: res.data.cat_show,
                        page:res.data.goods.page,
                        pageCount: res.data.goods.page_count,
                    });

                    if (!res.data.goods.list.length > 0) {
                        self.setData({
                            emptyGoods: 1
                        })
                    }
                }
            }
        });
    },
    /**
     * 顶部导航事件
     */
    switchNav: function (e) {
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });

        var cid = 0;
        if (cid == e.currentTarget.dataset.id && e.currentTarget.dataset.id != 0) return;
        cid = e.currentTarget.dataset.id;

        if(this.data.__platform == 'wx'){
            var windowWidth = self.systemInfo.windowWidth
            var offsetLeft = e.currentTarget.offsetLeft
            var scrollLeft = self.data.scrollLeft;

            if (offsetLeft > windowWidth / 2) {
                scrollLeft = offsetLeft
            } else {
                scrollLeft = 0
            }
            self.setData({
                scrollLeft: scrollLeft,
            });    
        }
        if(this.data.__platform == 'my'){
            var cat = self.data.cat;
            var st = true;
            for (var i = 0; i < cat.length; ++i) {
                if (cat[i].id === e.currentTarget.id) {
                    st = false;
                    if (i >= 1) {
                        self.setData({
                            toView: cat[i - 1].id
                        })
                    } else {
                        self.setData({
                            toView: '0'
                        })
                    }
                    break;
                }
            }
            if (st) {
                self.setData({
                    toView: '0'
                })
            }
        }

        self.setData({
            cid: cid,
            page: 1,
            scrollTop: 0,
            emptyGoods: 0,
            goods: [],
            show_loading_bar: 1,
        })

        getApp().request({
            url: getApp().api.book.list,
            method: "get",
            data: { cid: cid },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.hideLoading();
                    var goods = res.data.list;
                    if (res.data.page_count >= res.data.page) {
                        self.setData({
                            goods: goods,
                            page: res.data.page,
                            pageCount: res.data.page_count,
                            show_loading_bar: 0,
                        });
                    } else {
                        self.setData({
                            emptyGoods: 1,
                        });
                    }
                }
            }
        });
    },
    /**
     * 此上拉非微信原生上拉
     * 上拉加载
     */
    onReachBottom: function (e) {
        var self = this;
        var page = self.data.page;
        var pageCount = self.data.pageCount;
        var cid = self.data.cid;

        self.setData({
            show_loading_bar: 1
        });

        if (++page > pageCount) {
            self.setData({
                emptyGoods: 1,
                show_loading_bar: 0
            })
            return;
        }

        getApp().request({
            url: getApp().api.book.list,
            method: "get",
            data: { page: page, cid: cid },
            success: function (res) {
                if (res.code == 0) {
                    var goods = self.data.goods;
                    Array.prototype.push.apply(goods, res.data.list);

                    self.setData({
                        show_loading_bar: 0,
                        goods: goods,
                        page: res.data.page,
                        pageCount: res.data.page_count,
                        emptyGoods: 0
                    })
                }
            }
        });
    },
    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var res = {
            path: "/pages/book/index/index?user_id=" + self.data.__user_info.id + '&cid=',
            success: function (e) { },
        };
        return res;
    },
})