var interval = 0, page_first_init = !0, timer = 1, fullScreen = !1, page_first = [];

Page({
    data: {
        WindowWidth: getApp().core.getSystemInfoSync().windowWidth,
        WindowHeight: getApp().core.getSystemInfoSync().windowHeight,
        left: 0,
        show_notice: -1,
        animationData: {},
        play: -1,
        time: 0,
        buy: !1,
        opendate: !1,
        goods: "",
        form: {
            number: 1
        },
        time_all: []
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t), t.page_id || (t.page_id = -1), this.setData({
            options: t
        }), this.loadData(t);
    },
    suspension: function() {
        var o = this;
        interval = setInterval(function() {
            getApp().request({
                url: getApp().api.default.buy_data,
                data: {
                    time: o.data.time
                },
                method: "POST",
                success: function(t) {
                    if (0 == t.code) {
                        var a = !1;
                        o.data.msgHistory == t.md5 && (a = !0);
                        var e = "", i = t.cha_time, s = Math.floor(i / 60 - 60 * Math.floor(i / 3600));
                        e = 0 == s ? i % 60 + "秒" : s + "分" + i % 60 + "秒", !a && t.cha_time <= 300 ? o.setData({
                            buy: {
                                time: e,
                                type: t.data.type,
                                url: t.data.url,
                                user: 5 <= t.data.user.length ? t.data.user.slice(0, 4) + "..." : t.data.user,
                                avatar_url: t.data.avatar_url,
                                address: 8 <= t.data.address.length ? t.data.address.slice(0, 7) + "..." : t.data.address,
                                content: t.data.content
                            },
                            msgHistory: t.md5
                        }) : o.setData({
                            buy: !1
                        });
                    }
                },
                noHandlerFail: !0
            });
        }, 1e4);
    },
    loadData: function() {
        var i = this, t = {}, s = i.data.options;
        if (-1 != s.page_id) t.page_id = s.page_id; else {
            t.page_id = -1;
            var a = getApp().core.getStorageSync(getApp().const.PAGE_INDEX_INDEX);
            a && (a.act_modal_list = [], i.setData(a));
        }
        getApp().request({
            url: getApp().api.default.index,
            data: t,
            success: function(t) {
                if (0 == t.code) {
                    if ("diy" == t.data.status) {
                        var a = t.data.act_modal_list;
                        -1 != s.page_id && (getApp().core.setNavigationBarTitle({
                            title: t.data.info
                        }), i.setData({
                            title: t.data.info
                        }));
                        for (var e = a.length - 1; 0 <= e; e--) (void 0 === a[e].status || 0 == a[e].status) && getApp().helper.inArray(a[e].page_id, page_first) && !i.data.user_info_show || 0 == a[e].show ? a.splice(e, 1) : page_first.push(a[e].page_id);
                        i.setData({
                            template: t.data.template,
                            act_modal_list: a,
                            time_all: t.data.time_all
                        }), i.setTime();
                    } else page_first_init ? i.data.user_info_show || (page_first_init = !1) : t.data.act_modal_list = [], 
                    i.setData(t.data), i.miaoshaTimer();
                    -1 == s.page_id && getApp().core.setStorageSync(getApp().const.PAGE_INDEX_INDEX, t.data);
                }
            },
            complete: function() {
                getApp().core.stopPullDownRefresh();
            }
        });
    },
    onShow: function() {
        var e = this;
        getApp().page.onShow(this), require("./../../components/diy/diy.js").init(this), 
        getApp().getConfig(function(t) {
            var a = t.store;
            a && a.name && -1 == e.data.options.page_id && getApp().core.setNavigationBarTitle({
                title: a.name
            }), a && 1 === a.purchase_frame ? e.suspension(e.data.time) : e.setData({
                buy_user: ""
            });
        }), getApp().query = null;
    },
    onPullDownRefresh: function() {
        getApp().getStoreData(), clearInterval(timer), this.loadData();
    },
    onShareAppMessage: function(t) {
        getApp().page.onShareAppMessage(this);
        var a = this, e = getApp().getUser();
        return -1 != a.data.options.page_id ? {
            path: "/pages/index/index?user_id=" + e.id + "&page_id=" + a.data.options.page_id,
            title: a.data.title
        } : {
            path: "/pages/index/index?user_id=" + e.id,
            title: a.data.store.name
        };
    },
    showshop: function(t) {
        var a = this, e = t.currentTarget.dataset.id, i = t.currentTarget.dataset;
        getApp().request({
            url: getApp().api.default.goods,
            data: {
                id: e
            },
            success: function(t) {
                0 == t.code && a.setData({
                    data: i,
                    attr_group_list: t.data.attr_group_list,
                    goods: t.data,
                    showModal: !0
                });
            }
        });
    },
    miaoshaTimer: function() {
        var t = this;
        t.data.miaosha && 0 != t.data.miaosha.rest_time && (t.data.miaosha.ms_next || (timer = setInterval(function() {
            0 < t.data.miaosha.rest_time ? (t.data.miaosha.rest_time = t.data.miaosha.rest_time - 1, 
            t.data.miaosha.times = t.setTimeList(t.data.miaosha.rest_time), t.setData({
                miaosha: t.data.miaosha
            })) : clearInterval(timer);
        }, 1e3)));
    },
    onHide: function() {
        getApp().page.onHide(this), this.setData({
            play: -1
        }), clearInterval(interval);
    },
    onUnload: function() {
        getApp().page.onUnload(this), this.setData({
            play: -1
        }), clearInterval(timer), clearInterval(interval);
    },
    showNotice: function(t) {
        console.log(t), this.setData({
            show_notice: t.currentTarget.dataset.index
        });
    },
    closeNotice: function() {
        this.setData({
            show_notice: -1
        });
    },
    to_dial: function() {
        var t = this.data.store.contact_tel;
        getApp().core.makePhoneCall({
            phoneNumber: t
        });
    },
    closeActModal: function() {
        var a = this, e = a.data.act_modal_list;
        for (var t in e) {
            var i = parseInt(t);
            e[i].show && (e[i].show = !1);
            break;
        }
        a.setData({
            act_modal_list: e
        }), setTimeout(function() {
            for (var t in e) if (e[t].show) {
                e = e.splice(t, 1).concat(e);
                break;
            }
            a.setData({
                act_modal_list: e
            });
        }, 500);
    },
    naveClick: function(t) {
        getApp().navigatorClick(t, this);
    },
    onPageScroll: function(t) {
        var a = this;
        if (!fullScreen && -1 != a.data.play) {
            var e = getApp().core.getSystemInfoSync().windowHeight;
            "undefined" == typeof my ? getApp().core.createSelectorQuery().select(".video").fields({
                rect: !0
            }, function(t) {
                (t.top <= -200 || t.top >= e - 57) && a.setData({
                    play: -1
                });
            }).exec() : getApp().core.createSelectorQuery().select(".video").boundingClientRect().scrollOffset().exec(function(t) {
                (t[0].top <= -200 || t[0].top >= e - 57) && a.setData({
                    play: -1
                });
            });
        }
    },
    fullscreenchange: function(t) {
        fullScreen = !!t.detail.fullScreen;
    }
});