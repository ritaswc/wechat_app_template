Page({
    data: {
        address_list: []
    },
    onLoad: function(e) {
        getApp().page.onLoad(this, e);
    },
    onShow: function() {
        getApp().page.onShow(this);
        var t = this;
        getApp().core.showNavigationBarLoading(), getApp().request({
            url: getApp().api.user.address_list,
            success: function(e) {
                getApp().core.hideNavigationBarLoading(), 0 == e.code && t.setData({
                    address_list: e.data.list
                }), t.setData({
                    show_no_data_tip: 0 == t.data.address_list.length
                });
            }
        });
    },
    setDefaultAddress: function(e) {
        var a = this, d = e.currentTarget.dataset.index, t = a.data.address_list[d];
        getApp().core.showLoading({
            title: "正在保存",
            mask: !0
        }), getApp().request({
            url: getApp().api.user.address_set_default,
            data: {
                address_id: t.id
            },
            success: function(e) {
                if (getApp().core.hideLoading(), 0 == e.code) {
                    var t = a.data.address_list;
                    for (var s in t) t[s].is_default = s == d ? 1 : 0;
                    a.setData({
                        address_list: t
                    });
                }
            }
        });
    },
    deleteAddress: function(e) {
        var t = e.currentTarget.dataset.id;
        e.currentTarget.dataset.index;
        getApp().core.showModal({
            title: "提示",
            content: "确认删除改收货地址？",
            success: function(e) {
                e.confirm && (getApp().core.showLoading({
                    title: "正在删除",
                    mask: !0
                }), getApp().request({
                    url: getApp().api.user.address_delete,
                    data: {
                        address_id: t
                    },
                    success: function(e) {
                        0 == e.code && getApp().core.redirectTo({
                            url: "/pages/address/address"
                        }), 1 == e.code && (getApp().core.hideLoading(), getApp().core.showToast({
                            title: e.msg
                        }));
                    }
                }));
            }
        });
    }
});