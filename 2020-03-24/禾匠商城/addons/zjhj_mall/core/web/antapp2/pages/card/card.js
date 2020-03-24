if (typeof wx === 'undefined') var wx = getApp().core;
var is_loading = false;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        page: 1,
        show_qrcode: false,
        status: 1
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);

        if (options.status) {
            this.setData({
                status: options.status
            });
        }
        this.loadData();
    },

    loadData: function () {
        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        })
        getApp().request({
            url: getApp().api.user.card,
            data: {
                page: 1,
                status: self.data.status
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData(res.data);
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },


    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {
        getApp().page.onReachBottom(this);

        if (this.data.page == this.data.page_count) {
            return;
        }
        this.loadMore();
    },

    loadMore: function () {
        var self = this;
        if (is_loading) {
            return;
        }
        is_loading = true;
        getApp().core.showLoading({
            title: '加载中',
        });
        var p = self.data.page;
        getApp().request({
            url: getApp().api.user.card,
            data: {
                page: (p + 1),
                status: self.data.status
            },
            success: function (res) {
                if (res.code == 0) {
                    var list = self.data.list.concat(res.data.list);
                    self.setData({
                        list: list,
                        page_count: res.data.page_count,
                        row_count: res.data.row_count,
                        page: p + 1
                    });
                }
            },
            complete: function () {
                is_loading = false;
                getApp().core.hideLoading();
            }
        });
    },

    getQrcode: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var list = self.data.list;
        var card = list[index];
        getApp().core.showLoading({
            title: '加载中',
        });
        getApp().request({
            url: getApp().api.user.card_qrcode,
            data: {
                user_card_id: card.id
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        show_qrcode: true,
                        qrcode: res.data.url
                    });
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                    })
                }
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },
    hide: function () {
        this.setData({
            show_qrcode: false
        });
    },

    goto: function (e) {
        var status = e.currentTarget.dataset.status;
        getApp().core.redirectTo({
            url: '/pages/card/card?status=' + status,
        })
    },

    save: function () {
        var self = this;
        if (getApp().core.saveImageToPhotosAlbum) {
            getApp().core.showLoading({
                title: "正在保存图片",
                mask: false,
            });

            getApp().core.downloadFile({
                url: self.data.qrcode,
                success: function (e) {
                    getApp().core.showLoading({
                        title: "正在保存图片",
                        mask: false,
                    });
                    self.saveImg(e);
                },
                fail: function (e) {
                    getApp().core.showModal({
                        title: '下载失败',
                        content: e.errMsg + ";" + self.data.goods_qrcode,
                        showCancel: false,
                    });
                },
                complete: function (e) {
                    getApp().core.hideLoading();
                }
            });
        } else {
            getApp().core.showModal({
                title: '提示',
                content: '当前版本过低，无法使用该功能，请升级到最新版本后重试。'
            })
        }
    },
    saveImg: function (e) {
        var self = this;
        getApp().core.saveImageToPhotosAlbum({
            filePath: e.tempFilePath,
            success: function () {
                
                getApp().core.showModal({
                    title: '提示',
                    content: '保存成功',
                    showCancel: false,
                });
            },
            fail: function (e) {
                getApp().core.getSetting({
                    success: function (r) {
                        if (!r.authSetting['scope.writePhotosAlbum']) {
                            getApp().getauth({
                                content: "小程序需要授权保存到相册",
                                author:'scope.writePhotosAlbum',
                                success: function (res) {
                                    if (res) {
                                        self.saveImg(e);
                                    }
                                }
                            });
                        }
                    }
                })
            },
            complete: function (e) {
                getApp().core.hideLoading();
            }
        });
    }
})