Page({
    data: {
        url: ""
    },
    onLoad: function(e) {
        if (getApp().page.onLoad(this, e), getApp().core.canIUse("web-view")) {
            var o = decodeURIComponent(e.url), n = o.split("?"), t = n[0], a = n[1] ? n[1].split("&") : "", i = this;
            if (a && i.data.__user_info) {
                for (var p in o = t, a) "visiter_id=" == a[p] && (a[p] += i.data.__user_info.access_token), 
                "visiter_name=" == a[p] && (a[p] += encodeURIComponent(i.data.__user_info.nickname)), 
                "avatar=" == a[p] && (a[p] += encodeURIComponent(i.data.__user_info.avatar_url));
                var r = getCurrentPages();
                if (2 < r.length) {
                    var s = r[r.length - 2], c = "", g = "";
                    s.data.goods && (g = {
                        pid: (c = s.data.goods).id,
                        title: c.name,
                        img: c.cover_pic,
                        info: "",
                        price: "￥" + c.price,
                        url: ""
                    }), "pages/integral-mall/goods-info/index" == s.route && (g.price = c.integral + "积分 + ￥" + c.price), 
                    "step/goods/goods" == s.route && (g.price = c.price), g && a.push("product=" + encodeURIComponent(JSON.stringify(g)));
                }
                o = t + "?" + a.join("&"), console.log(o);
            }
            this.setData({
                url: o
            });
        } else getApp().core.showModal({
            title: "提示",
            content: "您的版本过低，无法打开本页面，请升级至最新版。",
            showCancel: !1,
            success: function(e) {
                e.confirm && getApp().core.navigateBack({
                    delta: 1
                });
            }
        });
    },
    onReady: function(e) {
        getApp().page.onReady(this);
    },
    onShow: function(e) {
        getApp().page.onShow(this);
    },
    onHide: function(e) {
        getApp().page.onHide(this);
    },
    onUnload: function(e) {
        getApp().page.onUnload(this);
    },
    onShareAppMessage: function(e) {
        return getApp().page.onShareAppMessage(this), {
            path: "pages/web/web?url=" + encodeURIComponent(e.webViewUrl)
        };
    }
});