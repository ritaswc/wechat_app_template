var p_animation, bout, animation;

Page({
    data: {
        circleList: [],
        awardList: [],
        colorCircleFirst: "#F12416",
        colorCircleSecond: "#FFFFFF",
        colorAwardDefault: "#F5F0FC",
        colorAwardSelect: "#ffe400",
        indexSelect: 0,
        isRunning: !1,
        prize: !1,
        close: !1,
        lottert: 0,
        animationData: "",
        time: !1,
        title: ""
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var a = this;
        getApp().request({
            url: getApp().api.pond.setting,
            success: function(t) {
                if (0 == t.code) {
                    var e = t.data.title;
                    e && (getApp().core.setNavigationBarTitle({
                        title: e
                    }), a.setData({
                        title: e
                    }));
                }
            }
        });
    },
    onShow: function() {
        getApp().page.onShow(this);
        var n = this;
        getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.pond.index,
            success: function(t) {
                n.setData(t.data);
                for (var e = t.data.list, a = 18, o = 18, i = 0; i < 8; i++) 0 == i ? o = a = 18 : i < 3 ? (a = a, 
                o = o + 196 + 8) : i < 5 ? (o = o, a = a + 158 + 8) : i < 7 ? (o = o - 196 - 8, 
                a = a) : i < 8 && (o = o, a = a - 158 - 8), e[i].topAward = a, e[i].leftAward = o;
                n.setData({
                    awardList: e
                });
            },
            complete: function(t) {
                getApp().core.hideLoading();
                for (var e = 4, a = 4, o = [], i = 0; i < 24; i++) {
                    if (0 == i) e = a = 8; else if (i < 6) a = 4, e += 110; else if (6 == i) a = 8, 
                    e = 660; else if (i < 12) a += 92, e = 663; else if (12 == i) a = 545, e = 660; else if (i < 18) a = 550, 
                    e -= 110; else if (18 == i) a = 545, e = 10; else {
                        if (!(i < 24)) return;
                        a -= 92, e = 5;
                    }
                    o.push({
                        topCircle: a,
                        leftCircle: e
                    });
                }
                n.setData({
                    circleList: o
                }), bout = setInterval(function() {
                    "#FFFFFF" == n.data.colorCircleFirst ? n.setData({
                        colorCircleFirst: "#F12416",
                        colorCircleSecond: "#FFFFFF"
                    }) : n.setData({
                        colorCircleFirst: "#FFFFFF",
                        colorCircleSecond: "#F12416"
                    });
                }, 900), n.pond_animation();
            }
        });
    },
    startGame: function() {
        var n = this;
        if (!n.data.isRunning) {
            var t = "";
            if (0 == n.data.oppty && (t = "抽奖机会不足"), n.data.integral || (t = "积分不足"), n.data.time || (t = "活动未开始或已经结束"), 
            t) getApp().core.showModal({
                title: "很抱歉",
                content: t,
                showCancel: !1,
                success: function(t) {
                    t.confirm && n.setData({
                        isRunning: !1
                    });
                }
            }); else {
                clearInterval(p_animation), animation.translate(0, 0).step(), n.setData({
                    animationData: animation.export(),
                    isRunning: !0,
                    lottert: 0
                });
                var e = n.data.indexSelect, a = 0, s = n.data.awardList, r = setInterval(function() {
                    if (e++, e %= 8, a += 30, n.setData({
                        indexSelect: e
                    }), 0 < n.data.lottert && e + 1 == n.data.lottert) {
                        if (clearInterval(r), n.pond_animation(), 5 == s[e].type) var t = 1; else t = 2;
                        n.setData({
                            isRunning: !1,
                            name: s[e].name,
                            num: s[e].id,
                            prize: t
                        });
                    }
                }, 200 + a);
                getApp().request({
                    url: getApp().api.pond.lottery,
                    success: function(o) {
                        if (1 == o.code) return clearInterval(r), getApp().core.showModal({
                            title: "很抱歉",
                            content: o.msg ? o.msg : "网络错误",
                            showCancel: !1,
                            success: function(t) {
                                t.confirm && _this.setData({
                                    isRunning: !1
                                });
                            }
                        }), void n.pond_animation();
                        "积分不足" == o.msg && n.setData({
                            integral: !1
                        });
                        var i = o.data.id;
                        s.forEach(function(t, e, a) {
                            t.id == i && setTimeout(function() {
                                n.setData({
                                    lottert: e + 1,
                                    oppty: o.data.oppty
                                });
                            }, 2e3);
                        });
                    }
                });
            }
        }
    },
    pondClose: function() {
        this.setData({
            prize: !1
        });
    },
    pond_animation: function() {
        var t = this;
        animation = getApp().core.createAnimation({
            duration: 500,
            timingFunction: "step-start",
            delay: 0,
            transformOrigin: "50% 50%"
        });
        var e = !0;
        p_animation = setInterval(function() {
            e ? (animation.translate(0, 0).step(), e = !1) : (animation.translate(0, -3).step(), 
            e = !0), t.setData({
                animationData: animation.export()
            });
        }, 900);
    },
    onHide: function() {
        getApp().page.onHide(this), clearInterval(bout), clearInterval(p_animation);
    },
    onShareAppMessage: function() {
        getApp().page.onShareAppMessage(this), this.setData({
            share_modal_active: !1
        });
        var t = {
            path: "/pond/pond/pond?user_id=" + getApp().getUser().id,
            title: this.data.title ? this.data.title : "九宫格抽奖"
        };
        setTimeout(function() {
            return t;
        }, 500);
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
            url: getApp().api.pond.qrcode,
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
    },
    goodsQrcodeClose: function() {
        this.setData({
            goods_qrcode_active: "",
            no_scroll: !1
        });
    }
});