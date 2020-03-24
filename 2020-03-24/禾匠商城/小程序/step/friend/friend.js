Page({
    data: {
        invite_list: [],
        info: [],
        page: 2,
        loading: !1,
        length: 0
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var n = this;
        getApp().core.showLoading({
            title: "数据加载中...",
            mask: !0
        }), getApp().request({
            url: getApp().api.step.invite_detail,
            data: {
                page: 1
            },
            success: function(t) {
                getApp().core.hideLoading();
                var i = t.data.info, a = t.data.invite_list, e = a.length;
                n.setData({
                    info: i,
                    length: e,
                    invite_list: a
                });
            }
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {
        var a = this, e = a.data.over, n = a.data.invite_list;
        if (!e) {
            var o = this.data.page;
            this.setData({
                loading: !0
            }), getApp().request({
                url: getApp().api.step.invite_detail,
                data: {
                    page: o
                },
                success: function(t) {
                    for (var i = 0; i < t.data.invite_list.length; i++) n.push(t.data.invite_list[i]);
                    t.data.invite_list.length < 15 && (e = !0), a.setData({
                        page: o + 1,
                        over: e,
                        loading: !1,
                        invite_list: n
                    });
                }
            });
        }
    },
    onShareAppMessage: function() {}
});