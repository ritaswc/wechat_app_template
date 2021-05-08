var utils = require("../../../utils/helper.js"), WxParse = require("../../../wxParse/wxParse.js"), gSpecificationsModel = require("../../../components/goods/specifications_model.js"), goodsBanner = require("../../../components/goods/goods_banner.js"), goodsInfo = require("../../../components/goods/goods_info.js"), goodsBuy = require("../../../components/goods/goods_buy.js");

Page({
    data: {
        pageType: "PINTUAN",
        hide: "hide",
        form: {
            number: 1,
            pt_detail: !1
        }
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var e = t.user_id, a = decodeURIComponent(t.scene);
        if (void 0 !== e) e; else if (void 0 !== a) {
            var i = utils.scene_decode(a);
            i.uid && i.gid ? (i.uid, t.gid = i.gid) : a;
        } else if ("undefined" != typeof my && null !== getApp().query) {
            var o = getApp().query;
            getApp().query = null, t.id = o.gid;
        }
        this.setData({
            id: t.gid,
            oid: t.oid ? t.oid : 0,
            group_checked: t.group_id ? t.group_id : 0
        }), this.getGoodsInfo(t);
        var r = getApp().core.getStorageSync(getApp().const.STORE);
        this.setData({
            store: r
        });
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this), gSpecificationsModel.init(this), goodsBanner.init(this), 
        goodsInfo.init(this), goodsBuy.init(this);
    },
    onHide: function() {
        getApp().page.onHide(this);
    },
    onUnload: function() {
        getApp().page.onUnload(this), getApp().core.removeStorageSync(getApp().const.PT_GROUP_DETAIL);
    },
    onPullDownRefresh: function() {
        getApp().page.onPullDownRefresh(this);
    },
    onReachBottom: function() {
        getApp().page.onReachBottom(this);
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        var t = this, e = getApp().core.getStorageSync(getApp().const.USER_INFO), a = "/pages/pt/details/details?gid=" + t.data.goods.id + "&user_id=" + e.id;
        return {
            title: t.data.goods.name,
            path: a,
            imageUrl: t.data.goods.cover_pic,
            success: function(t) {}
        };
    },
    getGoodsInfo: function(t) {
        var e = t.gid, o = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().core.showNavigationBarLoading(), getApp().request({
            url: getApp().api.group.details,
            method: "get",
            data: {
                gid: e
            },
            success: function(t) {
                if (0 == t.code) {
                    o.countDownRun(t.data.info.limit_time_ms);
                    var e = t.data.info.detail;
                    WxParse.wxParse("detail", "html", e, o), getApp().core.setNavigationBarTitle({
                        title: t.data.info.name
                    }), getApp().core.hideNavigationBarLoading();
                    var a = (t.data.info.original_price - t.data.info.price).toFixed(2), i = t.data.info;
                    i.service_list = t.data.info.service, o.setData({
                        group_checked: o.data.group_checked ? o.data.group_checked : 0,
                        goods: i,
                        attr_group_list: t.data.attr_group_list,
                        attr_group_num: t.data.attr_group_num,
                        limit_time: t.data.limit_time_res,
                        group_list: t.data.groupList,
                        group_num: t.data.groupList.length,
                        group_rule_id: t.data.groupRuleId,
                        comment: t.data.comment,
                        comment_num: t.data.commentNum,
                        reduce_price: a < 0 ? 0 : a
                    }), o.countDown(), o.selectDefaultAttr();
                } else getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(t) {
                        t.confirm && getApp().core.redirectTo({
                            url: "/pages/pt/index/index"
                        });
                    }
                });
            },
            complete: function(t) {
                getApp().core.hideLoading();
            }
        });
    },
    more: function() {
        this.setData({
            pt_detail: !0
        });
    },
    end_more: function() {
        this.setData({
            pt_detail: !1
        });
    },
    previewImage: function(t) {
        var e = t.currentTarget.dataset.url;
        getApp().core.previewImage({
            urls: [ e ]
        });
    },
    selectDefaultAttr: function() {
        var t = this;
        if (!t.data.goods || "0" === t.data.goods.use_attr) for (var e in t.data.attr_group_list) for (var a in t.data.attr_group_list[e].attr_list) 0 == e && 0 == a && (t.data.attr_group_list[e].attr_list[a].checked = !0);
        t.setData({
            attr_group_list: t.data.attr_group_list
        });
    },
    countDownRun: function(r) {
        var s = this;
        setInterval(function() {
            var t = new Date(r[0], r[1] - 1, r[2], r[3], r[4], r[5]) - new Date(), e = parseInt(t / 1e3 / 60 / 60 / 24, 10), a = parseInt(t / 1e3 / 60 / 60 % 24, 10), i = parseInt(t / 1e3 / 60 % 60, 10), o = parseInt(t / 1e3 % 60, 10);
            e = s.checkTime(e), a = s.checkTime(a), i = s.checkTime(i), o = s.checkTime(o), 
            s.setData({
                limit_time: {
                    days: e < 0 ? "00" : e,
                    hours: a < 0 ? "00" : a,
                    mins: i < 0 ? "00" : i,
                    secs: o < 0 ? "00" : o
                }
            });
        }, 1e3);
    },
    checkTime: function(t) {
        return t < 0 ? "00" : (t < 10 && (t = "0" + t), t);
    },
    goToGroup: function(t) {
        getApp().core.navigateTo({
            url: "/pages/pt/group/details?oid=" + t.target.dataset.id
        });
    },
    goToComment: function(t) {
        getApp().core.navigateTo({
            url: "/pages/pt/comment/comment?id=" + this.data.goods.id
        });
    },
    goArticle: function(t) {
        this.data.group_rule_id && getApp().core.navigateTo({
            url: "/pages/article-detail/article-detail?id=" + this.data.group_rule_id
        });
    },
    buyNow: function() {
        this.submit("GROUP_BUY", this.data.group_checked);
    },
    onlyBuy: function() {
        this.submit("ONLY_BUY", 0);
    },
    submit: function(t, e) {
        var a = this, i = "GROUP_BUY" == t;
        if (!a.data.show_attr_picker || i != a.data.groupNum) return a.setData({
            show_attr_picker: !0,
            groupNum: i
        }), !0;
        if (a.data.form.number > a.data.goods.num) return getApp().core.showToast({
            title: "商品库存不足，请选择其它规格或数量",
            image: "/images/icon-warning.png"
        }), !0;
        var o = a.data.attr_group_list, r = [];
        for (var s in o) {
            var n = !1;
            for (var d in o[s].attr_list) if (o[s].attr_list[d].checked) {
                n = {
                    attr_id: o[s].attr_list[d].attr_id,
                    attr_name: o[s].attr_list[d].attr_name
                };
                break;
            }
            if (!n) return getApp().core.showToast({
                title: "请选择" + o[s].attr_group_name,
                image: "/images/icon-warning.png"
            }), !0;
            r.push({
                attr_group_id: o[s].attr_group_id,
                attr_group_name: o[s].attr_group_name,
                attr_id: n.attr_id,
                attr_name: n.attr_name
            });
        }
        a.setData({
            show_attr_picker: !1
        });
        var p = 0;
        a.data.oid && (t = "GROUP_BUY_C", p = a.data.oid), getApp().core.redirectTo({
            url: "/pages/pt/order-submit/order-submit?goods_info=" + JSON.stringify({
                goods_id: a.data.goods.id,
                attr: r,
                num: a.data.form.number,
                type: t,
                deliver_type: a.data.goods.type,
                group_id: e,
                parent_id: p
            })
        });
    },
    countDown: function() {
        var n = this;
        setInterval(function() {
            var t = n.data.group_list;
            for (var e in t) {
                var a = new Date(t[e].limit_time_ms[0], t[e].limit_time_ms[1] - 1, t[e].limit_time_ms[2], t[e].limit_time_ms[3], t[e].limit_time_ms[4], t[e].limit_time_ms[5]) - new Date(), i = parseInt(a / 1e3 / 60 / 60 / 24, 10), o = parseInt(a / 1e3 / 60 / 60 % 24, 10), r = parseInt(a / 1e3 / 60 % 60, 10), s = parseInt(a / 1e3 % 60, 10);
                i = n.checkTime(i), o = n.checkTime(o), r = n.checkTime(r), s = n.checkTime(s), 
                t[e].limit_time = {
                    days: i,
                    hours: 0 < o ? o : "00",
                    mins: 0 < r ? r : "00",
                    secs: 0 < s ? s : "00"
                }, n.setData({
                    group_list: t
                });
            }
        }, 1e3);
    },
    bigToImage: function(t) {
        var e = this.data.comment[t.target.dataset.index].pic_list;
        getApp().core.previewImage({
            current: t.target.dataset.url,
            urls: e
        });
    },
    groupCheck: function() {
        var a = this, t = a.data.attr_group_num, e = a.data.attr_group_num.attr_list;
        for (var i in e) e[i].checked = !1;
        t.attr_list = e;
        a.data.goods;
        a.setData({
            group_checked: 0,
            attr_group_num: t
        });
        var o = a.data.attr_group_list, r = [], s = !0;
        for (var i in o) {
            var n = !1;
            for (var d in o[i].attr_list) if (o[i].attr_list[d].checked) {
                r.push(o[i].attr_list[d].attr_id), n = !0;
                break;
            }
            if (!n) {
                s = !1;
                break;
            }
        }
        s && (getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.group.goods_attr_info,
            data: {
                goods_id: a.data.goods.id,
                group_id: a.data.group_checked,
                attr_list: JSON.stringify(r)
            },
            success: function(t) {
                if (getApp().core.hideLoading(), 0 == t.code) {
                    var e = a.data.goods;
                    e.price = t.data.price, e.num = t.data.num, e.attr_pic = t.data.pic, e.single_price = t.data.single_price ? t.data.single_price : 0, 
                    e.group_price = t.data.price, e.is_member_price = t.data.is_member_price, a.setData({
                        goods: e
                    });
                }
            }
        }));
    },
    attrNumClick: function(t) {
        var a = this, e = t.target.dataset.id, i = a.data.attr_group_num, o = i.attr_list;
        for (var r in o) o[r].id == e ? o[r].checked = !0 : o[r].checked = !1;
        i.attr_list = o, a.setData({
            attr_group_num: i,
            group_checked: e
        });
        var s = a.data.attr_group_list, n = [], d = !0;
        for (var r in s) {
            var p = !1;
            for (var g in s[r].attr_list) if (s[r].attr_list[g].checked) {
                n.push(s[r].attr_list[g].attr_id), p = !0;
                break;
            }
            if (!p) {
                d = !1;
                break;
            }
        }
        d && (getApp().core.showLoading({
            title: "正在加载",
            mask: !0
        }), getApp().request({
            url: getApp().api.group.goods_attr_info,
            data: {
                goods_id: a.data.goods.id,
                group_id: a.data.group_checked,
                attr_list: JSON.stringify(n)
            },
            success: function(t) {
                if (getApp().core.hideLoading(), 0 == t.code) {
                    var e = a.data.goods;
                    e.price = t.data.price, e.num = t.data.num, e.attr_pic = t.data.pic, e.single_price = t.data.single_price ? t.data.single_price : 0, 
                    e.group_price = t.data.price, e.is_member_price = t.data.is_member_price, a.setData({
                        goods: e
                    });
                }
            }
        }));
    }
});