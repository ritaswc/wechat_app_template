if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        user: {},
        is_bind: '',// is_bind 1.已绑定|2.未绑定
        app: {}
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) { getApp().page.onLoad(this, options);
        this.checkBind();
        var user = getApp().core.getStorageSync(getApp().const.USER_INFO);
        this.setData({
            user: user
        })
    },

    checkBind: function () {
        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        })
        getApp().request({
            url: getApp().api.user.check_bind,
            success: function (res) {
                getApp().core.hideLoading()
                if (res.code === 0) {
                    self.setData({
                        is_bind: res.data.is_bind,
                        app: res.data.app
                    })
                }
            }
        })
    },

    getUserInfo: function (res) {
        getApp().core.showLoading({
            title: '加载中',
        })
        var self = this
        getApp().core.login({
            success: function (login_res) {
                var code = login_res.code;
                getApp().request({
                    url: getApp().api.passport.login,
                    method: 'POST',
                    data: {
                        code: code,
                        user_info: res.detail.rawData,
                        encrypted_data: res.detail.encryptedData,
                        iv: res.detail.iv,
                        signature: res.detail.signature
                    },
                    success: function (res) {
                        getApp().core.hideLoading()
                        if (res.code === 0) {
                            getApp().core.showToast({
                                title: '登录成功,请稍等...',
                                icon: 'none'
                            })
                            self.bind()
                        } else {
                            getApp().core.showToast({
                                title: '服务器出错，请再次点击绑定',
                                icon: 'none'
                            })
                        }
                    }

                })
            }
        })
    },

    bind: function () {
        getApp().request({
            url: getApp().api.user.authorization_bind,
            data: {},
            success: function (res) {
                if (res.code === 0) {
                    var bind_url = encodeURIComponent(res.data.bind_url);
                    getApp().core.redirectTo({
                        url: '/pages/web/web?url=' + bind_url,
                    })
                } else {
                    getApp().core.showToast({
                        title: res.msg,
                        icon: 'none'
                    })
                }
            }
        })
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

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function (options) { getApp().page.onPullDownRefresh(this);

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function (options) { getApp().page.onReachBottom(this);

    },
})