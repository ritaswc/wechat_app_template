if (typeof wx === 'undefined') var wx = getApp().core;
var is_loading_more = false;
var is_no_more = false;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        cat_id: "",
        page: 1,
        cat_list: [], 
        goods_list: [],
        sort: 0,
        sort_type: -1,
        quick_icon:true,
    },

    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        this.loadData(options);
    },

    /**
     * 加载初始数据
     * */
    loadData: function (options) {
        var self = this;
        var cat_list = getApp().core.getStorageSync(getApp().const.CAT_LIST);
        var height_bar = "";
        if (options.cat_id) {
            for (var i in cat_list) {
                var in_list = false;
                if (cat_list[i].id == options.cat_id) {
                    cat_list[i].checked = true;
                    if (cat_list[i].list.length > 0) {
                        height_bar = "height-bar";
                    }
                }
                for (var j in cat_list[i].list) {
                    if (cat_list[i].list[j].id == options.cat_id) {
                        cat_list[i].list[j].checked = true;
                        in_list = true;
                        height_bar = "height-bar";
                    }
                }
                if (in_list)
                    cat_list[i].checked = true;
            }
        }
        if (options.goods_id){
            var goods_id = options.goods_id
        }
        self.setData({
            cat_list: cat_list,
            cat_id: options.cat_id || "",
            height_bar: height_bar,
            goods_id: goods_id || "",
        });
        self.reloadGoodsList();

    },
    catClick: function (e) {
        var self = this;
        var cat_id = "";
        var index = e.currentTarget.dataset.index;
        var cat_list = self.data.cat_list;
        for (var i in cat_list) {
            for (var j in cat_list[i].list) {
                cat_list[i].list[j].checked = false;
            }
            if (i == index) {
                cat_list[i].checked = true;
                cat_id = cat_list[i].id;
            } else {
                cat_list[i].checked = false;
            }
        }
        var height_bar = "";
        if (cat_list[index].list.length > 0) {
            height_bar = "height-bar";
        }
        self.setData({
            cat_list: cat_list,
            cat_id: cat_id,
            height_bar: height_bar,
        });
        self.reloadGoodsList();
    },
    quickNavigation:function(){
        var status = 0;
        this.setData({
            quick_icon:!this.data.quick_icon
        })  
        var store = this.data.store;
        var animationPlus = getApp().core.createAnimation({
          duration: 300,
          timingFunction: 'ease-out',
        });

        var x = -55;
        if (!this.data.quick_icon) {
            animationPlus.translateY(x).opacity(1).step();
        } else {
            animationPlus.opacity(0).step();
        }
        this.setData({
           animationPlus: animationPlus.export(),
        });
    },
    subCatClick: function (e) {
        var self = this;
        var cat_id = "";
        var index = e.currentTarget.dataset.index;
        var parent_index = e.currentTarget.dataset.parentIndex;
        var cat_list = self.data.cat_list;
        for (var i in cat_list) {
            for (var j in cat_list[i].list) {
                if (i == parent_index && j == index) {
                    cat_list[i].list[j].checked = true;
                    cat_id = cat_list[i].list[j].id;
                } else {
                    cat_list[i].list[j].checked = false;
                }
            }
        }
        self.setData({
            cat_list: cat_list,
            cat_id: cat_id,
        });
        self.reloadGoodsList();
    },

    allClick: function () {
        var self = this;
        var cat_list = self.data.cat_list;
        for (var i in cat_list) {
            for (var j in cat_list[i].list) {
                cat_list[i].list[j].checked = false;
            }
            cat_list[i].checked = false;
        }
        self.setData({
            cat_list: cat_list,
            cat_id: "",
            height_bar: "",
        });
        self.reloadGoodsList();
    }
    ,

    reloadGoodsList: function () {
        var self = this;
        is_no_more = false;
        self.setData({
            page: 1,
            goods_list: [],
            show_no_data_tip: false,
        });
        var cat_id = self.data.cat_id || "";
        var p = self.data.page || 1;
        getApp().request({
            url: getApp().api.default.goods_list,
            data: {
                cat_id: cat_id,
                page: p,
                sort: self.data.sort,
                sort_type: self.data.sort_type,
                goods_id: self.data.goods_id,
            },
            success: function (res) {
                if (res.code == 0) {
                    if (res.data.list.length == 0)
                        is_no_more = true;
                    self.setData({ page: (p + 1) });
                    self.setData({ goods_list: res.data.list });
                }
                self.setData({
                    show_no_data_tip: (self.data.goods_list.length == 0),
                });
            },
            complete: function () {
                //getApp().core.hideNavigationBarLoading();
            }
        });
    }
    ,

    loadMoreGoodsList: function () {
        var self = this;
        if (is_loading_more)
            return;
        self.setData({
            show_loading_bar: true,
        });
        is_loading_more = true;
        var cat_id = self.data.cat_id || "";
        var p = self.data.page || 2;
        var goods_id = self.data.goods_id;
        getApp().request({
            url: getApp().api.default.goods_list,
            data: {
                page: p,
                cat_id: cat_id,
                sort: self.data.sort,
                sort_type: self.data.sort_type,
                goods_id: goods_id
            },
            success: function (res) {
                if (res.data.list.length == 0)
                    is_no_more = true;
                var goods_list = self.data.goods_list.concat(res.data.list);
                self.setData({
                    goods_list: goods_list,
                    page: (p + 1),
                });
            },
            complete: function () {
                is_loading_more = false;
                self.setData({
                    show_loading_bar: false,
                });
            }
        });
    }
    ,

    onReachBottom: function () {
        getApp().page.onReachBottom(this);

        if (is_no_more)
            return;
        this.loadMoreGoodsList();
    }, 

    onShow: function (e) {
        getApp().page.onShow(this);

        var self = this;
        var list_page_reload = getApp().core.getStorageSync(getApp().const.LIST_PAGE_RELOAD);
        if (list_page_reload) {  //从首页进来，按分类刷新商品列表
            var list_page_options = getApp().core.getStorageSync(getApp().const.LIST_PAGE_OPTIONS);
            getApp().core.removeStorageSync(getApp().const.LIST_PAGE_OPTIONS);
            getApp().core.removeStorageSync(getApp().const.LIST_PAGE_RELOAD);
            var cat_id = list_page_options.cat_id || "";
            self.setData({
                cat_id: cat_id,
            });
            var cat_list = self.data.cat_list;
            for (var i in cat_list) {
                var in_list = false;
                for (var j in cat_list[i].list) {
                    if (cat_list[i].list[j].id == cat_id) {
                        cat_list[i].list[j].checked = true;
                        in_list = true;
                    } else {
                        cat_list[i].list[j].checked = false;
                    }
                }
                if (in_list || cat_id == cat_list[i].id) {
                    cat_list[i].checked = true;
                    if (cat_list[i].list && cat_list[i].list.length > 0) {
                        self.setData({
                            height_bar: "height-bar",
                        });
                    }
                }
                else {
                    cat_list[i].checked = false;
                }
            }

            self.setData({ cat_list: cat_list });
            self.reloadGoodsList();
        }
    },

    sortClick: function (e) {
        var self = this;
        var sort = e.currentTarget.dataset.sort;
        var default_sort_type = e.currentTarget.dataset.default_sort_type == undefined ? -1 : e.currentTarget.dataset.default_sort_type;
        var sort_type = self.data.sort_type;
        if (self.data.sort == sort) {
            if (default_sort_type == -1) {
                return;
            }
            if (self.data.sort_type == -1) {
                sort_type = default_sort_type;
            } else {
                sort_type = (sort_type == 0 ? 1 : 0);
            }
        } else {
            sort_type = default_sort_type;
        }

        self.setData({
            sort: sort,
            sort_type: sort_type,
        });
        self.reloadGoodsList();
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function(options) {
        getApp().page.onShareAppMessage(this);

        var user_info = getApp().getUser()
        var path = '/pages/list/list?user_id=' + user_info.id + '&cat_id='+ this.data.cat_id;
        return {
            path: path,
            success: function(res) {
            }
        }
    },
});