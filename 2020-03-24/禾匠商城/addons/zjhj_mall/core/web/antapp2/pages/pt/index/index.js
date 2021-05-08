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
        page_count: 0,
        pt_url: false,
        page: 1,
        is_show: 0,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        this.systemInfo = getApp().core.getSystemInfoSync()
        var store = getApp().core.getStorageSync(getApp().const.STORE);
        this.setData({
            store: store,
        });

        var self = this;
        if (options.cid) {
            var cid = options.cid;
            this.setData({
                pt_url: false
            })
            getApp().core.showLoading({
                title: "正在加载",
                mask: true,
            });
            getApp().request({
                url: getApp().api.group.index,
                method: "get",
                success: function (res) {
                    self.switchNav({
                        'currentTarget': {
                            'dataset': {
                                'id': options.cid
                            }
                        }
                    });
                    if (res.code == 0) {
                        var block = {
                            data: {
                                pic_list: res.data.ad
                            }
                        }
                        self.setData({
                            banner: res.data.banner,
                            ad: res.data.ad,
                            page: res.data.goods.page,
                            page_count: res.data.goods.page_count,
                            block: block
                        });
                    }
                }
            });
            return;
        } else {
            this.setData({
                pt_url: true
            })
        }
        this.loadIndexInfo(this);
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
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function (options) {
        getApp().page.onHide(this);

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function (options) {
        getApp().page.onUnload(this);

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function (options) {
        getApp().page.onPullDownRefresh(this);

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function (options) {
        getApp().page.onReachBottom(this);
        var self = this
        self.setData({
            show_loading_bar: 1
        });

        if (self.data.page < self.data.page_count) {
            self.setData({
                page: self.data.page + 1
            })
            self.getGoods(self);
        } else {
            self.setData({
                is_show: 1,
                emptyGoods: 1,
                show_loading_bar: 0
            })
        }
    },
    /**
     * 拼团首页加载
     */
    loadIndexInfo: function (e) {
        var self = e;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.group.index,
            method: "get",
            data: {
                page: self.data.page,
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.hideLoading();
                    var block = {
                        data: {
                            pic_list: res.data.ad
                        }
                    }
                    self.setData({
                        cat: res.data.cat,
                        banner: res.data.banner,
                        ad: res.data.ad,
                        goods: res.data.goods.list,
                        page: res.data.goods.page,
                        page_count: res.data.goods.page_count,
                        block: block
                    });
                    if (res.data.goods.row_count <= 0) {
                        self.setData({
                            emptyGoods: 1,
                        })
                    }
                }
            }
        });
    },
    getGoods(e) {
        var self = e;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.group.list,
            method: "get",
            data: {
                page: self.data.page,
                cid: self.data.cid
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.hideLoading();
                    self.data.goods = self.data.goods.concat(res.data.list);
                    self.setData({
                        goods: self.data.goods,
                        page: res.data.page,
                        page_count: res.data.page_count,
                        show_loading_bar: 0
                    });
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
        var cid = e.currentTarget.dataset.id;
        self.setData({
            cid: cid
        })
        if (typeof my === 'undefined') {
            var windowWidth = this.systemInfo.windowWidth
            var offsetLeft = e.currentTarget.offsetLeft
            var scrollLeft = this.data.scrollLeft;
            if (offsetLeft > windowWidth / 2) {
                scrollLeft = offsetLeft
            } else {
                scrollLeft = 0
            }
            self.setData({
                scrollLeft: scrollLeft,
            })
        } else {
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
            is_show: 0
        })
        getApp().request({
            url: getApp().api.group.list,
            method: "get",
            data: {
                cid: cid
            },
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    var goods = res.data.list;
                    if (res.data.page_count >= res.data.page) {
                        self.setData({
                            goods: goods,
                            page: res.data.page,
                            page_count: res.data.page_count,
                            row_count: res.data.row_count,
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
     * 下拉加载
     */
    pullDownLoading: function (e) {
        var self = this;
        if (self.data.emptyGoods == 1 || self.data.show_loading_bar == 1) {
            return;
        }
        self.setData({
            show_loading_bar: 1
        });
        var pageNum = parseInt(self.data.page + 1);
        var cid = self.data.cid;
        getApp().request({
            url: getApp().api.group.list,
            method: "get",
            data: {
                page: pageNum,
                cid: cid
            },
            success: function (res) {
                if (res.code == 0) {
                    var goods = self.data.goods;
                    if (res.data.page > self.data.page) {
                        Array.prototype.push.apply(goods, res.data.list);
                    }
                    if (res.data.page_count >= res.data.page) {
                        self.setData({
                            goods: goods,
                            page: res.data.page,
                            page_count: res.data.page_count,
                            row_count: res.data.row_count,
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
     * 广告图片链接点击事件，处理跳转小程序
     * */
    navigatorClick: function (e) {
        var self = this;
        var open_type = e.currentTarget.dataset.open_type;
        var url = e.currentTarget.dataset.url;
        if (open_type != 'wxapp')
            return true;
        url = parseQueryString(url);
        url.path = url.path ? decodeURIComponent(url.path) : "";
        getApp().core.navigateToMiniProgram({
            appId: url.appId,
            path: url.path,
            complete: function (e) { }
        });
        return false;

        function parseQueryString(url) {
            var reg_url = /^[^\?]+\?([\w\W]+)$/,
                reg_para = /([^&=]+)=([\w\W]*?)(&|$|#)/g,
                arr_url = reg_url.exec(url),
                ret = {};
            if (arr_url && arr_url[1]) {
                var str_para = arr_url[1],
                    result;
                while ((result = reg_para.exec(str_para)) != null) {
                    ret[result[1]] = result[2];
                }
            }
            return ret;
        }
    },
    to_dial: function () {
        var contact_tel = this.data.store.contact_tel;
        getApp().core.makePhoneCall({
            phoneNumber: contact_tel
        })
    },
    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var res = {
            path: "/pages/pt/index/index?user_id=" + self.data.__user_info.id,
            success: function (e) {},
        };
        return res;
    },
})