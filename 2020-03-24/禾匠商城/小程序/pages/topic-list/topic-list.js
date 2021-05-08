Page({
    data: {
        backgrop: [ "navbar-item-active" ],
        navbarArray: [],
        navbarShowIndexArray: 0,
        navigation: !1,
        windowWidth: 375,
        scrollNavbarLeft: 0,
        currentChannelIndex: 0,
        articlesHide: !1
    },
    onLoad: function(a) {
        getApp().page.onLoad(this, a);
        var t = this, e = a.type;
        void 0 !== e && e && t.setData({
            typeid: e
        }), t.loadTopicList({
            page: 1,
            reload: !0
        }), getApp().core.getSystemInfo({
            success: function(a) {
                t.setData({
                    windowWidth: a.windowWidth
                });
            }
        });
    },
    loadTopicList: function(i) {
        var r = this;
        r.data.is_loading || i.loadmore && !r.data.is_more || (r.setData({
            is_loading: !0
        }), getApp().request({
            url: getApp().api.default.topic_type,
            success: function(a) {
                0 == a.code && r.setData({
                    navbarArray: a.data.list,
                    navbarShowIndexArray: Array.from(Array(a.data.list.length).keys()),
                    navigation: "" != a.data.list
                }), getApp().request({
                    url: getApp().api.default.topic_list,
                    data: {
                        page: i.page
                    },
                    success: function(a) {
                        if (0 == a.code) if (void 0 !== r.data.typeid) {
                            for (var t = 0, e = 0; e < r.data.navbarArray.length && (t += 66, r.data.navbarArray[e].id != r.data.typeid); e++) ;
                            r.setData({
                                scrollNavbarLeft: t
                            }), r.switchChannel(parseInt(r.data.typeid)), r.sortTopic({
                                page: 1,
                                type: r.data.typeid,
                                reload: !0
                            });
                        } else i.reload && r.setData({
                            list: a.data.list,
                            page: i.page,
                            is_more: 0 < a.data.list.length
                        }), i.loadmore && r.setData({
                            list: r.data.list.concat(a.data.list),
                            page: i.page,
                            is_more: 0 < a.data.list.length
                        });
                    },
                    complete: function() {
                        r.setData({
                            is_loading: !1
                        });
                    }
                });
            }
        }));
    },
    onShow: function() {
        getApp().page.onShow(this);
    },
    onPullDownRefresh: function() {
        getApp().page.onPullDownRefresh(this);
        var a = this.data.currentChannelIndex;
        this.switchChannel(parseInt(a)), this.sortTopic({
            page: 1,
            type: parseInt(a),
            reload: !0
        }), getApp().core.stopPullDownRefresh();
    },
    onReachBottom: function() {
        getApp().page.onReachBottom(this);
        var a = this.data.currentChannelIndex;
        this.switchChannel(parseInt(a)), this.sortTopic({
            page: this.data.page + 1,
            type: parseInt(a),
            loadmore: !0
        });
    },
    onTapNavbar: function(i) {
        var r = this;
        if ("undefined" == typeof my) {
            var a = i.currentTarget.offsetLeft;
            r.setData({
                scrollNavbarLeft: a - 85
            });
        } else {
            var n = r.data.navbarArray, s = !0;
            n.forEach(function(a, t, e) {
                i.currentTarget.id == a.id && (s = !1, 1 <= t ? r.setData({
                    toView: n[t - 1].id
                }) : r.setData({
                    toView: -1
                }));
            }), s && r.setData({
                toView: "0"
            });
        }
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), r.switchChannel(parseInt(i.currentTarget.id)), r.sortTopic({
            page: 1,
            type: i.currentTarget.id,
            reload: !0
        });
    },
    sortTopic: function(t) {
        var e = this;
        getApp().request({
            url: getApp().api.default.topic_list,
            data: t,
            success: function(a) {
                0 == a.code && (t.reload && e.setData({
                    list: a.data.list,
                    page: t.page,
                    is_more: 0 < a.data.list.length
                }), t.loadmore && e.setData({
                    list: e.data.list.concat(a.data.list),
                    page: t.page,
                    is_more: 0 < a.data.list.length
                }), getApp().core.hideLoading());
            }
        });
    },
    switchChannel: function(i) {
        var a = this.data.navbarArray, t = new Array();
        -1 == i ? t[1] = "navbar-item-active" : 0 == i && (t[0] = "navbar-item-active"), 
        a.forEach(function(a, t, e) {
            a.type = "", a.id == i && (a.type = "navbar-item-active");
        }), this.setData({
            navbarArray: a,
            currentChannelIndex: i,
            backgrop: t
        });
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        var a = this, t = {
            path: "/pages/topic-list/topic-list?user_id=" + a.data.__user_info.id + "&type=" + (a.data.typeid ? a.data.typeid : ""),
            success: function(a) {}
        };
        return console.log(t.path), t;
    }
});