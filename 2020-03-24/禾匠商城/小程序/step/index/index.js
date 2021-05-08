var _data;

function _defineProperty(e, t, a) {
    return t in e ? Object.defineProperty(e, t, {
        value: a,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : e[t] = a, e;
}

var util = require("../../utils/helper.js"), utils = getApp().helper;

Page(_defineProperty({
    data: (_data = {
        dare: !1,
        my: "0",
        todayStep: "0",
        authorize: !0,
        overStep: "0",
        banner_list: [],
        useStep: "0",
        nowAdd: "0.00",
        today: "",
        nextAdd: "0.00",
        people: "2153",
        friend: [],
        now: !1,
        convert_ratio: "",
        activity_data: [ {
            id: 0
        }, {
            open_date: ""
        }, {
            name: ""
        }, {
            bail_currency: 0
        }, {
            step_num: 0
        } ],
        convert_max: 0,
        title: "",
        goods: [],
        user_id: 0,
        time: "",
        encrypted_data: "",
        iv: "",
        code: "",
        page: 2,
        unit_id: ""
    }, _defineProperty(_data, "user_id", ""), _defineProperty(_data, "over", !1), _data),
    switch: function(e) {
        var t = 0;
        t = 1 == e.detail.value ? 1 : 0, getApp().request({
            url: getApp().api.step.remind,
            data: {
                remind: t
            }
        });
    },
    exchange: function() {
        var a = this, t = void 0, o = void 0, n = void 0, e = a.data.nowAdd, i = a.data.todayStep * (1 + e / 100), d = a.data.useStep, r = a.data.convert_ratio, s = a.data.convert_max, p = parseInt(i);
        0 < s && +s < p && (p = +s), p -= d;
        var c = a.data.overStep, u = (p / r).toString().match(/^\d+(?:\.\d{0,2})?/);
        u < .01 || 0 == c ? getApp().core.showModal({
            content: "步数不足",
            showCancel: !1
        }) : getApp().core.showModal({
            content: "确认把" + c + "步兑换为" + u + (a.data.store.option.step.currency_name ? a.data.store.option.step.currency_name : "活力币"),
            success: function(e) {
                e.confirm && (getApp().core.showLoading({
                    title: "兑换中...",
                    mask: !0
                }), getApp().core.login({
                    success: function(e) {
                        o = e.code, getApp().core.getWeRunData({
                            success: function(e) {
                                t = e.iv, n = e.encryptedData, getApp().request({
                                    url: getApp().api.step.convert,
                                    method: "post",
                                    data: {
                                        iv: t,
                                        code: o,
                                        encrypted_data: n,
                                        num: a.data.todayStep
                                    },
                                    success: function(e) {
                                        if (getApp().core.hideLoading(), 0 == e.code) {
                                            0 < s && +s < p && (p = +s), p -= e.list.num;
                                            var t = (+a.data.my + +e.list.convert).toFixed(2);
                                            a.setData({
                                                overStep: p,
                                                my: t,
                                                useStep: e.list.num
                                            });
                                        } else getApp().core.showModal({
                                            content: e.msg,
                                            showCancel: !1
                                        });
                                    }
                                });
                            }
                        });
                    }
                }));
            },
            fail: function(e) {
                getApp().core.hideLoading(), getApp().core.showModal({
                    content: "为确保您的正常使用，请完善授权操作",
                    showCancel: !1
                });
            }
        });
    },
    adError: function(e) {
        console.log(e.detail);
    },
    onShareAppMessage: function(e) {
        return getApp().page.onShareAppMessage(this), {
            path: "/step/dare/dare?user_id=" + getApp().getUser().id,
            title: this.data.title ? this.data.title : "步数挑战"
        };
    },
    onReachBottom: function() {
        var a = this, o = a.data.over;
        if (!o) {
            var e = this.data.encrypted_data, t = this.data.iv, n = this.data.code, i = this.data.user_id, d = this.data.goods, r = this.data.page;
            this.setData({
                loading: !0
            }), getApp().request({
                url: getApp().api.step.index,
                method: "POST",
                data: {
                    encrypted_data: e,
                    iv: t,
                    code: n,
                    user_id: i,
                    page: r
                },
                success: function(e) {
                    for (var t = 0; t < e.data.goods_data.length; t++) d.push(e.data.goods_data[t]);
                    e.data.goods_data.length < 6 && (o = !0), a.setData({
                        goods: d,
                        page: r + 1,
                        over: o,
                        loading: !1
                    });
                }
            });
        }
    },
    refresh: function() {
        getApp().core.showLoading({
            title: "步数加载中...",
            mask: !0
        });
        var e = this, t = e.data.convert_max;
        e.runData(e.data.user_id, t);
    },
    onShow: function() {
        if (0 != this.data.now) {
            var n = this, t = void 0, a = void 0, o = void 0, i = n.data.user_id;
            getApp().core.login({
                success: function(e) {
                    t = e.code, getApp().core.getWeRunData({
                        success: function(e) {
                            a = e.iv, o = e.encryptedData, getApp().request({
                                url: getApp().api.step.index,
                                method: "POST",
                                data: {
                                    encrypted_data: o,
                                    iv: a,
                                    code: t,
                                    user_id: i,
                                    page: 1
                                },
                                success: function(e) {
                                    getApp().core.hideLoading();
                                    var t = e.data.activity_data, a = e.data.user_data, o = e.data.user_data.step_currency;
                                    n.setData({
                                        activity_data: t,
                                        user_data: a,
                                        my: o
                                    });
                                }
                            });
                        }
                    });
                }
            });
        }
    },
    runData: function(y, S) {
        var D = this, M = void 0, x = void 0, L = void 0;
        getApp().core.login({
            success: function(e) {
                M = e.code, getApp().core.getWeRunData({
                    success: function(e) {
                        x = e.iv, L = e.encryptedData, getApp().request({
                            url: getApp().api.step.index,
                            method: "POST",
                            data: {
                                encrypted_data: L,
                                iv: x,
                                code: M,
                                user_id: y,
                                page: 1
                            },
                            success: function(e) {
                                getApp().core.hideLoading();
                                var t = void 0, a = void 0;
                                null == e.data.activity_data.id ? (a = !1, t = []) : (a = !0, t = e.data.activity_data);
                                var o = e.data.run_data.stepInfoList, n = e.data.user_data, i = void 0, d = [ {
                                    pic_url: "../image/ad.png"
                                } ];
                                0 < e.data.banner_list.length && (d = e.data.banner_list);
                                var r = !1;
                                null !== e.data.ad_data && (r = e.data.ad_data.unit_id);
                                var s = n.step_currency, p = e.data.ad_data, c = e.data.goods_data, u = o[o.length - 1].step, g = o[o.length - 1].timestamp, h = n.ratio / 10, l = n.invite_list, _ = 0, v = 0;
                                n.now_ratio && (v = n.now_ratio / 10);
                                var f = void 0;
                                0 == n.remind ? f = !1 : 1 == n.remind && (f = !0), 0 < n.convert_num && (_ = n.convert_num);
                                var A = parseInt(u * (1 + v / 100));
                                0 < S && +S < A && (A = +S), 1e3 <= (A -= +_) && (A = String(A).replace(/(\d)(?=(\d{3})+$)/g, "$1,"));
                                var m = "";
                                null != t.open_date && (m = t.open_date.replace(".", "").replace(".", "")), i = !(t.step_num > u), 
                                A < 0 && (A = 0);
                                var w = l.length;
                                D.setData(_defineProperty({
                                    overStep: A,
                                    todayStep: u,
                                    nextAdd: h,
                                    friend: l,
                                    today: g,
                                    finish: i,
                                    nowAdd: v,
                                    my: s,
                                    now: !0,
                                    user: n,
                                    length: w,
                                    banner_list: d,
                                    useStep: _,
                                    goods: c,
                                    user_id: y,
                                    checked: f,
                                    encrypted_data: L,
                                    iv: x,
                                    page: 2,
                                    code: M,
                                    open_date: m,
                                    activity_data: t,
                                    dare: a,
                                    ad_data: p,
                                    unit_id: r
                                }, "user_id", n.user_id));
                            },
                            fail: function(e) {
                                getApp().core.showModal({
                                    content: e.errMsg,
                                    showCancel: !1
                                });
                            }
                        });
                    },
                    fail: function(e) {
                        "getWeRunData:fail cancel" == e.errMsg ? getApp().core.showModal({
                            content: "读取失败，请稍后再试",
                            showCancel: !1
                        }) : "getWeRunData: fail device not support" == e.errMsg ? getApp().core.showModal({
                            content: '请在微信中搜索"微信运动"公众号，并点击关注',
                            showCancel: !1
                        }) : getApp().core.showModal({
                            content: e.errMsg,
                            showCancel: !1
                        });
                    }
                });
            },
            fail: function(e) {
                getApp().core.showModal({
                    content: e.errMsg,
                    showCancel: !1
                });
            }
        });
    },
    openSetting: function() {
        var o = this;
        getApp().core.openSetting({
            success: function(e) {
                if (1 == e.authSetting["scope.werun"] && 1 == e.authSetting["scope.userInfo"]) {
                    o.setData({
                        authorize: !0
                    }), getApp().core.showLoading({
                        title: "步数加载中...",
                        mask: !0
                    });
                    var t = o.data.user_id, a = o.data.convert_max;
                    o.runData(t, a);
                }
            },
            fail: function(e) {
                o.setData({
                    authorize: !1
                }), getApp().core.hideLoading();
            }
        });
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var o = 0;
        if (null !== e.scene) {
            var t = decodeURIComponent(e.scene), a = utils.scene_decode(t);
            0 < a.uid && (o = a.uid);
        }
        0 < e.user_id && (o = e.user_id), this.setData({
            user_id: o,
            now: !1
        });
        var n = util.formatTime(new Date()), i = n[0] + n[1] + n[2] + n[3] + n[5] + n[6] + n[8] + n[9];
        this.setData({
            time: i
        }), getApp().core.showLoading({
            title: "步数加载中...",
            mask: !0
        }), getApp().page.onShow(this), getApp().core.showShareMenu({
            withShareTicket: !0
        });
        var d = this, r = void 0, s = getApp().getUser();
        s && s.access_token || getApp().page.setUserInfoShow(), getApp().request({
            url: getApp().api.step.setting,
            success: function(e) {
                if (0 == e.code) {
                    var t = e.data.title, a = e.data.share_title;
                    r = e.data.convert_max, t && (getApp().core.setNavigationBarTitle({
                        title: t
                    }), d.setData({
                        title: t,
                        share_title: a
                    })), d.setData({
                        convert_ratio: e.data.convert_ratio,
                        convert_max: r
                    }), getApp().core.getSetting({
                        success: function(e) {
                            1 == e.authSetting["scope.werun"] && 1 == e.authSetting["scope.userInfo"] ? d.runData(o, r) : 1 == e.authSetting["scope.userInfo"] ? getApp().core.authorize({
                                scope: "scope.werun",
                                success: function(e) {
                                    "authorize:ok" == e.errMsg && d.runData(o, r);
                                },
                                fail: function(e) {
                                    d.setData({
                                        authorize: !1
                                    }), getApp().core.hideLoading();
                                }
                            }) : getApp().core.hideLoading();
                        },
                        fail: function(e) {
                            d.setData({
                                authorize: !1
                            }), getApp().core.hideLoading();
                        }
                    });
                }
            },
            fail: function(e) {
                getApp().core.showModal({
                    content: e.errMsg,
                    showCancel: !1
                });
            }
        });
    }
}, "onShareAppMessage", function(e) {
    return getApp().page.onShareAppMessage(this), {
        path: "/step/index/index?user_id=" + getApp().getUser().id,
        title: this.data.share_title ? this.data.share_title : this.data.title
    };
}));