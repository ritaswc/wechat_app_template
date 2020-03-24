if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        list: [],
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) { 
        getApp().page.onLoad(this, options);
        this.setData({
            status: options.status || 0,
        });
        this.loadData(options);
    },

    loadData: function (options) {
        var self = this;
        getApp().core.showLoading({
            title: "加载中",
        });
        getApp().request({
            url: getApp().api.coupon.index,
            data: {
                status: self.data.status,
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        list: res.data.list,
                    });
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },
    goodsList: function (e) {
        var goods_id = e.currentTarget.dataset.goods_id;
        var id = e.currentTarget.dataset.id;
        var list = this.data.list;
        for (var i in list) {
            if (parseInt(list[i].user_coupon_id) === parseInt(id)) {
                // 指定商品的优惠券才能跳转
                if (list[i].appoint_type == 2 && list[i].goods.length > 0) {
                    getApp().core.navigateTo({
                        url: '/pages/list/list?goods_id=' + goods_id
                    })
                }
                return
            }
        }
    },

    xia:function(e){
        var index = e.target.dataset.index;
        this.setData({
            check: index,
        });
    },
    shou: function () {
        this.setData({
            check: -1,
        });
    },
    
});