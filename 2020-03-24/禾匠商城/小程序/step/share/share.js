Page({
    data: {
        img: [],
        todayStep: 0,
        pic_list: "",
        save: !0,
        page: 1,
        left: !1,
        right: !0,
        get: !1
    },
    tagChoose: function(t) {
        var e = this, o = t.currentTarget.dataset.id, a = e.data.num;
        e.setData({
            currentItem: o
        }), getApp().core.showLoading({
            title: "分享图片生成中...",
            mask: !0
        }), getApp().request({
            url: getApp().api.step.qrcode,
            data: {
                goods_id: o,
                num: a
            },
            success: function(t) {
                console.log(t), 0 == t.code ? e.setData({
                    img: t.data.pic_url
                }) : getApp().core.showModal({
                    content: t.msg,
                    showCancel: !1
                }), setTimeout(function() {
                    getApp().core.hideLoading();
                }, 1e3);
            },
            fail: function(t) {
                getApp().core.showModal({
                    content: t.msg,
                    showCancel: !1
                });
            }
        });
    },
    screen: function() {
        var t = this.data.img;
        getApp().core.previewImage({
            urls: [ t ]
        });
    },
    saveImage: function() {
        var e = this;
        getApp().core.authorize({
            scope: "scope.writePhotosAlbum",
            success: function(t) {
                "authorize:ok" == t.errMsg && getApp().core.getImageInfo({
                    src: e.data.img,
                    success: function(t) {
                        getApp().core.saveImageToPhotosAlbum({
                            filePath: t.path,
                            success: function(t) {
                                getApp().core.showToast({
                                    title: "保存成功，快去发朋友圈吧！",
                                    icon: "success",
                                    duration: 2e3
                                });
                            },
                            fail: function(t) {
                                getApp().core.showModal({
                                    content: "授权失败",
                                    showCancel: !1
                                });
                            }
                        });
                    }
                });
            },
            fail: function(t) {
                getApp().core.showModal({
                    content: "为确保您的正常使用，请点击下方按钮授权",
                    showCancel: !1
                }), e.setData({
                    save: !1,
                    get: !0
                });
            }
        });
    },
    openSetting: function() {
        var e = this;
        wx.openSetting({
            success: function(t) {
                1 == t.authSetting["scope.writePhotosAlbum"] && e.setData({
                    save: !0,
                    get: !1
                });
            },
            fail: function(t) {
                getApp().core.showModal({
                    content: "为确保您的正常使用，请点击下方按钮授权",
                    showCancel: !1
                });
            }
        });
    },
    chooseImg: function(t) {
        var o = this, a = this.data.page, s = !0, i = !0, e = t.currentTarget.dataset.id;
        1 == e ? a-- : 2 == e && a++, getApp().request({
            url: getApp().api.step.pic_list,
            data: {
                page: a
            },
            success: function(t) {
                var e = t.data.pic_list;
                0 == e.length ? (getApp().core.showToast({
                    title: "没有更多了",
                    icon: "none",
                    duration: 1e3
                }), i = !1, o.setData({
                    right: i
                })) : (1 == a && (s = !1), e.length < 4 && (i = !1), o.setData({
                    page: a,
                    pic_list: e,
                    left: s,
                    right: i
                }));
            }
        });
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var a = this, s = 0;
        t.todayStep && (s = t.todayStep), a.setData({
            num: s
        });
        getApp().core.showLoading({
            title: "分享图片生成中...",
            mask: !0
        }), getApp().request({
            url: getApp().api.step.pic_list,
            data: {
                page: 1
            },
            success: function(t) {
                var e = t.data.pic_list, o = !0;
                0 < e[0].pic_url.length ? (e.length < 4 && (o = !1), a.setData({
                    pic_list: e,
                    currentItem: e[0].id,
                    right: o
                }), getApp().request({
                    url: getApp().api.step.qrcode,
                    data: {
                        goods_id: e[0].id,
                        num: s
                    },
                    success: function(t) {
                        setTimeout(function() {
                            0 == t.code ? a.setData({
                                img: t.data.pic_url
                            }) : getApp().core.showModal({
                                content: t.msg,
                                showCancel: !1
                            }), getApp().core.hideLoading();
                        }, 1e3);
                    }
                })) : (getApp().core.hideLoading(), getApp().core.showToast({
                    title: "暂无海报模板",
                    icon: "none",
                    duration: 1e3
                }), setTimeout(function() {
                    getApp().core.navigateTo({
                        url: "../index/index"
                    });
                }, 1e3));
            }
        });
    }
});