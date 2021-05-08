Page({
    data: {
        article_list: []
    },
    onLoad: function(t) {
        getApp().page.onLoad(this, t);
        var a = this;
        getApp().core.showLoading(), getApp().request({
            url: getApp().api.default.article_list,
            data: {
                cat_id: 2
            },
            success: function(t) {
                getApp().core.hideLoading(), a.setData({
                    article_list: t.data.list
                });
            }
        });
    }
});