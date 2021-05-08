!function () {
    "use strict";
    function e(e, t, n) {
        if (!A) return void d("app key is empty");
        var o = I + "/api/error", r = {
            appkey: A,
            system_info: g.getSystemInfo(),
            user_info: g.getUserInfo(),
            version: t,
            msg: e
        };
        return [o, r, n]
    }

    function t() {
        return 1 === parseInt(g.get("wxAuth"))
    }

    function n(res, e) {
        o();
        var n = res;
        var t = function () {
            var t = g.getFakeOpenID();
            return "function" == typeof e && e(t)
        };
        wx.login({
            success: function (n) {
                var code = n.code;
                return n.code ? void wx.request({
                    url: I + "/data/wechat/login",
                    data: { hotAppKey: A, code: n.code, sdkVersion: g.getVersion() },
                    method: "POST",
                    success: function (t) {
                        var n = t.data.openid;
                        return l.isEmpty(n) && (n = g.getFakeOpenID()), g.setOpenID(n), "function" == typeof e && e(n, code)
                    },
                    fail: t
                }) : t()
            }, fail: t
        })
    }

    function o(e) {
        void 0 === e && (e = A);
        var t = "hotAppKey不能为空";
        if (l.isEmpty(e)) throw wx.showToast({ title: t }), Error(t)
    }

    function r(e, r) {
        e = e || g.getHotAppKey(), o(e), g.set("hotAppKey", e), d("hotAppKey: " + A + " 已初始化");
        var p = function () {
            t() ? (d("conf.wxAuth disable our auth dialog"), g.set("userInfo", {}), a()) : wx.getUserInfo({
                success: function (e) {
                    g.set("userInfo", e.userInfo), a()
                }
            })
        }, i = g.getOpenID();
        return i ? p() : void n(p)
    }

    function a() {
        wx.request({
            url: I + "/data/wechat/launch",
            data: {
                hotAppKey: A,
                openId: g.getOpenID(),
                hotAppUUID: g.getHotAppUUID(),
                userInfo: g.getUserInfo(),
                systemInfo: g.getSystemInfo(),
                phoneTime: Date.parse(new Date) / 1e3,
                hotAppVersion: g.getVersion(),
                sdkVersion: g.getVersion()
            },
            method: "POST",
            success: function (e) {
                var t = g.get("uploadType");
                if (0 == t) g.set("uploadType", e.data.upload_type); else {
                    var n = wx.getStorageSync("hotAppEvent") || [];
                    if (0 == n.length) return;
                    wx.request({
                        url: I + "/data/wechat/event",
                        data: { hotAppKey: A, openId: g.getOpenID(), hotAppUUID: g.getHotAppUUID(), eventArray: n },
                        method: "POST",
                        success: function (e) {
                            d(wx.getStorageSync("hotAppEvent") || [], e.data);
                            try {
                                wx.removeStorageSync("hotAppEvent")
                            } catch (e) {
                                d(e)
                            }
                        },
                        fail: function () {
                            d("send event fail"), wx.setStorageSync("hotAppEvent", n)
                        }
                    })
                }
            },
            fail: function (e) {
                d("send launch fail: ", e)
            }
        })
    }

    function p(e, t) {
        t = void 0 === t ? "" : t;
        var n = g.getHotAppKey(), o = g.get("uploadType"), r = g.getHotAppHost();
        if (n) {
            var a = wx.getStorageSync("hotAppEvent") || [], p = {
                eventId: e,
                eventValue: t,
                phoneTime: Date.parse(new Date) / 1e3
            };
            a.push(p), 0 != o ? wx.setStorageSync("hotAppEvent", a) : wx.request({
                url: r + "/data/wechat/event",
                data: {
                    hotAppKey: g.getHotAppKey(),
                    openId: g.getOpenID(),
                    hotAppUUID: g.getHotAppUUID(),
                    eventArray: a,
                    hotAppVersion: g.getVersion()
                },
                method: "POST",
                success: function (e) {
                    d("hotAppEvent", wx.getStorageSync("hotAppEvent") || []);
                    try {
                        wx.removeStorageSync("hotAppEvent")
                    } catch (e) {
                        d("remove hotAppEvent failed", e)
                    }
                },
                fail: function () {
                    d("send event fail"), wx.setStorageSync("hotAppEvent", a)
                }
            })
        } else d("hotappkey is empty")
    }

    function i(e, t) {
        g.validKey(["hotAppHost", "hotAppKey"]);
        var n = g.getHotAppHost(), o = n + "/api/searchkey", r = { appkey: g.get("hotAppKey") };
        l.map(e, function (e, t) {
            r[e] = t
        }), y.http(o, r, t)
    }

    function u(e, t, n, o) {
        var r = g.getSystemInfo(), a = g.getUserInfo(), p = g.getHotAppHost();
        if (l.isEmpty(a)) return d("userinfo is empty"), "function" == typeof o && o(!1);
        var i = p + "/api/feedback", u = {
            appkey: g.getHotAppKey(),
            content: e,
            openid: g.getOpenID() ? g.getOpenID() : g.getFakeOpenID(),
            content_type: t,
            contract_info: n,
            system_info: r,
            user_info: a
        };
        y.http(i, u, o)
    }

    function s(e) {
        wx.chooseImage({
            success: function (t) {
                d(t);
                var n = t.tempFilePaths;
                wx.uploadFile({
                    url: I + "/api/feedback/image/upload",
                    filePath: n[0],
                    name: "file",
                    formData: { appkey: A },
                    success: function (t) {
                        var n = t.data;
                        return l.assert.isString(t.data) && (n = JSON.parse(t.data)), 0 == n.ret ? "function" == typeof e && e(n.image_url) : "function" == typeof e && e(!1)
                    },
                    fail: function (t) {
                        return "function" == typeof e && e(!1)
                    }
                })
            }, fail: function (t) {
                return d("choose img failed"), "function" == typeof e && e(!1)
            }
        })
    }

    function c(e, t) {
        if ("object" != typeof e || !e.__route__) return void d("context error");
        if (!A) return void d("hotapp key is empty");
        var n = e.__route__, o = setInterval(function () {
            var e = g.getOpenID(), t = g.getUserInfo();
            l.isEmpty(e) || l.isEmpty(t) || (clearInterval(o), d("clear timer check_env, trigger page onload event"), r(e, t))
        }, 200), r = function (e, o) {
            var r = I + "/data/wechat/param";
            for (var a in t) t[a] = unescape(t[a].replace(/\\u/g, "%u"));
            var p = { hotAppKey: A, page: n, openId: e, hotAppUUID: g.getHotAppUUID(), paraInfo: t };
            p.paraInfo.userInfo = o, y.http(r, p)
        }
    }

    function f(e, t) {
        if (!A) return void d("hotapp key is empty");
        if ("object" != typeof e || !e.__route__) return void d("context error, must be in Page instance scope");
        var n = t.path, o = g.getOpenID();
        o || (o = g.getFakeOpenID(), d("shareMessage use fake openId: " + o));
        var r = I + "/data/wechat/share", a = function (e) {
            var t = {}, n = e.indexOf("?");
            if (!v.isString(e) || n === -1) return t;
            var o = e.substring(n + 1), r = o.split("&");
            return l.map(r, function (e) {
                var n = e.split("=");
                t[n[0]] = n[1] || ""
            }), t
        }, p = a(n);
        d("shareMessage params: ", p);
        var i = { hotAppKey: A, page: e.__route__, openId: o, hotAppUUID: g.getHotAppUUID(), params: p };
        y.http(r, i);
        var u = "?hotapp_share_id=" + o, s = n.indexOf("?") === -1 ? n + u : n.substring(0, n.indexOf("?")) + u;
        if (v.isObject(p) && !l.isEmpty(p)) {
            var c = [];
            Object.keys(p).forEach(function (e) {
                c.push(e + "=" + encodeURIComponent(p[e]))
            }), s += "&" + c.join("&")
        }
        return d("shareUrl: ", s), t.path = s, t
    }

    function h(e, t, n) {
        if (e[t]) {
            var o = e[t], r = 3, a = 0;
            e[t] = function (e) {
                if ("onShareAppMessage" == t) {
                    if (!o || !v.isFunction(o)) return;
                    var p = o.apply(this, arguments);
                    return n.call(this, p)
                }
                if ("onError" == t) {
                    var i = n.apply(this, arguments);
                    if (v.isArray(i) && v.isObject(i[1])) {
                        if (a > r) return d("maxRequest ", r, "times achieved"), void o.call(this, e);
                        var u = i[0], s = i[1], c = ["getStorageSync:fail"];
                        if (l.inArray(s.msg, c)) return o.call(this, e);
                        try {
                            y.http(u, s, function (e, t) {
                                v.isObject(e) ? d("send err log ok, err data: ", e, t) : !1 === e && (d("err occurred", t), ++a)
                            })
                        } catch (e) {
                            d("onError", e)
                        }
                    }
                    return o.call(this, e)
                }
                return n.call(this, e, t), o.call(this, e)
            }
        }
    }

    var l = function () {
        function e(e, t) {
            return !!p.isArray(t) && t.indexOf(e) !== -1
        }

        function t(e, t) {
            return t = t || {}, p.shouldBe("object", e), p.shouldBe("object", t), i(t, function (t, n) {
                e[t] = n
            }), e
        }

        function n(e) {
            return p.isObject(e) ? 0 === Object.getOwnPropertyNames(e).length : p.isArray(e) ? 0 === e.length : !e
        }

        var o = Object.prototype.toString, r = Object.prototype.hasOwnProperty, a = Array.prototype.slice, p = function () {
            function t(t, o) {
                var r = ["function", "object", "array", "string", "number", "boolean"];
                if (!e(t, r)) throw Error("unknown type: " + t);
                var p = a.call(arguments), i = "is" + t[0].toUpperCase() + t.substring(1);
                if (n[i]) {
                    if (!n[i].call(null, o)) throw Error("argument#" + p.indexOf(o) + " should be " + t + ", " + typeof o + " given");
                    return !0
                }
                throw Error("Unregistered function: " + i)
            }

            var n = {
                isFunction: function (e) {
                    return "[object Function]" === o.call(e)
                }, isObject: function (e) {
                    return "[object Object]" === o.call(e)
                }, isArray: function (e) {
                    return "[object Array]" === o.call(e)
                }, isString: function (e) {
                    return "[object String]" === o.call(e)
                }, isNumber: function (e) {
                    return "[object Number]" === o.call(e)
                }, isBoolean: function (e) {
                    return "[object Boolean]" === o.call(e)
                }
            };
            return n.shouldBe = t, n
        } (), i = function (e, t) {
            if (p.isObject(e)) for (var n in e) r.call(e, n) && t.call(null, n, e[n]); else if (p.isArray(e)) for (var o = 0, a = e.length; o < a; o++)t.call(null, e[o], o)
        };
        return { assert: p, inArray: e, map: i, extendObj: t, isEmpty: n }
    } ()
    var g = function () {
        function e() {
            var e = t("hotAppDebug"), n = Array.prototype.slice.call(arguments);
            n.unshift("[ZM-DEBUG]: "), e && console && console.log.apply(this, n)
        }

        function t(e) {
            return !!l.inArray(e, k) && b[e]
        }

        function n(e, t) {
            return !!l.inArray(e, k) && (b[e] = t, !0)
        }

        function o() {
            return b
        }

        function r(e) {
            if (l.assert.isString(e)) {
                if (!t(e)) throw Error("invalid key: " + e)
            } else l.assert.isArray(e) && l.map(e, function (e) {
                if (!t(e)) throw Error("invalid key: " + e)
            });
            return !0
        }

        function a() {
            return t("hotAppKey") || ""
        }

        function p() {
            n("hotAppUUID", ""), wx.clearStorageSync()
        }

        function i() {
            var e = "userInfo", n = t(e);
            return n
        }

        function u() {
            return t("hotAppHost") || ""
        }

        function s() {
            return wx.getSystemInfoSync() || {}
        }

        function c(e) {
            return n("hotAppKey", e)
        }

        function f() {
            var o = a();
            if (!o) return e("hotappkey is empty"), "";
            var r = t("hotAppUUID");
            if (r) return r;
            var p = t("hotAppUUIDCache");
            return p ? (r = wx.getStorageSync(p), r || (r = A(), n("hotAppUUID", r), wx.setStorageSync(p, r)), r) : (e("hotAppUUIDCache is empty"), "")
        }

        function h() {
            return t("hotAppVersion") || ""
        }

        function g() {
            return !!t("hotAppDebug")
        }

        function y(e) {
            return e = e || !1, n("hotAppDebug", e)
        }

        function d() {
            var e = t("hotAppOpenIdCache");
            return wx.getStorageSync(e)
        }

        function v(n) {
            var o = t("hotAppOpenIdCache");
            return o ? wx.setStorageSync(o, n) : (e("hotAppOpenIdCache is empty"), !1)
        }

        function A(e) {
            function t() {
                function e() {
                    return Math.floor(65536 * (1 + Math.random())).toString(16).substring(1)
                }

                for (var t = "", n = 0; n < 8; n++)t += e();
                return t
            }

            return e = e || {}, "uuid_" + +new Date + t()
        }

        function I() {
            var e = "fakeOpenId", t = wx.getStorageSync(e);
            if (t) return t;
            var n = A();
            return wx.setStorageSync(e, n), n
        }

        function m() {
            var e = d();
            return e ? e : I()
        }

        function w(e) {
            return e = e || "", e + "_" + m()
        }

        function D(e) {
            e = e || "";
            var t = Date.parse(new Date);
            return e + "_" + m() + "_" + 1e3 * t
        }

        function S(e, t) {
            var n = d();
            if (!n) return "function" == typeof t && t(!1);
            var o = e.replace("_" + I() + "_", "_" + d() + "_");
            return "function" == typeof t && t(o)
        }

        var O = {
            hotAppHost: "https://wxapi.hotapp.cn",
            hotAppUUID: "",
            userInfo: {},
            hotAppVersion: "2.1.0",
            hotAppUUIDCache: "hotAppUUID",
            hotAppEventCache: "hotAppEvent",
            hotAppOpenIdCache: "hotAppOpenId",
            uploadType: 0,
            debugarr: [],
            hotAppDebug: !1
        }
        var U = require("./config.js")
        var b = l.extendObj(O, U)
        var x = l.assert;
        x.shouldBe("object", b);
        var k = Object.getOwnPropertyNames(b);
        return {
            getAll: o,
            get: t,
            set: n,
            validKey: r,
            getHotAppKey: a,
            clearData: p,
            getUserInfo: i,
            getSystemInfo: s,
            setEventUploadType: c,
            getHotAppUUID: f,
            getHotAppHost: u,
            getVersion: h,
            setDebug: y,
            isDebug: g,
            getOpenID: d,
            setOpenID: v,
            genKeyFromUser: A,
            getLocalKey: I,
            getFakeOpenID: m,
            getPrefix: w,
            genPrimaryKey: D,
            replaceOpenIdKey: S,
            log: e
        }
    } ()
    var y = function () {
        function e(e, t, n) {
            wx.getNetworkType({
                success: function (o) {
                    var r = o.networkType;
                    return "none" != r && (t.sdkVersion || (t.sdkVersion = g.getVersion()), void wx.request({
                        url: e,
                        data: t,
                        method: "POST",
                        header: { "content-type": "application/json" },
                        success: function (e) {
                            return "function" == typeof n && n(e.data)
                        },
                        fail: function (e) {
                            return "function" == typeof n && n(!1, e)
                        }
                    }))
                }
            })
        }

        function t(e) {
            return 0 == e.useProxy ? void wx.request({
                url: e.url,
                data: e.data,
                header: e.header,
                method: e.method,
                success: function (t) {
                    e.success(t)
                },
                fail: function (t) {
                    e.fail(t)
                },
                complete: function (t) {
                    e.complete(t)
                }
            }) : void (p ? wx.request({
                url: i + "/proxy/?appkey=" + p + "&url=" + e.url,
                data: e.data,
                header: e.header,
                method: e.method,
                success: function (t) {
                    e.success(t)
                },
                fail: function (t) {
                    e.fail(t)
                },
                complete: function (t) {
                    e.complete(t)
                }
            }) : a("hotappkey is empty"))
        }

        function n(t, n) {
            var o = i + "/api/get", r = { appkey: p, key: t };
            e(o, r, n)
        }

        function o(t, n, o) {
            var r = i + "/api/post", a = { appkey: p, key: t, value: n };
            e(r, a, o)
        }

        function r(t, n) {
            var o = i + "/api/delete", r = { appkey: p, key: t };
            e(o, r, n)
        }

        var a = g.log, p = g.getHotAppKey(), i = g.getHotAppHost();
        return { http: e, get: n, post: o, del: r, request: t }
    } ()
    var d = g.log
    var v = l.assert
    var A = g.getHotAppKey()
    var I = g.getHotAppHost()
    var m = App
    App = function (t) {
        var n = { lastShow: 0, lastHide: 0, lastTTL: 0 }, o = g.getHotAppHost();
        h(t, "onLaunch", function () {
            d("app launching"), r()
        }), h(t, "onShow", function () {
            d("app showing"), n.lastShow = +new Date
        }), h(t, "onHide", function () {
            d("app showing"), n.lastHide = +new Date;
            var e = parseInt((n.lastHide - n.lastShow) / 1e3);
            if (e > 0) {
                d("app ttl, ", e);
                var t = o + "/data/wechat/time", r = {
                    time: e,
                    hotAppKey: g.getHotAppKey(),
                    hotAppUUID: g.getHotAppUUID(),
                    openId: g.getOpenID()
                };
                d("send app ttl request ", r), y.http(t, r)
            }
        }), h(t, "onError", function (t) {
            return e(t, g.get("appVer") || "0.1.0")
        }), m(t)
    };
    var w = Page;
    Page = function (e) {
        h(e, "onReady", function () {
        }), h(e, "onLoad", function () {
            var e = arguments[0];
            c(this, e)
        }), h(e, "onUnload", function () {
        }), h(e, "onShow", function () {
            d("page show")
        }), h(e, "onHide", function () {
            d("page hide")
        }), "onShareAppMessage" in e && h(e, "onShareAppMessage", function (e) {
            var t = this.__route__;
            return d("page onShareAppMessage: " + t), e = e || {
                title: "ZM-title",
                desc: "ZM-desc",
                path: "/" === t[0] ? t : "/" + t
            }, f(this, e)
        }), w(e)
    }, module.exports = {
        init: r,
        onEvent: p,
        onError: e,
        onLoad: c,
        onShare: f,
        setEventUploadType: g.setEventUploadType,
        clearData: g.clearData,
        wxlogin: n,
        getFakeOpenID: g.getFakeOpenID,
        getOpenID: g.getOpenID,
        getPrefix: g.getPrefix,
        genPrimaryKey: g.genPrimaryKey,
        replaceOpenIdKey: g.replaceOpenIdKey,
        searchkey: i,
        get: y.get,
        post: y.post,
        del: y.del,
        request: y.request,
        getVersion: g.getVersion,
        setDebug: g.setDebug,
        feedback: u,
        uploadFeedbackImage: s,
        log: d
    }
} ();
