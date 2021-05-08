var app = getApp(), api = getApp().api, utils = getApp().helper, videoContext = "", setIntval = null, WxParse = require("../../wxParse/wxParse.js"), userIntval = null, scrollIntval = null, is_loading = !1;

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
        show_content: !1
    },
    onLoad: function(e) {
        if (getApp().page.onLoad(this, e), "undefined" == typeof my) {
            var t = decodeURIComponent(e.scene);
            if (void 0 !== t) {
                var a = utils.scene_decode(t);
                a.gid && (e.goods_id = a.gid);
            }
        } else if (null !== app.query) {
            var o = app.query;
            app.query = null, e.goods_id = o.gid;
        }
        this.getGoods(e.goods_id);
    },
    getGoods: function(e) {
        var a = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.bargain.goods,
            data: {
                goods_id: e,
                page: 1
            },
            success: function(e) {
                if (0 == e.code) {
                    var t = e.data.goods.detail;
                    WxParse.wxParse("detail", "html", t, a), a.setData(e.data), a.setData({
                        reset_time: a.data.goods.reset_time,
                        time_list: a.setTimeList(e.data.goods.reset_time),
                        p: 1,
                        foreshow_time: a.data.goods.foreshow_time,
                        foreshow_time_list: a.setTimeList(a.data.goods.foreshow_time)
                    }), a.setTimeOver(), e.data.bargain_info && a.getUserTime();
                } else getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && getApp().core.navigateBack({
                            delta: -1
                        });
                    }
                });
            },
            complete: function(e) {
                getApp().core.hideLoading();
            }
        });
    },
    onReady: function() {
        app.page.onReady(this);
    },
    onShow: function() {
        app.page.onShow(this);
    },
    onHide: function() {
        app.page.onHide(this);
    },
    onUnload: function() {
        app.page.onUnload(this), clearInterval(setIntval), setIntval = null, clearInterval(userIntval), 
        userIntval = null, clearInterval(scrollIntval), scrollIntval = null;
    },
    play: function(e) {
        var t = e.target.dataset.url;
        this.setData({
            url: t,
            hide: "",
            show: !0
        }), (videoContext = getApp().core.createVideoContext("video")).play();
    },
    close: function(e) {
        if ("video" == e.target.id) return !0;
        this.setData({
            hide: "hide",
            show: !1
        }), videoContext.pause();
    },
    onGoodsImageClick: function(e) {
        var t = [], a = e.currentTarget.dataset.index;
        for (var o in this.data.goods.pic_list) t.push(this.data.goods.pic_list[o].pic_url);
        getApp().core.previewImage({
            urls: t,
            current: t[a]
        });
    },
    hide: function(e) {
        0 == e.detail.current ? this.setData({
            img_hide: ""
        }) : this.setData({
            img_hide: "hide"
        });
    },
    setTimeOver: function() {
        var i = this;
        setIntval = setInterval(function() {
            i.data.resset_time <= 0 && clearInterval(setIntval);
            var e = i.data.reset_time - 1, t = i.setTimeList(e), a = i.data.foreshow_time - 1, o = i.setTimeList(a);
            i.setData({
                reset_time: e,
                time_list: t,
                foreshow_time: a,
                foreshow_time_list: o
            });
        }, 1e3);
    },
    orderSubmit: function() {
        var t = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.bargain.bargain_submit,
            method: "POST",
            data: {
                goods_id: t.data.goods.id
            },
            success: function(e) {
                0 == e.code ? getApp().core.redirectTo({
                    url: "/bargain/activity/activity?order_id=" + e.data.order_id
                }) : t.showToast({
                    title: e.msg
                });
            },
            complete: function(e) {
                getApp().core.hideLoading();
            }
        });
    },
    buyNow: function() {
        var e = [], t = [], a = this.data.bargain_info;
        a && (t.push({
            bargain_order_id: a.order_id
        }), e.push({
            mch_id: 0,
            goods_list: t
        }), getApp().core.redirectTo({
            url: "/pages/new-order-submit/new-order-submit?mch_list=" + JSON.stringify(e)
        }));
    },
    getUserTime: function() {
        var t = this;
        userIntval = setInterval(function() {
            t.loadData();
        }, 1e3), scrollIntval = setInterval(function() {
            var e = t.data.user_index;
            3 < t.data.bargain_info.bargain_info.length - e ? e += 3 : e = 0, t.setData({
                user_index: e
            });
        }, 3e3);
    },
    loadData: function() {
        var o = this, i = o.data.p;
        is_loading || (is_loading = !0, app.request({
            url: api.bargain.goods_user,
            data: {
                page: i + 1,
                goods_id: o.data.goods.id
            },
            success: function(e) {
                if (0 == e.code) {
                    var t = o.data.bargain_info.bargain_info, a = e.data.bargain_info;
                    0 == a.bargain_info.length && (clearInterval(userIntval), userIntval = null), a.bargain_info = t.concat(a.bargain_info), 
                    o.setData({
                        bargain_info: a,
                        p: i + 1
                    });
                } else o.showToast({
                    title: e.msg
                });
            },
            complete: function() {
                is_loading = !1;
            }
        }));
    },
    contentClose: function() {
        this.setData({
            show_content: !1
        });
    },
    contentOpen: function() {
        this.setData({
            show_content: !0
        });
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        return {
            path: "/bargain/list/list?goods_id=" + this.data.goods.id + "&user_id=" + this.data.__user_info.id,
            success: function(e) {},
            title: this.data.goods.name,
            imageUrl: this.data.goods.pic_list[0].pic_url
        };
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
        var t = this;
        if (t.setData({
            qrcode_active: "active",
            share_modal_active: ""
        }), t.data.goods_qrcode) return !0;
        getApp().request({
            url: getApp().api.bargain.qrcode,
            data: {
                goods_id: t.data.goods.id
            },
            success: function(e) {
                0 == e.code && t.setData({
                    goods_qrcode: e.data.pic_url
                }), 1 == e.code && (t.goodsQrcodeClose(), getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm;
                    }
                }));
            }
        });
    },
    qrcodeClick: function(e) {
        var t = e.currentTarget.dataset.src;
        getApp().core.previewImage({
            urls: [ t ]
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
        var t = this;
        getApp().core.saveImageToPhotosAlbum ? (getApp().core.showLoading({
            title: "正在保存图片",
            mask: !1
        }), getApp().core.downloadFile({
            url: t.data.goods_qrcode,
            success: function(e) {
                getApp().core.showLoading({
                    title: "正在保存图片",
                    mask: !1
                }), getApp().core.saveImageToPhotosAlbum({
                    filePath: e.tempFilePath,
                    success: function() {
                        getApp().core.showModal({
                            title: "提示",
                            content: "商品海报保存成功",
                            showCancel: !1
                        });
                    },
                    fail: function(e) {
                        getApp().core.showModal({
                            title: "图片保存失败",
                            content: e.errMsg,
                            showCancel: !1
                        });
                    },
                    complete: function(e) {
                        getApp().core.hideLoading();
                    }
                });
            },
            fail: function(e) {
                getApp().core.showModal({
                    title: "图片下载失败",
                    content: e.errMsg + ";" + t.data.goods_qrcode,
                    showCancel: !1
                });
            },
            complete: function(e) {
                getApp().core.hideLoading();
            }
        })) : getApp().core.showModal({
            title: "提示",
            content: "当前版本过低，无法使用该功能，请升级到最新版本后重试。",
            showCancel: !1
        });
    }
});