module.exports = {
    init: function(t) {
        var e = this;
        e.currentPage = t, e.setNavi(), void 0 === t.cutover && (t.cutover = function(t) {
            e.cutover(t);
        }), void 0 === t.to_dial && (t.to_dial = function(t) {
            e.to_dial(t);
        }), void 0 === t.map_goto && (t.map_goto = function(t) {
            e.map_goto(t);
        }), void 0 === t.map_power && (t.map_power = function(t) {
            e.map_power(t);
        });
    },
    setNavi: function() {
        var o = this.currentPage;
        -1 != [ "pages/index/index", "pages/book/details/details", "pages/pt/details/details", "pages/goods/goods" ].indexOf(this.getCurrentPageUrl()) && o.setData({
            home_icon: !0
        }), getApp().getConfig(function(t) {
            var e = t.store.quick_navigation;
            e.home_img || (e.home_img = "/images/quick-home.png"), o.setData({
                setnavi: e
            });
        });
    },
    getCurrentPageUrl: function() {
        var t = getCurrentPages();
        return t[t.length - 1].route;
    },
    to_dial: function() {
        getApp().getConfig(function(t) {
            var e = t.store.contact_tel;
            console.log(e), getApp().core.makePhoneCall({
                phoneNumber: e
            });
        });
    },
    map_power: function() {
        var o = this.currentPage;
        getApp().getConfig(function(t) {
            var e = t.store.option.quick_map;
            void 0 !== e ? o.map_goto(e) : getApp().core.getSetting({
                success: function(t) {
                    t.authSetting["scope.userLocation"] ? o.map_goto(e) : getApp().getauth({
                        content: "需要获取您的地理位置授权，请到小程序设置中打开授权！",
                        cancel: !1,
                        author: "scope.userLocation",
                        success: function(t) {
                            t.authSetting["scope.userLocation"] && o.map_goto(e);
                        }
                    });
                }
            });
        });
    },
    map_goto: function(t) {
        this.currentPage;
        var e = t.lal.split(",");
        getApp().core.openLocation({
            latitude: parseFloat(e[0]),
            longitude: parseFloat(e[1]),
            name: t.address,
            address: t.address
        });
    },
    cutover: function() {
        var a = this.currentPage;
        a.setData({
            quick_icon: !a.data.quick_icon
        });
        var i = getApp().core.createAnimation({
            duration: 350,
            timingFunction: "ease-out"
        }), n = getApp().core.createAnimation({
            duration: 350,
            timingFunction: "ease-out"
        }), p = getApp().core.createAnimation({
            duration: 350,
            timingFunction: "ease-out"
        }), c = getApp().core.createAnimation({
            duration: 350,
            timingFunction: "ease-out"
        }), r = getApp().core.createAnimation({
            duration: 350,
            timingFunction: "ease-out"
        }), s = getApp().core.createAnimation({
            duration: 350,
            timingFunction: "ease-out"
        });
        getApp().getConfig(function(t) {
            var e = a.data.store, o = -50;
            a.data.quick_icon ? (e.option && e.option.wxapp && 1 == e.option.wxapp.status && (r.translateY(o).opacity(1).step(), 
            o -= 50), e.show_customer_service && 1 == e.show_customer_service && e.service && (c.translateY(o).opacity(1).step(), 
            o -= 50), e.option && 1 == e.option.web_service_status && (p.translateY(o).opacity(1).step(), 
            o -= 50), 1 == e.dial && e.dial_pic && (n.translateY(o).opacity(1).step(), o -= 50), 
            e.option && 1 == e.option.quick_map.status && (s.translateY(o).opacity(1).step(), 
            o -= 50), i.translateY(o).opacity(1).step()) : (i.opacity(0).step(), p.opacity(0).step(), 
            n.opacity(0).step(), c.opacity(0).step(), r.opacity(0).step(), s.opacity(0).step()), 
            a.setData({
                animationPlus: i.export(),
                animationcollect: p.export(),
                animationPic: n.export(),
                animationTranspond: c.export(),
                animationInput: r.export(),
                animationMapPlus: s.export()
            });
        });
    }
};