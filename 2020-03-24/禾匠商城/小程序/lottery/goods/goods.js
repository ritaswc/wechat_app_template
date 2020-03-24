var lotteryInter, options_id, timer, luckyTimer, utils = getApp().helper, videoContext = "", WxParse = require("../../wxParse/wxParse.js");

Page({
    data: {
        hide: "hide",
        time_list: {
            day: 0,
            hour: "00",
            minute: "00",
            second: "00"
        },
        p: 1,
        user_index: 0,
        show_animate: !0,
        animationTranslottery: {},
        award_bg: !1
    },
    onLoad: function(t) {
        if (getApp().page.onLoad(this, t), this.getBinding(), t.user_id && this.buyZero(), 
        "undefined" == typeof my) {
            var e = decodeURIComponent(t.scene);
            if (void 0 !== e) {
                var o = utils.scene_decode(e);
                o.gid && (t.id = o.gid);
            }
        } else if (null !== getApp().query) {
            var a = app.query;
            getApp().query = null, t.id = a.gid;
        }
        options_id = t.id;
    },
    onShow: function() {
        getApp().page.onShow(this), this.getGoods({
            id: options_id
        });
    },
    getBinding: function() {
        var t = getApp().core.getStorageSync("USER_INFO");
        t && (t.binding || this.setData({
            user_bind_show: !0
        }));
    },
    getGoods: function(t) {
        var e = t.id;
        console.log(t), getApp().core.showLoading({
            title: "加载中"
        });
        var a = this;
        getApp().request({
            url: getApp().api.lottery.goods,
            data: {
                id: e,
                user_id: t.user_id,
                page: 1
            },
            success: function(t) {
                if (0 == t.code) {
                    var e = t.data.goods.detail, o = t.data.lottery_info.end_time;
                    a.setTimeStart(o), WxParse.wxParse("detail", "html", e, a), a.setData(t.data);
                } else getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(t) {
                        t.confirm && getApp().core.redirectTo({
                            url: "/lottery/index/index"
                        });
                    }
                });
            },
            complete: function(t) {
                getApp().core.hideLoading();
            }
        });
    },
    catchTouchMove: function(t) {
        return !1;
    },
    onHide: function() {
        getApp().page.onHide(this), clearInterval(timer), clearInterval(lotteryInter), this.setData({
            award_bg: !1
        });
    },
    onUnload: function() {
        getApp().page.onUnload(this), clearInterval(timer);
    },
    setTimeStart: function(t) {
        var r = this, e = new Date(), n = parseInt(t - e.getTime() / 1e3);
        clearInterval(timer), timer = setInterval(function() {
            var t = 0, e = 0, o = 0, a = 0;
            0 < n && (t = Math.floor(n / 86400), e = Math.floor(n / 3600) - 24 * t, o = Math.floor(n / 60) - 24 * t * 60 - 60 * e, 
            a = Math.floor(n) - 24 * t * 60 * 60 - 60 * e * 60 - 60 * o);
            var i = {
                day: t,
                hour: e,
                minute: o,
                second: a
            };
            r.setData({
                time_list: i
            }), n--;
        }, 1e3), n <= 0 && clearInterval(timer);
    },
    buyZero: function() {
        var t = this, e = !t.data.award_bg;
        t.setData({
            award_bg: e
        });
        var o = getApp().core.createAnimation({
            duration: 1e3,
            timingFunction: "linear",
            transformOrigin: "50% 50%"
        });
        t.data.award_bg ? o.width("360rpx").height("314rpx").step() : o.scale(0, 0).opacity(0).step(), 
        t.setData({
            animationTranslottery: o.export()
        });
        var a = 0;
        lotteryInter = setInterval(function() {
            a % 2 == 0 ? o.scale(.9).opacity(1).step() : o.scale(1).opacity(1).step(), t.setData({
                animationTranslottery: o.export()
            }), 500 == ++a && (a = 0);
        }, 500);
    },
    submitTime: function() {
        var t = getApp().core.createAnimation({
            duration: 500,
            transformOrigin: "50% 50%"
        }), e = this, o = 0;
        lotteryInter = setInterval(function() {
            o % 2 == 0 ? t.scale(2.3, 2.3).opacity(1).step() : t.scale(2.5, 2.5).opacity(1).step(), 
            e.setData({
                animationTranslottery: t.export()
            }), 500 == ++o && (o = 0);
        }, 500);
    },
    submit: function(t) {
        var e = t.detail.formId, o = t.currentTarget.dataset.lottery_id;
        getApp().core.navigateTo({
            url: "/lottery/detail/detail?lottery_id=" + o + "&form_id=" + e
        }), clearInterval(lotteryInter), this.setData({
            award_bg: !1
        });
    },
    play: function(t) {
        var e = t.target.dataset.url;
        this.setData({
            url: e,
            hide: "",
            show: !0
        }), (videoContext = getApp().core.createVideoContext("video")).play();
    },
    close: function(t) {
        if ("video" == t.target.id) return !0;
        this.setData({
            hide: "hide",
            show: !1
        }), videoContext.pause();
    },
    onGoodsImageClick: function(t) {
        var e = [], o = t.currentTarget.dataset.index;
        for (var a in this.data.goods.pic_list) e.push(this.data.goods.pic_list[a].pic_url);
        getApp().core.previewImage({
            urls: e,
            current: e[o]
        });
    },
    hide: function(t) {
        0 == t.detail.current ? this.setData({
            img_hide: ""
        }) : this.setData({
            img_hide: "hide"
        });
    },
    buyNow: function(t) {
        var e = [], o = {
            goods_id: this.data.goods.id,
            num: 1,
            attr: JSON.parse(this.data.lottery_info.attr)
        };
        e.push(o);
        var a = [];
        a.push({
            mch_id: 0,
            goods_list: e
        }), getApp().core.navigateTo({
            url: "/pages/new-order-submit/new-order-submit?mch_list=" + JSON.stringify(a)
        });
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        var t = getApp().getUser(), e = this.data.lottery_info.id, o = {
            imageUrl: this.data.goods.pic_list[0].pic_url,
            path: "/lottery/goods/goods?id=" + e + "&user_id=" + t.id
        };
        return console.log(o), o;
    },
    showShareModal: function() {
        this.setData({
            share_modal_active: "active"
        });
    },
    shareModalClose: function() {
        this.setData({
            share_modal_active: ""
        });
    },
    getGoodsQrcode: function() {
        var e = this;
        if (e.setData({
            qrcode_active: "active",
            share_modal_active: ""
        }), e.data.goods_qrcode) return !0;
        getApp().request({
            url: getApp().api.lottery.qrcode,
            data: {
                goods_id: e.data.lottery_info.id
            },
            success: function(t) {
                0 == t.code && e.setData({
                    goods_qrcode: t.data.pic_url
                }), 1 == t.code && (e.goodsQrcodeClose(), getApp().core.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(t) {
                        t.confirm;
                    }
                }));
            }
        });
    },
    qrcodeClick: function(t) {
        var e = t.currentTarget.dataset.src;
        getApp().core.previewImage({
            urls: [ e ]
        });
    },
    qrcodeClose: function() {
        this.setData({
            qrcode_active: ""
        });
    },
    goodsQrcodeClose: function() {
        this.setData({
            goods_qrcode_active: "",
            no_scroll: !1
        });
    },
    saveQrcode: function() {
        var e = this;
        getApp().core.saveImageToPhotosAlbum ? (getApp().core.showLoading({
            title: "正在保存图片",
            mask: !1
        }), getApp().core.downloadFile({
            url: e.data.goods_qrcode,
            success: function(t) {
                getApp().core.showLoading({
                    title: "正在保存图片",
                    mask: !1
                }), getApp().core.saveImageToPhotosAlbum({
                    filePath: t.tempFilePath,
                    success: function() {
                        getApp().core.showModal({
                            title: "提示",
                            content: "商品海报保存成功",
                            showCancel: !1
                        });
                    },
                    fail: function(t) {
                        getApp().core.showModal({
                            title: "图片保存失败",
                            content: t.errMsg,
                            showCancel: !1
                        });
                    },
                    complete: function(t) {
                        getApp().core.hideLoading();
                    }
                });
            },
            fail: function(t) {
                getApp().core.showModal({
                    title: "图片下载失败",
                    content: t.errMsg + ";" + e.data.goods_qrcode,
                    showCancel: !1
                });
            },
            complete: function(t) {
                getApp().core.hideLoading();
            }
        })) : getApp().core.showModal({
            title: "提示",
            content: "当前版本过低，无法使用该功能，请升级到最新版本后重试。",
            showCancel: !1
        });
    }
});