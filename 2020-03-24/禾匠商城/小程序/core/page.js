module.exports = {
    currentPage: null,
    currentPageOptions: {},
    navbarPages: [ "pages/index/index", "pages/cat/cat", "pages/cart/cart", "pages/user/user", "pages/list/list", "pages/search/search", "pages/topic-list/topic-list", "pages/video/video-list", "pages/miaosha/miaosha", "pages/shop/shop", "pages/pt/index/index", "pages/book/index/index", "pages/share/index", "pages/quick-purchase/index/index", "mch/m/myshop/myshop", "mch/shop-list/shop-list", "pages/integral-mall/index/index", "pages/integral-mall/register/index", "pages/article-detail/article-detail", "pages/article-list/article-list", "pages/order/order" ],
    onLoad: function(t, e) {
        this.currentPage = t, this.currentPageOptions = e;
        var o = this;
        if (this.setUserInfo(), this.setWxappImg(), this.setStore(), this.setParentId(e), 
        this.getNavigationBarColor(), this.setDeviceInfo(), this.setPageClasses(), this.setPageNavbar(null), 
        this.setBarTitle(), this.setNavi(), "function" == typeof t.onSelfLoad && t.onSelfLoad(e), 
        o._setFormIdSubmit(), "undefined" != typeof my && "pages/login/login" != t.route && e && (t.options || (t.options = e), 
        getApp().core.setStorageSync("last_page_options", e)), "lottery/goods/goods" == t.route && e) {
            if (e.user_id) var n = e.user_id, a = e.id; else if (e.scene && isNaN(e.scene)) {
                var i = decodeURIComponent(e.scene);
                if (i && (i = getApp().helper.scene_decode(i)) && i.uid) n = i.uid, a = i.gid;
            }
            getApp().request({
                data: {
                    user_id: n,
                    lottery_id: a
                },
                url: getApp().api.lottery.clerk,
                success: function(e) {
                    e.code;
                }
            });
        }
        t.navigatorClick = function(e) {
            o.navigatorClick(e, t);
        }, t.setData({
            __platform: getApp().platform
        }), void 0 === t.showToast && (t.showToast = function(e) {
            o.showToast(e);
        }), getApp().shareSendCoupon = function(e) {
            o.shareSendCoupon(e);
        }, void 0 === t.setTimeList && (t.setTimeList = function(e) {
            return o.setTimeList(e);
        }), void 0 === t.showLoading && (t.showLoading = function(e) {
            o.showLoading(e);
        }), void 0 === t.hideLoading && (t.hideLoading = function(e) {
            o.hideLoading(e);
        }), void 0 === t.modalConfirm && (t.modalConfirm = function(e) {
            o.modalConfirm(e);
        }), void 0 === t.modalClose && (t.modalClose = function(e) {
            o.modalClose(e);
        }), void 0 === t.modalShow && (t.modalShow = function(e) {
            o.modalShow(e);
        }), void 0 === t.myLogin && (t.myLogin = function() {
            o.myLogin();
        }), void 0 === t.getUserInfo && (t.getUserInfo = function(e) {
            o.getUserInfo(e);
        }), void 0 === t.getPhoneNumber && (t.getPhoneNumber = function(e) {
            o.getPhoneNumber(e);
        }), void 0 === t.bindParent && (t.bindParent = function(e) {
            o.bindParent(e);
        }), void 0 === t.closeCouponBox && (t.closeCouponBox = function(e) {
            o.closeCouponBox(e);
        }), void 0 === t.relevanceSuccess && (t.relevanceSuccess = function(e) {
            o.relevanceSuccess(e);
        }), void 0 === t.relevanceError && (t.relevanceError = function(e) {
            o.relevanceError(e);
        }), void 0 === t.saveQrcode && (t.saveQrcode = function(e) {
            o.saveQrcode(e);
        }), void 0 === t.setUserInfoShowFalse && (t.setUserInfoShowFalse = function(e) {
            o.setUserInfoShowFalse();
        }), void 0 === t.cancelLogin && (t.cancelLogin = function(e) {
            o.cancelLogin();
        });
    },
    onReady: function(e) {
        this.currentPage = e;
    },
    onShow: function(e) {
        var t = getApp();
        if (this.currentPage = e, t.onShowData && t.onShowData.scene) {
            var o = [ 1045, 1046, 1058, 1067, 1084, 1091 ];
            0 <= o.indexOf(t.onShowData.scene) ? this.setPageNavbar(t.onShowData.scene) : (console.log("no in array--\x3e", t.onShowData.scene), 
            console.log("the array--\x3e", o));
        }
        getApp().orderPay.init(e, getApp()), require("../components/quick-navigation/quick-navigation.js").init(this.currentPage);
    },
    onHide: function(e) {
        this.currentPage = e;
    },
    onUnload: function(e) {
        this.currentPage = e;
    },
    onPullDownRefresh: function(e) {
        this.currentPage = e;
    },
    onReachBottom: function(e) {
        this.currentPage = e;
    },
    onShareAppMessage: function(e) {
        this.currentPage = e, setTimeout(function() {
            getApp().shareSendCoupon(e);
        }, 1e3);
    },
    imageClick: function(e) {
        console.log("image click", e);
    },
    textClick: function(e) {
        console.log("text click", e);
    },
    tap1: function(e) {
        console.log("tap1", e);
    },
    tap2: function(e) {
        console.log("tap2", e);
    },
    formSubmit_collect: function(e) {
        e.detail.formId;
        console.log("formSubmit_collect--\x3e", e);
    },
    setUserInfo: function() {
        var e = this.currentPage, t = getApp().getUser();
        t && e.setData({
            __user_info: t
        });
    },
    setWxappImg: function(e) {
        var t = this.currentPage;
        getApp().getConfig(function(e) {
            t.setData({
                __wxapp_img: e.wxapp_img,
                store: e.store
            });
        });
    },
    setStore: function(e) {
        var t = this.currentPage;
        getApp().getConfig(function(e) {
            e.store && t.setData({
                store: e.store,
                __is_comment: e.store ? e.store.is_comment : 1,
                __is_sales: e.store ? e.store.is_sales : 1,
                __is_member_price: e.store ? e.store.is_member_price : 1,
                __is_share_price: e.store ? e.store.is_share_price : 1,
                __alipay_mp_config: e.alipay_mp_config
            });
        });
    },
    setParentId: function(e) {
        var t = this.currentPage;
        "/pages/index/index" == t.route && this.setOfficalAccount();
        var o = 0;
        if (e) {
            if (e.user_id) o = e.user_id; else if (e.scene) {
                if (isNaN(e.scene)) {
                    var n = decodeURIComponent(e.scene);
                    n && (n = getApp().helper.scene_decode(n)) && n.uid && (o = n.uid);
                } else -1 == t.route.indexOf("clerk") && (o = e.scene);
                this.setOfficalAccount();
            }
        } else if (null !== getApp().query) {
            var a = getApp().query;
            void 0 !== a.uid ? o = a.uid : void 0 !== a.user_id && (o = a.user_id);
        }
        o && void 0 !== o && 0 < o && (getApp().core.setStorageSync(getApp().const.PARENT_ID, o), 
        getApp().trigger.remove(getApp().trigger.events.login, "TRY_TO_BIND_PARENT"), getApp().trigger.add(getApp().trigger.events.login, "TRY_TO_BIND_PARENT", function() {
            t.bindParent({
                parent_id: o,
                condition: 0
            });
        }));
    },
    showToast: function(e) {
        var t = this.currentPage, o = e.duration || 2500, n = e.title || "", a = (e.success, 
        e.fail, e.complete || null);
        t._toast_timer && clearTimeout(t._toast_timer), t.setData({
            _toast: {
                title: n
            }
        }), t._toast_timer = setTimeout(function() {
            var e = t.data._toast;
            e.hide = !0, t.setData({
                _toast: e
            }), "function" == typeof a && a();
        }, o);
    },
    setDeviceInfo: function() {
        var e = this.currentPage, t = [ {
            id: "device_iphone_5",
            model: "iPhone 5"
        }, {
            id: "device_iphone_x",
            model: "iPhone X"
        } ], o = getApp().core.getSystemInfoSync();
        if (o.model) for (var n in 0 <= o.model.indexOf("iPhone X") && (o.model = "iPhone X"), 
        t) t[n].model == o.model && e.setData({
            __device: t[n].id
        });
    },
    setPageNavbar: function(s) {
        var c = this, p = this.currentPage, e = getApp().core.getStorageSync("_navbar");
        e && n(e);
        var t = !1;
        for (var o in c.navbarPages) if (p.route == c.navbarPages[o]) {
            t = !0;
            break;
        }
        function n(e) {
            var t = !1;
            for (var o in e.navs) {
                var n = e.navs[o].url, a = p.route || p.__route__ || null;
                if (n = e.navs[o].new_url, void 0 !== e.navs[o].params || "/pages/index/index" == e.navs[o].url) for (var i in p.options) getApp().helper.inArray(i, [ "scene", "user_id", "uid" ]) || "page_id" == i && -1 == p.options[i] || (-1 == a.indexOf("?") ? a += "?" : a += "&", 
                a += i + "=" + p.options[i]);
                console.log(a);
                var r = a;
                1058 == s && -1 != r.indexOf("?appid=") && (r = r.substr(0, r.indexOf("?appid="))), 
                n === "/" + r ? t = e.navs[o].active = !0 : e.navs[o].active = !1;
            }
            t && (p.setData({
                _navbar: e
            }), c.setPageClasses());
        }
        t && getApp().request({
            url: getApp().api.default.navbar,
            success: function(e) {
                0 == e.code && (n(e.data), getApp().core.setStorageSync("_navbar", e.data));
            }
        });
    },
    setPageClasses: function() {
        var e = this.currentPage, t = e.data.__device;
        e.data._navbar && e.data._navbar.navs && 0 < e.data._navbar.navs.length && (t += " show_navbar"), 
        t && e.setData({
            __page_classes: t
        });
    },
    showLoading: function(e) {
        var t = t;
        t.setData({
            _loading: !0
        });
    },
    hideLoading: function(e) {
        this.currentPage.setData({
            _loading: !1
        });
    },
    setTimeList: function(e) {
        function t(e) {
            return e <= 0 && (e = 0), e < 10 ? "0" + e : e;
        }
        var o = "00", n = "00", a = "00", i = 0, r = "", s = "", c = "";
        if (86400 <= e && (i = parseInt(e / 86400), e %= 86400, r += i + "天", s += i + "天", 
        c += i + "天"), e < 86400) {
            var p = parseInt(e / 3600);
            e %= 3600, r += (a = t(p)) + "小时", s += a + ":", c = 0 < i || 0 < p ? c + a + ":" : "";
        }
        return e < 3600 && (n = t(parseInt(e / 60)), e %= 60, r += n + "分", s += n + ":", 
        c += n + ":"), e < 60 && (r += (o = t(e)) + "秒", s += o, c += o), {
            d: i,
            h: a,
            m: n,
            s: o,
            content: r,
            content_1: s,
            content_ms: c
        };
    },
    setBarTitle: function(e) {
        var t = this.currentPage.route, o = getApp().core.getStorageSync(getApp().const.WX_BAR_TITLE);
        for (var n in o) o[n].url === t && getApp().core.setNavigationBarTitle({
            title: o[n].title
        });
    },
    getNavigationBarColor: function() {
        var t = getApp(), o = this;
        t.request({
            url: t.api.default.navigation_bar_color,
            success: function(e) {
                0 == e.code && (t.core.setStorageSync(getApp().const.NAVIGATION_BAR_COLOR, e.data), 
                o.setNavigationBarColor(), t.navigateBarColorCall && "function" == typeof t.navigateBarColorCall && t.navigateBarColorCall(e));
            }
        });
    },
    setNavigationBarColor: function() {
        var t = this.currentPage, e = getApp().core.getStorageSync(getApp().const.NAVIGATION_BAR_COLOR);
        e && (getApp().core.setNavigationBarColor(e), t.setData({
            _navigation_bar_color: e
        })), getApp().navigateBarColorCall = function(e) {
            getApp().core.setNavigationBarColor(e.data), t.setData({
                _navigation_bar_color: e.data
            });
        };
    },
    navigatorClick: function(e, t) {
        var o = e.currentTarget.dataset.open_type;
        if ("redirect" == o) return !0;
        if ("wxapp" != o) {
            if ("tel" == o) {
                var n = e.currentTarget.dataset.tel;
                getApp().core.makePhoneCall({
                    phoneNumber: n
                });
            }
            return !1;
        }
    },
    shareSendCoupon: function(o) {
        var n = getApp();
        n.core.showLoading({
            mask: !0
        }), o.hideGetCoupon || (o.hideGetCoupon = function(e) {
            var t = e.currentTarget.dataset.url || !1;
            o.setData({
                get_coupon_list: null
            }), t && n.core.navigateTo({
                url: t
            });
        }), n.request({
            url: n.api.coupon.share_send,
            success: function(e) {
                0 == e.code && o.setData({
                    get_coupon_list: e.data.list
                });
            },
            complete: function() {
                n.core.hideLoading();
            }
        });
    },
    bindParent: function(e) {
        var t = getApp();
        if ("undefined" != e.parent_id && 0 != e.parent_id) {
            var o = t.getUser();
            if (0 < t.core.getStorageSync(t.const.SHARE_SETTING).level) 0 != e.parent_id && t.request({
                url: t.api.share.bind_parent,
                data: {
                    parent_id: e.parent_id,
                    condition: e.condition
                },
                success: function(e) {
                    0 == e.code && (o.parent = e.data, t.setUser(o));
                }
            });
        }
    },
    _setFormIdSubmit: function(e) {
        var g = this.currentPage;
        g._formIdSubmit || (g._formIdSubmit = function(e) {
            var t = e.currentTarget.dataset, o = e.detail.formId, n = t.bind || null, a = t.type || null, i = t.url || null, r = t.appId || null, s = getApp().core.getStorageSync(getApp().const.FORM_ID_LIST);
            s && s.length || (s = []);
            var c = [];
            for (var p in s) c.push(s[p].form_id);
            switch (console.log("form_id"), "the formId is a mock one" === o || getApp().helper.inArray(o, c) || (s.push({
                time: getApp().helper.time(),
                form_id: o
            }), getApp().core.setStorageSync(getApp().const.FORM_ID_LIST, s)), g[n] && "function" == typeof g[n] && g[n](e), 
            a) {
              case "navigate":
                i && getApp().core.navigateTo({
                    url: i
                });
                break;

              case "redirect":
                i && getApp().core.redirectTo({
                    url: i
                });
                break;

              case "switchTab":
                i && getApp().core.switchTab({
                    url: i
                });
                break;

              case "reLaunch":
                i && getApp().core.reLaunch({
                    url: i
                });
                break;

              case "navigateBack":
                i && getApp().core.navigateBack({
                    url: i
                });
                break;

              case "wxapp":
                r && getApp().core.navigateToMiniProgram({
                    url: i,
                    appId: r,
                    path: t.path || ""
                });
            }
        });
    },
    modalClose: function(e) {
        this.currentPage.setData({
            modal_show: !1
        }), console.log("你点击了关闭按钮");
    },
    modalConfirm: function(e) {
        this.currentPage.setData({
            modal_show: !1
        }), console.log("你点击了确定按钮");
    },
    modalShow: function(e) {
        this.currentPage.setData({
            modal_show: !0
        }), console.log("点击会弹出弹框");
    },
    getUserInfo: function(o) {
        var n = this;
        "getUserInfo:ok" == o.detail.errMsg && getApp().core.login({
            success: function(e) {
                var t = e.code;
                n.unionLogin({
                    code: t,
                    user_info: o.detail.rawData,
                    encrypted_data: o.detail.encryptedData,
                    iv: o.detail.iv,
                    signature: o.detail.signature
                });
            },
            fail: function(e) {}
        });
    },
    myLogin: function(e) {
        var n = this;
        "my" === getApp().platform && (getApp().login_complete || (getApp().login_complete = !0, 
        my.getAuthCode({
            scopes: "auth_base",
            success: function(o) {
                my.getOpenUserInfo({
                    success: function(e) {
                        var t = JSON.parse(e.response);
                        t.response && t.response.code && "10000" == t.response.code && n.unionLogin({
                            code: o.authCode,
                            user_info: JSON.stringify(t.response)
                        });
                    }
                });
            },
            fail: function(e) {
                getApp().login_complete = !1, getApp().core.redirectTo({
                    url: "/pages/index/index"
                });
            }
        })));
    },
    unionLogin: function(e) {
        var o = this.currentPage, n = this;
        getApp().core.showLoading({
            title: "正在登录",
            mask: !0
        }), getApp().is_login = !0, getApp().request({
            url: getApp().api.passport.login,
            method: "POST",
            data: e,
            success: function(e) {
                if (0 == e.code) {
                    o.setData({
                        __user_info: e.data
                    }), getApp().setUser(e.data), getApp().core.setStorageSync(getApp().const.ACCESS_TOKEN, e.data.access_token), 
                    getApp().trigger.run(getApp().trigger.events.login);
                    var t = getApp().core.getStorageSync(getApp().const.STORE);
                    e.data.binding || !t.option.phone_auth || t.option.phone_auth && 0 == t.option.phone_auth ? n.loadRoute() : ("undefined" == typeof wx && n.loadRoute(), 
                    n.setPhone()), n.setUserInfoShowFalse();
                } else getApp().is_login = !1, getApp().login_complete = !1, getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1
                });
            },
            fail: function() {
                getApp().login_complete = !1;
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        });
    },
    getPhoneNumber: function(o) {
        var n = this.currentPage, a = this;
        "getPhoneNumber:fail user deny" == o.detail.errMsg ? getApp().core.showModal({
            title: "提示",
            showCancel: !1,
            content: "未授权"
        }) : (getApp().core.showLoading({
            title: "授权中"
        }), getApp().core.login({
            success: function(e) {
                if (e.code) {
                    var t = e.code;
                    getApp().request({
                        url: getApp().api.user.user_binding,
                        method: "POST",
                        data: {
                            iv: o.detail.iv,
                            encryptedData: o.detail.encryptedData,
                            code: t
                        },
                        success: function(e) {
                            if (0 == e.code) {
                                var t = n.data.__user_info;
                                t.binding = e.data.dataObj, getApp().setUser(t), n.setData({
                                    PhoneNumber: e.data.dataObj,
                                    __user_info: t,
                                    binding: !0,
                                    binding_num: e.data.dataObj
                                }), a.loadRoute();
                            } else getApp().core.showToast({
                                title: "授权失败,请重试"
                            });
                        },
                        complete: function(e) {
                            getApp().core.hideLoading();
                        }
                    });
                } else getApp().core.showToast({
                    title: "获取用户登录态失败！" + e.errMsg
                });
            }
        }));
    },
    setUserInfoShow: function() {
        this.currentPage.setData({
            user_info_show: !0
        });
    },
    setPhone: function() {
        var e = this.currentPage;
        "undefined" == typeof my && e.setData({
            user_bind_show: !0
        });
    },
    setUserInfoShowFalse: function() {
        this.currentPage.setData({
            user_info_show: !1
        });
    },
    closeCouponBox: function(e) {
        this.currentPage.setData({
            get_coupon_list: ""
        });
    },
    relevanceSuccess: function(e) {
        console.log(e);
    },
    relevanceError: function(e) {
        console.log(e);
    },
    setOfficalAccount: function(e) {
        this.currentPage.setData({
            __is_offical_account: !0
        });
    },
    loadRoute: function() {
        var e = this.currentPage;
        "pages/index/index" == e.route || getApp().core.redirectTo({
            url: "/" + e.route + "?" + getApp().helper.objectToUrlParams(e.options)
        }), this.setUserInfoShowFalse();
    },
    setNavi: function() {
        var o = this.currentPage;
        -1 != [ "pages/index/index", "pages/book/details/details", "pages/pt/details/details", "pages/goods/goods" ].indexOf(this.currentPage.route) && o.setData({
            home_icon: !0
        }), getApp().getConfig(function(e) {
            var t = e.store.quick_navigation;
            t.home_img || (t.home_img = "/images/quick-home.png"), o.setData({
                setnavi: t
            });
        });
    },
    saveQrcode: function() {
        var t = this.currentPage;
        getApp().core.saveImageToPhotosAlbum ? (getApp().core.showLoading({
            title: "正在保存图片",
            mask: !1
        }), getApp().core.downloadFile({
            url: t.data.qrcode_pic,
            success: function(e) {
                getApp().core.showLoading({
                    title: "正在保存图片",
                    mask: !1
                }), getApp().core.saveImageToPhotosAlbum({
                    filePath: e.tempFilePath,
                    success: function() {
                        getApp().core.showModal({
                            title: "提示",
                            content: "保存成功",
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
    },
    cancelLogin: function() {
        this.setUserInfoShowFalse();
    }
};