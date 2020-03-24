var util = require("../../utils/helper.js"), utils = getApp().helper;

Page({
    data: {
        unit_id: "",
        ad: !1,
        space: !1,
        step: 0,
        page: 2,
        over: !1,
        success: !1
    },
    onReachBottom: function() {
        var i = this, s = i.data.over, o = i.data.activity_data, e = void 0, a = void 0, c = void 0;
        if (!s) {
            var n = this.data.page;
            this.setData({
                loading: !0
            }), getApp().core.login({
                success: function(t) {
                    e = t.code, getApp().core.getWeRunData({
                        success: function(t) {
                            a = t.iv, c = t.encryptedData, getApp().request({
                                url: getApp().api.step.activity,
                                method: "POST",
                                data: {
                                    encrypted_data: c,
                                    iv: a,
                                    code: e,
                                    user_id: void 0,
                                    page: n
                                },
                                success: function(t) {
                                    getApp().core.hideLoading();
                                    for (var e = 0; e < t.list.activity_data.length; e++) o.push(t.list.activity_data[e]);
                                    t.list.activity_data.length < 3 && (s = !0);
                                    for (var a = 0; a < o.length; a++) o[a].date = o[a].open_date.replace("-", "").replace("-", "");
                                    i.setData({
                                        page: n + 1,
                                        over: s,
                                        loading: !1,
                                        activity_data: o
                                    });
                                }
                            });
                        }
                    });
                }
            });
        }
    },
    openSetting: function() {
        var e = this, a = e.data.user_id;
        getApp().core.openSetting({
            success: function(t) {
                1 == t.authSetting["scope.werun"] && 1 == t.authSetting["scope.userInfo"] && (e.setData({
                    authorize: !0
                }), getApp().core.showLoading({
                    title: "数据加载中...",
                    mask: !0
                }), e.activity(a));
            },
            fail: function(t) {
                e.setData({
                    authorize: !1
                }), getApp().core.hideLoading();
            }
        });
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = this, a = !1, i = !1, s = void 0;
        null !== t.user_id && (s = t.user_id), getApp().request({
            url: getApp().api.step.setting,
            success: function(t) {
                0 == t.code && e.setData({
                    title: t.data.title,
                    share_title: t.data.share_title
                });
            }
        });
        var o = util.formatTime(new Date()), c = o[0] + o[1] + o[2] + o[3] + o[5] + o[6] + o[8] + o[9];
        this.setData({
            page: 2,
            time: c
        }), null !== t.open_date && (a = t.open_date), null !== t.join && (i = t.join), 
        e.setData({
            join: i,
            open_date: a
        }), getApp().core.showLoading({
            title: "数据加载中...",
            mask: !0
        }), getApp().core.getSetting({
            success: function(t) {
                1 == t.authSetting["scope.werun"] && 1 == t.authSetting["scope.userInfo"] ? e.activity(s) : getApp().core.authorize({
                    scope: "scope.userInfo",
                    success: function(t) {
                        getApp().core.authorize({
                            scope: "scope.werun",
                            success: function(t) {
                                "authorize:ok" == t.errMsg && e.activity(s);
                            },
                            fail: function(t) {
                                e.setData({
                                    authorize: !1
                                }), getApp().core.hideLoading();
                            }
                        });
                    }
                });
            },
            fail: function(t) {
                e.setData({
                    authorize: !1
                }), getApp().core.hideLoading();
            }
        });
    },
    activity: function(e) {
        var p = this, a = void 0, i = void 0, s = void 0;
        getApp().core.login({
            success: function(t) {
                a = t.code, getApp().core.getWeRunData({
                    success: function(t) {
                        i = t.iv, s = t.encryptedData, getApp().request({
                            url: getApp().api.step.activity,
                            method: "POST",
                            data: {
                                encrypted_data: s,
                                iv: i,
                                code: a,
                                user_id: e
                            },
                            success: function(t) {
                                var e = t.list.run_data;
                                getApp().core.hideLoading();
                                var a = t.list.ad_data, i = t.list.activity_data, s = void 0;
                                if (s = !1, i.length < 1) s = !0; else for (var o = 0; o < i.length; o++) i[o].date = i[o].open_date.replace("-", "").replace("-", "");
                                var c = !1, n = !1;
                                null !== a && (c = t.list.ad_data.unit_id, n = !0), p.setData({
                                    unit_id: c,
                                    step: e,
                                    space: s,
                                    activity_data: i,
                                    ad_data: a,
                                    ad: n
                                });
                            }
                        });
                    }
                });
            }
        });
    },
    adError: function(t) {
        console.log(t.detail);
    },
    close: function() {
        this.setData({
            join: !1
        });
    },
    onShareAppMessage: function(t) {
        return getApp().page.onShareAppMessage(this), this.setData({
            join: !1
        }), {
            path: "/step/index/index?user_id=" + getApp().getUser().id,
            title: this.data.share_title ? this.data.share_title : this.data.title
        };
    },
    submit: function(t) {
        var e = void 0, a = void 0, i = void 0;
        console.log(t);
        var s = t.currentTarget.dataset.id, o = (t.currentTarget.dataset.step, this), c = this.data.step;
        getApp().core.showLoading({
            title: "正在提交...",
            mask: !0
        }), getApp().core.login({
            success: function(t) {
                e = t.code, getApp().core.getWeRunData({
                    success: function(t) {
                        a = t.iv, i = t.encryptedData, getApp().request({
                            url: getApp().api.step.activity_submit,
                            method: "POST",
                            data: {
                                code: e,
                                iv: a,
                                encrypted_data: i,
                                num: c,
                                activity_id: s
                            },
                            success: function(t) {
                                getApp().core.hideLoading(), 0 == t.code ? o.setData({
                                    success: !0
                                }) : getApp().core.showModal({
                                    content: t.msg,
                                    showCancel: !1
                                });
                            }
                        });
                    }
                });
            }
        });
    },
    success: function() {
        this.setData({
            success: !1
        }), getApp().core.redirectTo({
            url: "../dare/dare"
        });
    }
});