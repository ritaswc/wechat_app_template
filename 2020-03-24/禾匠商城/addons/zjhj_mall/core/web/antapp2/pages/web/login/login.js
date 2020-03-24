if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {},

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) { getApp().page.onLoad(this, options);
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function (options) { getApp().page.onReady(this);
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function (options) { getApp().page.onShow(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function (options) { getApp().page.onHide(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function (options) { getApp().page.onUnload(this);
    },

    loginSubmit: function () {
        var self = this;
        var token = self.options.scene || false;
        if(typeof my !== 'undefined'){
            if (getApp().query !== null) {
                var query = getApp().query;
                getApp().query = null;
                token = query.token;
            }
        }
        if (!token) {
            getApp().core.showModal({
                title: '提示',
                content: '无效的Token，请刷新页面后重新扫码登录',
                showCancel: false,
                success: function (e) {
                    if (e.confirm) {
                        getApp().core.redirectTo({
                            url: '/pages/index/index',
                        });
                        //getApp().core.navigateBack({delta: 1});
                    }
                }
            });
            return false;
        }
        getApp().core.showLoading({
            title: '正在处理',
            mask: true,
        });
        getApp().request({
            url: getApp().api.user.web_login + "&token=" + token,
            success: function (res) {
                getApp().core.hideLoading();
                getApp().core.showModal({
                    title: '提示',
                    content: res.msg,
                    showCancel: false,
                    success: function (e) {
                        if (e.confirm) {
                            getApp().core.redirectTo({
                                url: '/pages/index/index',
                            });
                            //getApp().core.navigateBack({delta: 1});
                        }
                    }
                });
            }
        });
    },
});
