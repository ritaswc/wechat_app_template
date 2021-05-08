Page({
    data: {},
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
        var t = this, a = e.user_card_id;
        a && (getApp().core.showLoading({
            title: "加载中"
        }), getApp().request({
            url: getApp().api.user.card_detail,
            data: {
                user_card_id: a
            },
            success: function(e) {
                0 == e.code && (0 === e.data.list.is_use && t.getQrcode(a), t.setData({
                    use: e.data.list.is_use,
                    list: e.data.list
                }));
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        }));
    },
    getQrcode: function(e) {
        var t = this;
        getApp().request({
            url: getApp().api.user.card_qrcode,
            data: {
                user_card_id: e
            },
            success: function(e) {
                0 == e.code ? t.setData({
                    qrcode: e.data.url
                }) : getApp().core.showModal({
                    title: "提示",
                    content: e.msg,
                    showCancel: !1
                });
            }
        });
    },
    goodsQrcodeClick: function(e) {
        var t = e.currentTarget.dataset.src;
        getApp().core.previewImage({
            urls: [ t ]
        });
    }
});