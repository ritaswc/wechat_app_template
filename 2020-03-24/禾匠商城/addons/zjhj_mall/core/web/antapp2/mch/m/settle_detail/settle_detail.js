if (typeof wx === 'undefined') var wx = getApp().core;
// pages/settle_detail/settle_detail.js
Page({

    /**
     * 页面的初始数据
     */
    data: {
        settle_type: '',
        settleList: [],
        page: 1,
        loading: false,
        no_more: false,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options)
        var self = this;
        self.setData({
            settle_type: options.settle_type,
        })

        self.getSettleList();
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

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {
        getApp().page.onHide(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {
        getApp().page.onUnload(this);
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {
        getApp().page.onPullDownRefresh(this);
    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {
        getApp().page.onReachBottom(this);
        var self = this;
        self.getSettleList()
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);
    },

    /**
     * 结算详情列表
     */
    getSettleList: function () {
        var self = this;

        if (self.data.loading) {
            return;
        }
        if (self.data.no_more) {
            return;
        }
        self.setData({
            loading: true,
        });

        var settleType = self.data.settle_type;
        var page = self.data.page;
        getApp().core.showLoading({
            title: '正在加载',
            mask: true,
        });
        getApp().request({
            url: getApp().api.mch.user.settle_log,
            data: {
                settle_type: settleType,
                page: page
            },
            success: function (res) {
                if (res.code == 0) {
                    if (res.data.list.length > 0) {
                        self.setData({
                            settleList: self.data.settleList.concat(res.data.list),
                            page: page + 1
                        });
                    } else {
                        self.setData({
                            no_more: true,
                        });
                    }
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function (e) {
                            if (e.confirm) {
                                getApp().core.navigateBack();
                            }
                        }
                    });
                }
            },
            complete: function () {
                getApp().core.hideLoading();
                self.setData({
                    loading: false,
                });
            }
        });
    }
})