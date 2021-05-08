if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        load_more_count: 0,
        last_load_more_time: 0,
        is_loading: false,
        loading_class: "",
        cat_id: false,
        keyword: false,
        page: 1,
        limit: 20,
        pageCount: 0,
        goods_list: [],
        show_history: true,
        show_result: false,
        history_list: [],
        is_search: true,
        is_show: false,
        cats: [], //搜索分类
        default_cat: [], //默认搜索分类,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        this.cats();

    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function (options) {
        getApp().page.onReady(this);

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function (options) {
        getApp().page.onShow(this);
        var self = this;
        self.setData({
            history_list: self.getHistoryList(true),
        });
    },

    /**
     * 上拉加载更多
     */
    onReachBottom: function () {
        getApp().page.onReachBottom(this);
        var self = this;
        var page = self.data.page + 1;

        if(page <= self.data.pageCount) {
            self.setData({
                page: page
            })
            self.getGoodsList();
        }
    },

    // 获取搜索分类列表
    cats: function () {
        var self = this;
        getApp().request({
            url: getApp().api.default.cats,
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        cats: res.data.list,
                        default_cat: res.data.default_cat
                    })
                }
            }
        })
    },
    //改变搜索分类
    change_cat: function (e) {
        var self = this;
        var cats = self.data.cats;
        var catId = e.currentTarget.dataset.id
        for (var i in cats) {
            if (catId === cats[i].id) {
                var defaultCat = {
                    id: cats[i].id,
                    name: cats[i].name,
                    key: cats[i].key,
                    url: cats[i].url
                }
            }
        }
        self.setData({
            default_cat: defaultCat,
        })
    },
    // 下拉框展开|收起事件
    pullDown: function () {
        var self = this;
        var cats = self.data.cats;
        var default_cat = self.data.default_cat;

        for (var i in cats) {
            if (cats[i].id === default_cat.id) {
                cats[i].is_active = true
            } else {
                cats[i].is_active = false;
            }
        }

        self.setData({
            is_show: !self.data.is_show,
            cats: cats
        })

    },

    inputFocus: function () {
        var self = this;
        self.setData({
            show_history: true,
            show_result: false,
        });
    },
    inputBlur: function () {
        var self = this;
        if (self.data.goods_list.length > 0) {
            setTimeout(function () {
                self.setData({
                    show_history: false,
                    show_result: true,
                });
            }, 300);
        }
    },
    inputConfirm: function (e) {
        var self = this;
        var keyword = e.detail.value;
        if (keyword.length == 0)
            return;
        self.setData({
            page: 1,
            keyword: keyword,
            goods_list: [],
        });
        self.setHistory(keyword);
        self.getGoodsList();

    },
    searchCancel: function () {
        getApp().core.navigateBack({
            delta: 1,
        });
    },
    historyClick: function (e) {
        var self = this;
        var keyword = e.currentTarget.dataset.value;
        if (keyword.length == 0)
            return;
        self.setData({
            page: 1,
            keyword: keyword,
            goods_list: [],
        });
        self.getGoodsList();
    },

    getGoodsList: function () {
        var self = this;
        self.setData({
            show_history: false,
            show_result: true,
            is_search: true
        });
        // self.setData({
        //     page: 1,
        //     scroll_top: 0,
        // });
        // self.setData({
        //     goods_list: [],
        // });
        var data = {};
        if (self.data.cat_id) {
            data.cat_id = self.data.cat_id;
            self.setActiveCat(data.cat_id);
        }
        if (self.data.keyword) {
            data.keyword = self.data.keyword;
        }
        data.defaultCat = JSON.stringify(self.data.default_cat);
        data.page = self.data.page;
        self.showLoadingBar();
        self.is_loading = true;
        getApp().request({
            url: getApp().api.default.search,
            data: data,
            success: function (res) {
                if (res.code == 0) {
                    var goodsList = self.data.goods_list.concat(res.data.list);
                    self.setData({
                        goods_list: goodsList,
                        pageCount: res.data.page_count
                    });
                    if (res.data.list.length == 0) {
                        self.setData({
                            is_search: false
                        });
                    } else {
                        self.setData({
                            is_search: true
                        });
                    }
                }
                if (res.code == 1) {
                }
            },
            complete: function () {
                self.hideLoadingBar();
                self.is_loading = false;
            }
        });
    },
    getHistoryList: function (is_desc) {
        is_desc = is_desc || false;
        var history_list = getApp().core.getStorageSync(getApp().const.SEARCH_HISTORY_LIST);
        if (!history_list)
            return [];
        if (!is_desc) {
            return history_list;
        }
        var new_list = [];
        for (var i = history_list.length - 1; i >= 0; i--)
            new_list.push(history_list[i]);
        return new_list;
    },
    setHistory: function (keyword) {
        var self = this;
        var history_list = self.getHistoryList();
        history_list.push({
            keyword: keyword,
        });
        for (var i in history_list) {
            if (history_list.length <= 20)
                break;
            history_list.splice(i, 1);
        }
        getApp().core.setStorageSync(getApp().const.SEARCH_HISTORY_LIST, history_list);
    },

    getMoreGoodsList: function () {
        var self = this;
        var data = {};
        if (self.data.cat_id) {
            data.cat_id = self.data.cat_id;
            self.setActiveCat(data.cat_id);
        }
        if (self.data.keyword)
            data.keyword = self.data.keyword;
        data.page = self.data.page || 1;
        self.showLoadingMoreBar();
        self.setData({
            is_loading: true
        });
        self.setData({
            load_more_count: self.data.load_more_count + 1,
        });
        data.page = self.data.page + 1;
        data.defaultCat = self.data.default_cat;
        self.setData({ page: data.page });
        data.defaultCat = JSON.stringify(self.data.default_cat);
        getApp().request({
            url: getApp().api.default.search,
            data: data,
            success: function (res) {
                if (res.code == 0) {
                    var old_goods_list = self.data.goods_list;
                    if (res.data.list.length > 0) {
                        for (var i in res.data.list) {
                            old_goods_list.push(res.data.list[i]);
                        }
                        self.setData({
                            goods_list: old_goods_list
                        });
                    } else {
                        self.setData({
                            page: data.page - 1,
                        });
                    }
                }
                if (res.code == 1) {
                }
            },
            complete: function () {
                self.setData({
                    is_loading: false
                });
                self.hideLoadingMoreBar();
            }
        });
    },

    showLoadingBar: function () {
        var self = this;
        self.setData({
            loading_class: "active",
        });
    },
    hideLoadingBar: function () {
        var self = this;
        self.setData({
            loading_class: "",
        });
    },
    showLoadingMoreBar: function () {
        var self = this;
        self.setData({
            loading_more_active: "active",
        });
    },
    hideLoadingMoreBar: function () {
        var self = this;
        self.setData({
            loading_more_active: "",
        });
    },
    deleteSearchHistory: function () {
        var self = this;
        self.setData({
            history_list: null,
        });
        getApp().core.removeStorageSync(getApp().const.SEARCH_HISTORY_LIST);
    },

});