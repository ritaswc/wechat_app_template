var WxParse = require("../../wxParse/wxParse.js");

Page({
    data: {
        version: getApp()._version
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var t = this;
        getApp().request({
            url: getApp().api.default.article_detail,
            data: {
                id: e.id
            },
            success: function(e) {
                0 == e.code && (getApp().core.setNavigationBarTitle({
                    title: e.data.title
                }), WxParse.wxParse("content", "html", e.data.content, t)), 1 == e.code && getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1,
                    confirm: function(e) {
                        e.confirm && getApp().core.navigateBack();
                    }
                });
            }
        });
    }
});