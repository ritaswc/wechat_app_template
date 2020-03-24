var WxParse = require("./../../wxParse/wxParse.js");

Page({
    data: {},
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var t = this;
        if ("undefined" == typeof my) {
            var o = decodeURIComponent(e.scene);
            if (void 0 !== o) {
                var a = getApp().helper.scene_decode(o);
                a.uid && a.gid && (e.id = a.gid);
            }
        } else if (null !== getApp().query) {
            var i = app.query;
            getApp().query = null, e.id = i.gid;
        }
        getApp().request({
            url: getApp().api.default.topic,
            data: {
                id: e.id
            },
            success: function(e) {
                0 == e.code ? (t.setData(e.data), WxParse.wxParse("content", "html", e.data.content, t)) : getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1,
                    success: function(e) {
                        e.confirm && getApp().core.redirectTo({
                            url: "/pages/index/index"
                        });
                    }
                });
            }
        });
    },
    wxParseTagATap: function(e) {
        if (e.currentTarget.dataset.goods) {
            var t = e.currentTarget.dataset.src || !1;
            if (!t) return;
            getApp().core.navigateTo({
                url: t
            });
        }
    },
    quickNavigation: function() {
        this.setData({
            quick_icon: !this.data.quick_icon
        });
        var e = getApp().core.createAnimation({
            duration: 300,
            timingFunction: "ease-out"
        });
        this.data.quick_icon ? e.opacity(0).step() : e.translateY(-55).opacity(1).step(), 
        this.setData({
            animationPlus: e.export()
        });
    },
    favoriteClick: function(e) {
        var t = this, o = e.currentTarget.dataset.action;
        getApp().request({
            url: getApp().api.user.topic_favorite,
            data: {
                topic_id: t.data.id,
                action: o
            },
            success: function(e) {
                getApp().core.showToast({
                    title: e.msg
                }), 0 == e.code && t.setData({
                    is_favorite: o
                });
            }
        });
    },
    onShow: function() {
        getApp().page.onShow(this);
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this);
        var e = getApp().getUser(), t = this.data.id;
        return {
            title: this.data.title,
            path: "/pages/topic/topic?id=" + t + "&user_id=" + e.id
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
            url: getApp().api.default.topic_qrcode,
            data: {
                goods_id: t.data.id
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
                            content: "专题海报保存成功",
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