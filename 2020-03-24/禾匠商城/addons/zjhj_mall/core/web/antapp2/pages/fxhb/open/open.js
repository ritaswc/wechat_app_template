if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        page_img: {
            bg: getApp().webRoot + '/statics/images/fxhb/bg.png',
            close: getApp().webRoot + '/statics/images/fxhb/close.png',
            hongbao_bg: getApp().webRoot + '/statics/images/fxhb/hongbao_bg.png',
            open_hongbao_btn: getApp().webRoot + '/statics/images/fxhb/open_hongbao_btn.png',
        },
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        var self = this;
        getApp().page.onLoad(this, options);

        getApp().core.showLoading({title: '加载中', mask: true});
        getApp().request({
            url: getApp().api.fxhb.open,
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    if (res.data.hongbao_id) {
                        getApp().core.redirectTo({
                            url: '/pages/fxhb/detail/detail?id=' + res.data.hongbao_id,
                        });
                    } else {
                        self.setData(res.data);
                    }
                }
                if (res.code == 1) {
                    getApp().core.showModal({
                        content: res.msg,
                        showCancel: false,
                        success: function (res) {
                            if (res.confirm) {
                                getApp().core.redirectTo({
                                    url: '/pages/index/index'
                                });
                            }
                        }
                    });
                }
            }
        });
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {
        getApp().page.onReady(this);
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);
    },

    showRule: function () {
        this.setData({
            showRule: true,
        });
    },

    closeRule: function () {
        this.setData({
            showRule: false,
        });
    },

    openHongbao: function (e) {
        getApp().core.showLoading({title: '抢红包中', mask: true});
        getApp().request({
            url: getApp().api.fxhb.open_submit,
            method: 'post',
            data: {
                form_id: e.detail.formId,
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.redirectTo({
                        url: '/pages/fxhb/detail/detail?id=' + res.data.hongbao_id,
                    });
                } else {
                    getApp().core.hideLoading();
                    getApp().core.showModal({
                        content: res.msg,
                        showCancel: false,
                    });
                }
            }
        });
    },
});