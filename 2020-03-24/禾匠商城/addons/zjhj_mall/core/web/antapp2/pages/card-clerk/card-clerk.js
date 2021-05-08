if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {

    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);

        var user_info = getApp().getUser();
        this.setData({
            store: getApp().core.getStorageSync(getApp().const.STIRE),
            user_info: user_info
        });
        var user_card_id = "";
        if(typeof my === 'undefined'){
            user_card_id = decodeURIComponent(options.scene);
        }else{
            if (getApp().query !== null) {
                var query = getApp().query;
                getApp().query = null;
                user_card_id = query.user_card_id;
            }
        }
        getApp().core.showModal({
            title: '提示',
            content: '是否核销？',
            success: function (e) {
                if (e.confirm) {
                    getApp().core.showLoading({
                        title: '核销中',
                    })
                    getApp().request({
                        url: getApp().api.user.card_clerk,
                        data: {
                            user_card_id: user_card_id
                        },
                        success: function (res) {
                            getApp().core.showModal({
                                title: '提示',
                                content: res.msg,
                                showCancel: false,
                                success: function (res) {
                                    if (res.confirm) {
                                        getApp().core.redirectTo({
                                            url: '/pages/index/index',
                                        })
                                    }
                                }
                            })
                        },
                        complete: function () {
                            getApp().core.hideLoading();
                        }
                    });
                }
                else if (e.cancel) {
                    getApp().core.redirectTo({
                        url: '/pages/index/index',
                    })
                }
            }
        })
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);
    },
})