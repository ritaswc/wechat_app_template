var timer = null;

Page({
    data: {
        page_img: {
            bg: getApp().webRoot + "/statics/images/fxhb/bg.png",
            close: getApp().webRoot + "/statics/images/fxhb/close.png",
            hongbao_bg: getApp().webRoot + "/statics/images/fxhb/hongbao_bg.png",
            open_hongbao_btn: getApp().webRoot + "/statics/images/fxhb/open_hongbao_btn.png",
            wechat: getApp().webRoot + "/statics/images/fxhb/wechat.png",
            coupon: getApp().webRoot + "/statics/images/fxhb/coupon.png",
            pointer_r: getApp().webRoot + "/statics/images/fxhb/pointer_r.png",
            best_icon: getApp().webRoot + "/statics/images/fxhb/best_icon.png",
            more_l: getApp().webRoot + "/statics/images/fxhb/more_l.png",
            more_r: getApp().webRoot + "/statics/images/fxhb/more_r.png",
            cry: getApp().webRoot + "/statics/images/fxhb/cry.png",
            share_modal_bg: getApp().webRoot + "/statics/images/fxhb/share_modal_bg.png"
        },
        goods_list: null,
        rest_time_str: "--:--:--"
    },
    onLoad: function(e) {
        var a = this;
        getApp().page.onLoad(this, e);
        var t = e.id;
        getApp().core.showLoading({
            title: "加载中",
            mask: !0
        }), getApp().request({
            url: getApp().api.fxhb.detail,
            data: {
                id: t
            },
            success: function(t) {
                getApp().core.hideLoading(), 1 != t.code ? (0 == t.code && (a.setData({
                    rule: t.data.rule,
                    share_pic: t.data.share_pic,
                    share_title: t.data.share_title,
                    coupon_total_money: t.data.coupon_total_money,
                    rest_user_num: t.data.rest_user_num,
                    rest_time: t.data.rest_time,
                    hongbao: t.data.hongbao,
                    hongbao_list: t.data.hongbao_list,
                    is_my_hongbao: t.data.is_my_hongbao,
                    my_coupon: t.data.my_coupon,
                    goods_list: t.data.goods_list
                }), a.setRestTimeStr()), a.showShareModal()) : getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && (1 == t.game_open ? getApp().core.redirectTo({
                            url: "/pages/fxhb/open/open"
                        }) : getApp().core.redirectTo({
                            url: "/pages/index/index"
                        }));
                    }
                });
            }
        });
    },
    onReady: function() {
        getApp().page.onReady(this);
    },
    onShow: function() {
        getApp().page.onShow(this);
    },
    showRule: function() {
        this.setData({
            showRule: !0
        });
    },
    closeRule: function() {
        this.setData({
            showRule: !1
        });
    },
    showShareModal: function() {
        this.setData({
            showShareModal: !0
        });
    },
    closeShareModal: function() {
        this.setData({
            showShareModal: !1
        });
    },
    setRestTimeStr: function() {
        var o = this, s = o.data.rest_time || !1;
        !1 !== s && null !== s && ((s = parseInt(s)) <= 0 ? o.setData({
            rest_time_str: "00:00:00"
        }) : (timer && clearInterval(timer), timer = setInterval(function() {
            if ((s = o.data.rest_time) <= 0) return clearInterval(timer), void o.setData({
                rest_time_str: "00:00:00"
            });
            var e = parseInt(s / 3600), t = parseInt(s % 3600 / 60), a = parseInt(s % 3600 % 60);
            o.setData({
                rest_time: s - 1,
                rest_time_str: (e < 10 ? "0" + e : e) + ":" + (t < 10 ? "0" + t : t) + ":" + (a < 10 ? "0" + a : a)
            });
        }, 1e3)));
    },
    detailSubmit: function(e) {
        var t = this;
        getApp().core.showLoading({
            mask: !0
        }), getApp().request({
            url: getApp().api.fxhb.detail_submit,
            method: "post",
            data: {
                id: t.data.hongbao.id,
                form_id: e.detail.formId
            },
            success: function(e) {
                if (1 == e.code) return getApp().core.hideLoading(), void getApp().core.showToast({
                    title: e.msg,
                    complete: function() {
                        0 == e.game_open && getApp().core.redirectTo({
                            url: "/pages/index/index"
                        });
                    }
                });
                0 == e.code && (getApp().core.hideLoading(), getApp().core.showToast({
                    title: e.msg,
                    complete: function() {
                        1 == e.reload && getApp().core.redirectTo({
                            url: "/pages/fxhb/detail/detail?id=" + t.options.id
                        });
                    }
                }));
            }
        });
    },
    onShareAppMessage: function() {
        var t = this;
        getApp().page.onShareAppMessage(this);
        var e = t.data.__user_info;
        return {
            path: "/pages/fxhb/detail/detail?id=" + t.data.hongbao.id + (e ? "&user_id=" + e.id : ""),
            title: t.data.share_title || null,
            imageUrl: t.data.share_pic || null,
            complete: function(e) {
                t.closeShareModal();
            }
        };
    }
});