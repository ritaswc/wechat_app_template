var time = require("../commons/time.js"), app = getApp(), api = getApp().api, setIntval = null, is_loading = !1, is_no_more = !0;

Page({
    data: {
        show_more: !0,
        p: 1,
        show_modal: !1,
        show: !1,
        show_more_btn: !0,
        animationData: null,
        show_modal_a: !1
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var a = this;
        a.setData({
            order_id: t.order_id
        }), a.joinBargain(), time.init(a);
    },
    joinBargain: function() {
        var a = this;
        getApp().request({
            url: getApp().api.bargain.bargain,
            data: {
                order_id: a.data.order_id
            },
            success: function(t) {
                0 == t.code ? (a.getOrderInfo(), a.setData(t.data)) : (a.showToast({
                    title: t.msg
                }), getApp().core.hideLoading());
            }
        });
    },
    getOrderInfo: function() {
        var a = this;
        getApp().request({
            url: getApp().api.bargain.activity,
            data: {
                order_id: a.data.order_id,
                page: 1
            },
            success: function(t) {
                0 == t.code ? (a.setData(t.data), a.setData({
                    time_list: a.setTimeList(t.data.reset_time),
                    show: !0
                }), a.data.bargain_status && a.setData({
                    show_modal: !0
                }), a.setTimeOver(), is_no_more = !1, a.animationCr()) : a.showToast({
                    title: t.msg
                });
            },
            complete: function(t) {
                getApp().core.hideLoading();
            }
        });
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this);
    },
    onHide: function() {
        getApp().page.onHide(this);
    },
    onUnload: function() {
        getApp().page.onUnload(this), clearInterval(setIntval), setIntval = null;
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        var t = this;
        return {
            path: "/bargain/activity/activity?order_id=" + t.data.order_id + "&user_id=" + t.data.__user_info.id,
            success: function(t) {},
            title: t.data.share_title || null
        };
    },
    loadData: function() {
        var e = this;
        if (getApp().core.showLoading({
            title: "加载中"
        }), !is_loading) {
            is_loading = !0, getApp().core.showNavigationBarLoading();
            var i = e.data.p + 1;
            getApp().request({
                url: getApp().api.bargain.activity,
                data: {
                    order_id: e.data.order_id,
                    page: i
                },
                success: function(t) {
                    if (0 == t.code) {
                        var a = e.data.bargain_info;
                        a = a.concat(t.data.bargain_info), e.setData({
                            bargain_info: a,
                            p: i,
                            price: t.data.price,
                            money_per: t.data.money_per,
                            money_per_t: t.data.money_per_t
                        }), 0 == t.data.bargain_info.length && (is_no_more = !0, i -= 1, e.setData({
                            show_more_btn: !1,
                            show_more: !0,
                            p: i
                        }));
                    } else e.showToast({
                        title: t.msg
                    });
                },
                complete: function(t) {
                    getApp().core.hideLoading(), getApp().core.hideNavigationBarLoading(), is_loading = !1;
                }
            });
        }
    },
    showMore: function(t) {
        this.data.show_more_btn && (is_no_more = !1), is_no_more || this.loadData();
    },
    hideMore: function() {
        this.setData({
            show_more_btn: !0,
            show_more: !1
        });
    },
    orderSubmit: function() {
        var t = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().core.redirectTo({
            url: "/bargain/goods/goods?goods_id=" + t.data.goods_id
        });
    },
    close: function() {
        this.setData({
            show_modal: !1
        });
    },
    buyNow: function() {
        var a = [], t = [];
        t.push({
            bargain_order_id: this.data.order_id
        }), a.push({
            mch_id: 0,
            goods_list: t
        }), getApp().core.showModal({
            title: "提示",
            content: "是否确认购买？",
            success: function(t) {
                t.confirm && getApp().core.redirectTo({
                    url: "/pages/new-order-submit/new-order-submit?mch_list=" + JSON.stringify(a)
                });
            }
        });
    },
    goToList: function() {
        getApp().core.redirectTo({
            url: "/bargain/list/list"
        });
    },
    animationCr: function() {
        var t = this;
        t.animationT(), setTimeout(function() {
            t.setData({
                show_modal_a: !0
            }), t.animationBig(), t.animationS();
        }, 800);
    },
    animationBig: function() {
        var t = getApp().core.createAnimation({
            duration: 500,
            transformOrigin: "50% 50%"
        }), a = this, e = 0;
        setInterval(function() {
            e % 2 == 0 ? t.scale(.9).step() : t.scale(1).step(), a.setData({
                animationData: t.export()
            }), 500 == ++e && (e = 0);
        }, 500);
    },
    animationS: function() {
        var t = getApp().core.createAnimation({
            duration: 500
        });
        t.width("512rpx").height("264rpx").step(), t.rotate(-2).step(), t.rotate(4).step(), 
        t.rotate(-2).step(), t.rotate(0).step(), this.setData({
            animationDataHead: t.export()
        });
    },
    animationT: function() {
        var t = getApp().core.createAnimation({
            duration: 200
        });
        t.width("500rpx").height("500rpx").step(), this.setData({
            animationDataT: t.export()
        });
    }
});