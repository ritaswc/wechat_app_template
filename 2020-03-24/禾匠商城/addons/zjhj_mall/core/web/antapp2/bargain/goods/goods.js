if (typeof wx === 'undefined') var wx = getApp().core;
// bargain/goods/goods.js
var app = getApp();
var api = getApp().api;
var utils = getApp().helper;
var videoContext = '';
var setIntval = null;
var WxParse = require('../../wxParse/wxParse.js');
var userIntval = null;
var scrollIntval = null;
var is_loading = false;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        hide: "hide",
        time_list: {
            day: 0,
            hour: '00',
            minute: '00',
            second: '00'
        },
        p: 1,
        user_index: 0,
        show_content: false
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        if (typeof my === 'undefined') {
            var scene = decodeURIComponent(options.scene);
            if (typeof scene !== 'undefined') {
                var scene_obj = utils.scene_decode(scene);
                if (scene_obj.gid) {
                    options.goods_id = scene_obj.gid;
                }
            }
        } else {
            if (app.query !== null) {
                var query = app.query;
                app.query = null;
                options.goods_id = query.gid;
            }
        }
        this.getGoods(options.goods_id);
    },

    getGoods: function(goods_id) {
        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        });
        getApp().request({
            url: getApp().api.bargain.goods,
            data: {
                goods_id: goods_id,
                page: 1
            },
            success: function(res) {
                if (res.code == 0) {
                    var detail = res.data.goods.detail;
                    WxParse.wxParse("detail", "html", detail, self);
                    self.setData(res.data);
                    self.setData({
                        reset_time: self.data.goods.reset_time,
                        time_list: self.setTimeList(res.data.goods.reset_time),
                        p: 1,
                        foreshow_time: self.data.goods.foreshow_time,
                        foreshow_time_list: self.setTimeList(self.data.goods.foreshow_time)
                    });
                    self.setTimeOver();
                    if (res.data.bargain_info) {
                        self.getUserTime();
                    }
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function(e) {
                            if (e.confirm) {
                                getApp().core.navigateBack({
                                    delta: -1
                                })
                            }
                        }
                    })
                }
            },
            complete: function(res) {
                getApp().core.hideLoading();
            }
        });
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function() {
        app.page.onReady(this);
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {
        app.page.onShow(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function() {
        app.page.onHide(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function() {
        app.page.onUnload(this);
        clearInterval(setIntval);
        setIntval = null;
        clearInterval(userIntval);
        userIntval = null;
        clearInterval(scrollIntval);
        scrollIntval = null;
    },

    play: function(e) {
        var url = e.target.dataset.url; //获取视频链接
        this.setData({
            url: url,
            hide: '',
            show: true,
        });
        videoContext = getApp().core.createVideoContext('video');
        videoContext.play();
    },

    close: function(e) {
        if (e.target.id == 'video') {
            return true;
        }
        this.setData({
            hide: "hide",
            show: false
        });
        videoContext.pause();
    },

    onGoodsImageClick: function(e) {
        var self = this;
        var urls = [];
        var index = e.currentTarget.dataset.index;
        //console.log(self.data.goods.pic_list);
        for (var i in self.data.goods.pic_list) {
            urls.push(self.data.goods.pic_list[i].pic_url);
        }
        getApp().core.previewImage({
            urls: urls, // 需要预览的图片http链接列表
            current: urls[index],
        });
    },

    hide: function(e) {
        if (e.detail.current == 0) {
            this.setData({
                img_hide: ""
            });
        } else {
            this.setData({
                img_hide: "hide"
            });
        }
    },

    // 设置定时器
    setTimeOver: function() {
        var self = this;

        setIntval = setInterval(function() {
            if (self.data.resset_time <= 0) {
                clearInterval(setIntval);
            }
            var reset_time = self.data.reset_time - 1;
            var time_list = self.setTimeList(reset_time);
            var foreshow_time = self.data.foreshow_time - 1;
            var foreshow_time_list = self.setTimeList(foreshow_time);
            self.setData({
                reset_time: reset_time,
                time_list: time_list,
                foreshow_time: foreshow_time,
                foreshow_time_list: foreshow_time_list
            });
        }, 1000);
    },

    orderSubmit: function() {
        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        })
        getApp().request({
            url: getApp().api.bargain.bargain_submit,
            method: "POST",
            data: {
                goods_id: self.data.goods.id,
            },
            success: function(res) {
                if (res.code == 0) {
                    getApp().core.redirectTo({
                        url: '/bargain/activity/activity?order_id=' + res.data.order_id,
                    })
                } else {
                    self.showToast({
                        title: res.msg
                    });
                }
            },
            complete: function(res) {
                getApp().core.hideLoading();
            }
        });
    },
    buyNow: function() {
        var self = this;
        var mch_list = [];
        var goods_list = [];
        var bargain_info = self.data.bargain_info;
        if (!bargain_info) {
            return;
        }
        goods_list.push({
            bargain_order_id: bargain_info.order_id
        });
        mch_list.push({
            mch_id: 0,
            goods_list: goods_list
        });
        getApp().core.redirectTo({
            url: "/pages/new-order-submit/new-order-submit?mch_list=" + JSON.stringify(mch_list),
        });
    },

    getUserTime: function() {
        var self = this;
        userIntval = setInterval(function() {
            self.loadData()
        }, 1000);
        scrollIntval = setInterval(function() {
            var user_index = self.data.user_index;
            var count = self.data.bargain_info.bargain_info.length;
            if (count - user_index > 3) {
                user_index = user_index + 3;
            } else {
                user_index = 0;
            }
            self.setData({
                user_index: user_index
            });
        }, 3000);
    },

    loadData: function() {
        var self = this;
        var p = self.data.p;
        if (is_loading) {
            return;
        }
        is_loading = true;
        app.request({
            url: api.bargain.goods_user,
            data: {
                page: p + 1,
                goods_id: self.data.goods.id
            },
            success: function(res) {
                if (res.code == 0) {
                    var bargain_info_user = self.data.bargain_info.bargain_info;
                    var bargain_info = res.data.bargain_info
                    if (bargain_info.bargain_info.length == 0) {
                        clearInterval(userIntval);
                        userIntval = null;
                    }
                    bargain_info.bargain_info = bargain_info_user.concat(bargain_info.bargain_info);
                    self.setData({
                        bargain_info: bargain_info,
                        p: p + 1
                    });
                } else {
                    self.showToast({
                        title: res.msg
                    });
                }
            },
            complete: function() {
                is_loading = false;
            }
        });
    },

    contentClose: function() {
        var self = this;
        self.setData({
            show_content: false
        });
    },
    contentOpen: function() {
        var self = this;
        self.setData({
            show_content: true
        });
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var res = {
            path: "/bargain/list/list?goods_id=" + self.data.goods.id + "&user_id=" + self.data.__user_info.id,
            success: function (e) { },
            title: self.data.goods.name,
            imageUrl: self.data.goods.pic_list[0].pic_url,
        };
        return res;
    },


    showShareModal:function(){
        this.setData({
            share_modal_active: "active",
        });
    },

    shareModalClose:function(){
        this.setData({
            share_modal_active:'',
        })
    },

    /**
    *  海报
    */
    getGoodsQrcode: function() {
        var self = this;
        self.setData({
            qrcode_active: "active",
            share_modal_active: "",
        });
        if (self.data.goods_qrcode) return true

        getApp().request({
            url: getApp().api.bargain.qrcode,
            data: {
                goods_id: self.data.goods.id,
            },
            success: function(res) {
                if (res.code == 0) {
                    self.setData({
                        goods_qrcode: res.data.pic_url,
                    });
                }
                if (res.code == 1) {
                    self.goodsQrcodeClose();
                    getApp().core.showModal({
                        title: "提示",
                        content: res.msg,
                        showCancel: false,
                        success: function(res) {
                            if (res.confirm) {
                            }
                        }
                    });
                }
            },
        });
    },  

    qrcodeClick: function(e) {
        var src = e.currentTarget.dataset.src;
        getApp().core.previewImage({
            urls: [src],
        });
    },
    qrcodeClose: function() {
        var self = this; 
        self.setData({
            qrcode_active: "",
        });
    },

    goodsQrcodeClose: function() {
        var self = this;
        self.setData({
            goods_qrcode_active: "",
            no_scroll: false,
        });
    },

    saveQrcode: function() {
        var self = this;
        if (!getApp().core.saveImageToPhotosAlbum) {
            getApp().core.showModal({
                title: '提示',
                content: '当前版本过低，无法使用该功能，请升级到最新版本后重试。',
                showCancel: false,
            });
            return;
        }

        getApp().core.showLoading({
            title: "正在保存图片",
            mask: false,
        });

        getApp().core.downloadFile({
            url: self.data.goods_qrcode,
            success: function(e) {
                getApp().core.showLoading({
                    title: "正在保存图片",
                    mask: false,
                });
                getApp().core.saveImageToPhotosAlbum({
                    filePath: e.tempFilePath,
                    success: function() {
                        getApp().core.showModal({
                            title: '提示',
                            content: '商品海报保存成功',
                            showCancel: false,
                        });
                    },
                    fail: function(e) {
                        getApp().core.showModal({
                            title: '图片保存失败',
                            content: e.errMsg,
                            showCancel: false,
                        });
                    },
                    complete: function(e) {
                        getApp().core.hideLoading();
                    }
                });
            },
            fail: function(e) {
                getApp().core.showModal({
                    title: '图片下载失败',
                    content: e.errMsg + ";" + self.data.goods_qrcode,
                    showCancel: false,
                });
            },
            complete: function(e) {
                getApp().core.hideLoading();
            }
        });
    },

})