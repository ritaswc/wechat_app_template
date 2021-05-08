module.exports = {
    currentPage: null,
    init: function(e) {
        var t = this;
        void 0 === (t.currentPage = e).onGoodsImageClick && (e.onGoodsImageClick = function(e) {
            t.onGoodsImageClick(e);
        }), void 0 === e.hide && (e.hide = function(e) {
            t.hide(e);
        }), void 0 === e.play && (e.play = function(e) {
            t.play(e);
        }), void 0 === e.close && (e.close = function(e) {
            t.close(e);
        });
    },
    onGoodsImageClick: function(e) {
        var t = this.currentPage, i = [], o = e.currentTarget.dataset.index;
        for (var a in t.data.goods.pic_list) i.push(t.data.goods.pic_list[a]);
        getApp().core.previewImage({
            urls: i,
            current: i[o]
        });
    },
    hide: function(e) {
        var t = this.currentPage;
        0 == e.detail.current ? t.setData({
            img_hide: ""
        }) : t.setData({
            img_hide: "hide"
        });
    },
    play: function(e) {
        var t = this.currentPage, i = e.target.dataset.url;
        t.setData({
            url: i,
            hide: "",
            show: !0
        }), getApp().core.createVideoContext("video").play();
    },
    close: function(e) {
        var t = this.currentPage;
        if ("video" == e.target.id) return !0;
        t.setData({
            hide: "hide",
            show: !1
        }), getApp().core.createVideoContext("video").pause();
    }
};