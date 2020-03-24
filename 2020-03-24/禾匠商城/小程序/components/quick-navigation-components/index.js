Component({
    properties: {
        setnavi: {
            type: "object",
            value: {}
        },
        __device: {
            type: "string",
            value: ""
        },
        home_icon: {
            type: "boolean",
            value: !0
        },
        options: {
            type: "object",
            value: {}
        },
        store: {
            type: "object",
            value: {}
        },
        __platform: {
            type: "string",
            value: "wx"
        },
        __alipay_mp_config: {
            type: "object",
            value: {}
        },
        __user_info: {
            type: "object",
            value: {}
        },
        click_pic: {
            type: "object",
            value: !1
        }
    },
    data: {
        animationPlus: !1,
        animationcollect: !1,
        animationPic: !1,
        animationTranspond: !1,
        animationInput: !1,
        animationMapPlus: !1,
        quick_icon: !1
    },
    options: {
        addGlobalClass: !0
    },
    methods: {
        setNavi: function() {
            var t = this;
            if (-1 != [ "pages/index/index", "pages/book/details/details", "pages/pt/details/details", "pages/goods/goods" ].indexOf(this.getCurrentPageUrl()) && t.setData({
                home_icon: !0
            }), "undefined" == typeof my) var e = t.data.store.quick_navigation; else e = t.props.store.quick_navigation;
            e.home_img || (e.home_img = "/images/quick-home.png"), t.setData({
                setnavi: e
            });
        },
        getCurrentPageUrl: function() {
            var t = getCurrentPages();
            return t[t.length - 1].route;
        },
        to_dial: function() {
            if ("undefined" == typeof my) var t = this.data.store.contact_tel; else t = this.props.store.contact_tel;
            getApp().core.makePhoneCall({
                phoneNumber: t
            });
        },
        map_power: function() {
            var e = this;
            if ("undefined" == typeof my) var o = e.data.store.option.quick_map; else o = e.props.store.option.quick_map;
            "undefined" == typeof my ? e.map_goto(o) : getApp().core.getSetting({
                success: function(t) {
                    t.authSetting["scope.userLocation"] ? e.map_goto(o) : getApp().getauth({
                        content: "需要获取您的地理位置授权，请到小程序设置中打开授权！",
                        cancel: !1,
                        author: "scope.userLocation",
                        success: function(t) {
                            t.authSetting["scope.userLocation"] && e.map_goto(o);
                        }
                    });
                }
            });
        },
        map_goto: function(t) {
            var e = t.lal.split(",");
            getApp().core.openLocation({
                latitude: parseFloat(e[0]),
                longitude: parseFloat(e[1]),
                name: t.address,
                address: t.address
            });
        },
        cutover: function() {
            var t = this;
            t.setData({
                quick_icon: !t.data.quick_icon
            });
            var e = getApp().core.createAnimation({
                duration: 350,
                timingFunction: "ease-out"
            }), o = getApp().core.createAnimation({
                duration: 350,
                timingFunction: "ease-out"
            }), a = getApp().core.createAnimation({
                duration: 350,
                timingFunction: "ease-out"
            }), i = getApp().core.createAnimation({
                duration: 350,
                timingFunction: "ease-out"
            }), n = getApp().core.createAnimation({
                duration: 350,
                timingFunction: "ease-out"
            }), p = getApp().core.createAnimation({
                duration: 350,
                timingFunction: "ease-out"
            });
            if ("undefined" == typeof my) var s = t.data.store; else s = t.props.store;
            var c = -50;
            t.data.quick_icon ? (s.option && s.option.wxapp && 1 == s.option.wxapp.status && (n.translateY(c).opacity(1).step(), 
            c -= 50), s.show_customer_service && 1 == s.show_customer_service && s.service && (i.translateY(c).opacity(1).step(), 
            c -= 50), s.option && 1 == s.option.web_service_status && (a.translateY(c).opacity(1).step(), 
            c -= 50), 1 == s.dial && s.dial_pic && (o.translateY(c).opacity(1).step(), c -= 50), 
            s.option && 1 == s.option.quick_map.status && (p.translateY(c).opacity(1).step(), 
            c -= 50), e.translateY(c).opacity(1).step()) : (e.opacity(0).step(), a.opacity(0).step(), 
            o.opacity(0).step(), i.opacity(0).step(), n.opacity(0).step(), p.opacity(0).step()), 
            t.setData({
                animationPlus: e.export(),
                animationcollect: a.export(),
                animationPic: o.export(),
                animationTranspond: i.export(),
                animationInput: n.export(),
                animationMapPlus: p.export()
            });
        }
    }
});