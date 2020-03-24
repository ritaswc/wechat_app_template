var app = getApp(), api = app.api, is_loading_more = !1, is_no_more = !1;

Page({
    data: {
        page: 1,
        video_list: [],
        url: "",
        hide: "hide",
        show: !1,
        animationData: {}
    },
    onLoad: function(o) {
        app.page.onLoad(this, o);
        this.loadMoreGoodsList(), is_no_more = is_loading_more = !1;
    },
    onReady: function() {},
    onShow: function() {
        app.page.onShow(this);
    },
    onHide: function() {
        app.page.onHide(this);
    },
    onUnload: function() {
        app.page.onUnload(this);
    },
    onPullDownRefresh: function() {},
    loadMoreGoodsList: function() {
        var t = this;
        if (!is_loading_more) {
            t.setData({
                show_loading_bar: !0
            }), is_loading_more = !0;
            var i = t.data.page;
            app.request({
                url: api.default.video_list,
                data: {
                    page: i
                },
                success: function(o) {
                    0 == o.data.list.length && (is_no_more = !0);
                    var a = t.data.video_list.concat(o.data.list);
                    t.setData({
                        video_list: a,
                        page: i + 1
                    });
                },
                complete: function() {
                    is_loading_more = !1, t.setData({
                        show_loading_bar: !1
                    });
                }
            });
        }
    },
    play: function(o) {
        var a = o.currentTarget.dataset.index;
        getApp().core.createVideoContext("video_" + this.data.show_video).pause(), this.setData({
            show_video: a,
            show: !0
        });
    },
    onReachBottom: function() {
        is_no_more || this.loadMoreGoodsList();
    },
    more: function(o) {
        var a = this, t = o.target.dataset.index, i = a.data.video_list, e = getApp().core.createAnimation({
            duration: 1e3,
            timingFunction: "ease"
        });
        this.animation = e, -1 != i[t].show ? (e.rotate(0).step(), i[t].show = -1) : (e.rotate(0).step(), 
        i[t].show = 0), a.setData({
            video_list: i,
            animationData: this.animation.export()
        });
    }
});