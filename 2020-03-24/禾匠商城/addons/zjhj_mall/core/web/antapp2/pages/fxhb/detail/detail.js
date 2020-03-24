if (typeof wx === 'undefined') var wx = getApp().core;
var timer = null;
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
            wechat: getApp().webRoot + '/statics/images/fxhb/wechat.png',
            coupon: getApp().webRoot + '/statics/images/fxhb/coupon.png',
            pointer_r: getApp().webRoot + '/statics/images/fxhb/pointer_r.png',
            best_icon: getApp().webRoot + '/statics/images/fxhb/best_icon.png',
            more_l: getApp().webRoot + '/statics/images/fxhb/more_l.png',
            more_r: getApp().webRoot + '/statics/images/fxhb/more_r.png',
            cry: getApp().webRoot + '/statics/images/fxhb/cry.png',
            share_modal_bg: getApp().webRoot + '/statics/images/fxhb/share_modal_bg.png',
        },
        goods_list: null,
        rest_time_str: '--:--:--',
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        var self = this;
        getApp().page.onLoad(this, options);

        var id = options.id;
        getApp().core.showLoading({title: '加载中', mask: true});
        getApp().request({
            url: getApp().api.fxhb.detail,
            data: {
                id: id,
            },
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 1) {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function (e) {
                            if (e.confirm) {
                                if (res.game_open == 1) {
                                    getApp().core.redirectTo({
                                        url: '/pages/fxhb/open/open',
                                    });
                                } else {
                                    getApp().core.redirectTo({
                                        url: '/pages/index/index',
                                    });
                                }
                            }
                        }
                    });
                    return;
                }
                if (res.code == 0) {
                    self.setData({
                        rule: res.data.rule,
                        share_pic: res.data.share_pic,
                        share_title: res.data.share_title,
                        coupon_total_money: res.data.coupon_total_money,
                        rest_user_num: res.data.rest_user_num,
                        rest_time: res.data.rest_time,
                        hongbao: res.data.hongbao,
                        hongbao_list: res.data.hongbao_list,
                        is_my_hongbao: res.data.is_my_hongbao,
                        my_coupon: res.data.my_coupon,
                        goods_list: res.data.goods_list,
                    });
                    self.setRestTimeStr();
                }
                self.showShareModal();
            },
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
        var self = this;
        self.setData({
            showRule: true,
        });
    },

    closeRule: function () {
        var self = this;
        self.setData({
            showRule: false,
        });
    },

    //打开分享提示框
    showShareModal: function () {
        var self = this;
        self.setData({
            showShareModal: true,
        });
    },

    //关闭分享提示框
    closeShareModal: function () {
        var self = this;
        self.setData({
            showShareModal: false,
        });
    },

    //倒计时器
    setRestTimeStr: function () {
        var self = this;
        var rest_time = self.data.rest_time || false;
        if (rest_time === false || rest_time === null)
            return;
        rest_time = parseInt(rest_time);
        if (rest_time <= 0) {
            self.setData({
                rest_time_str: '00:00:00',
            });
            return;
        }
        if (timer)
            clearInterval(timer);
        timer = setInterval(function () {
            rest_time = self.data.rest_time;
            if (rest_time <= 0) {
                clearInterval(timer);
                self.setData({
                    rest_time_str: '00:00:00',
                });
                return;
            }
            var h = parseInt(rest_time / 3600);
            var m = parseInt((rest_time % 3600) / 60);
            var s = parseInt((rest_time % 3600) % 60);
            self.setData({
                rest_time: rest_time - 1,
                rest_time_str: (h < 10 ? ('0' + h) : h) + ':' + (m < 10 ? ('0' + m) : m) + ':' + (s < 10 ? ('0' + s) : s),
            });
        }, 1000);
    },

    //一起拆红包操作
    detailSubmit: function (e) {
        var self = this;
        getApp().core.showLoading({
            mask: true,
        });
        getApp().request({
            url: getApp().api.fxhb.detail_submit,
            method: 'post',
            data: {
                id: self.data.hongbao.id,
                form_id: e.detail.formId,
            },
            success: function (res) {
                if (res.code == 1) {
                    getApp().core.hideLoading();
                    getApp().core.showToast({
                        title: res.msg,
                        complete: function () {
                            if (res.game_open == 0) {
                                getApp().core.redirectTo({
                                    url: '/pages/index/index',
                                });
                            }
                        }
                    });
                    return;
                }
                if (res.code == 0) {
                    getApp().core.hideLoading();
                    getApp().core.showToast({
                        title: res.msg,
                        complete: function () {
                            if (res.reload == 1) {
                                getApp().core.redirectTo({
                                    url: '/pages/fxhb/detail/detail?id=' + self.options.id,
                                });
                            }
                        }
                    });
                }
            }
        });
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        var self = this;
        getApp().page.onShareAppMessage(this);
        var user = self.data.__user_info;
        var data = {
            path: '/pages/fxhb/detail/detail?id=' + self.data.hongbao.id + (user ? ('&user_id=' + user.id) : ''),
            title: self.data.share_title || null,
            imageUrl: self.data.share_pic || null,
            complete: function (e) {
                self.closeShareModal();
            },
        };
        return data;
    }
});