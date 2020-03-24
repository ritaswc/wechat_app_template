if (typeof wx === 'undefined') var wx = getApp().core;
var utils = require('../../../utils/helper.js');
Page({

    /**
     * 页面的初始数据
     */
    data: {
        // now_date: new Date(),
        is_date_start: true
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        this.getPreview(options);
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function(options) {
        getApp().page.onReady(this);

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function(options) {
        getApp().page.onShow(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function(options) {
        getApp().page.onHide(this);

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function(options) {
        getApp().page.onUnload(this);

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function(options) {
        getApp().page.onPullDownRefresh(this);

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function(options) {
        getApp().page.onReachBottom(this);

    },

    /**
     * 复选
     */
    checkboxChange: function(e) {
        var self = this;
        var pid = e.target.dataset.pid;
        var id = e.target.dataset.id;
        var form_list = self.data.form_list;
        var is_selected = form_list[pid].default[id]['selected'];
        if (is_selected == true) {
            form_list[pid].default[id]['selected'] = false;
        } else {
            form_list[pid].default[id]['selected'] = true;
        }
        self.setData({
            form_list: form_list,
        });
    },
    /**
     * 单选
     */
    radioChange: function(e) {
        var self = this;
        var pid = e.target.dataset.pid;
        var form_list = self.data.form_list;
        for (var i in form_list[pid].default) {
            if (e.target.dataset.id == i) {
                form_list[pid].default[i]['selected'] = true;
            } else {
                form_list[pid].default[i]['selected'] = false;
            }
        }
        self.setData({
            form_list: form_list,
        });
    },
    /**
     * input 改变
     */
    inputChenge: function(e) {
        var self = this;
        var id = e.target.dataset.id;
        var form_list = self.data.form_list;
        form_list[id].default = e.detail.value;
        self.setData({
            form_list: form_list,
        });
    },

    /**
     * 获取数据
     * getsubmit_preview
     */
    getPreview: function(e) {
        var self = this;
        let goods_info = JSON.parse(e['goods_info'])[0];
        self.setData({
            attr: goods_info['attr']
        })
        var gid = goods_info['id'];
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        // getApp().core.showNavigationBarLoading();
        let attr = JSON.stringify(goods_info['attr']);
        getApp().request({
            url: getApp().api.book.submit_preview,
            method: "get",
            data: {
                gid: gid,
                attr: attr
            },
            success: function(res) {
                if (res.code == 0) {
                    for (var i in res.data.form_list) {
                        if (res.data.form_list[i].type == 'date') {
                            // res.data.form_list[i].default = res.data.form_list[i].default ? res.data.form_list[i].default : utils.formatData(new Date);

                            if (!res.data.form_list[i].default) {
                                res.data.form_list[i].default = utils.formatData(new Date);
                                self.setData({
                                    is_date_start: false
                                })
                            }


                        }
                        if (res.data.form_list[i].type == 'time') {
                            res.data.form_list[i].default = res.data.form_list[i].default ? res.data.form_list[i].default : '00:00';
                        }
                    }
                    var option = res.data.option;
                    if (option) {
                        if (option['balance'] == 1) {
                            self.setData({
                                balance: true,
                                pay_type: 'BALANCE_PAY'
                            })
                            getApp().request({
                                url: getApp().api.user.index,
                                success: function(res) {
                                    if (res.code == 0) {
                                        getApp().core.setStorageSync(getApp().const.USER_INFO, res.data.user_info);
                                    }
                                }
                            });
                        }
                        if (option['wechat'] == 1) {
                            self.setData({
                                wechat: true,
                                pay_type: 'WECHAT_PAY'
                            })
                        }
                    } else {
                        self.setData({
                            wechat: true,
                            pay_type: 'WECHAT_PAY'
                        })
                    }
                    self.setData({
                        goods: res.data.goods,
                        form_list: res.data.form_list,
                        level_price: res.data.level_price,
                    });
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function(res) {
                            if (res.confirm) {
                                getApp().core.redirectTo({
                                    url: '/pages/book/index/index'
                                });
                            }
                        }
                    });
                }
            },
            complete: function(res) {
                setTimeout(function() {
                    // 延长一秒取消加载动画
                    getApp().core.hideLoading();
                }, 1000);
            }
        });
    },
    booksubmit: function(e) {
        var self = this;
        var pay_type = self.data.pay_type;

        if (self.data.goods.price == 0) {
            self.submit(e);
            return;
        }
        if (pay_type == 'BALANCE_PAY') {
            var user_info = getApp().core.getStorageSync(getApp().const.USER_INFO);

            getApp().core.showModal({
                title: '当前账户余额：' + user_info.money,
                content: '是否使用余额',
                success: function(be) {
                    if (be.confirm) {
                        self.submit(e);
                    } else {
                        return;
                    }
                }
            })
        };
        if (pay_type == 'WECHAT_PAY') {
            self.submit(e);
        }
    },
    submit: function(e) {
        var form_id = e.detail.formId;
        var self = this;
        var gid = self.data.goods.id;
        var attr = JSON.stringify(self.data.attr);
        var form_list = JSON.stringify(self.data.form_list);
        var pay_type = self.data.pay_type;

        getApp().core.showLoading({
            title: "正在提交",
            mask: true,
        });
        // getApp().core.showNavigationBarLoading();
        getApp().request({
            url: getApp().api.book.submit,
            method: "post",
            data: {
                gid: gid,
                form_list: form_list,
                form_id: form_id,
                pay_type: pay_type,
                attr: attr
            },
            success: function(res) {
                if (res.code == 0) {
                    if (res.type == 1) {
                        // 免费 + 余额
                        getApp().core.redirectTo({
                            url: "/pages/book/order/order?status=1",
                        });
                    } else {
                        getApp().core.showLoading({
                            title: "正在提交",
                            mask: true,
                        });
                        //发起支付
                        getApp().core.requestPayment({
                            _res: res,
                            timeStamp: res.data.timeStamp,
                            nonceStr: res.data.nonceStr,
                            package: res.data.package,
                            signType: res.data.signType,
                            paySign: res.data.paySign,
                            success: function(e) {
                                getApp().core.redirectTo({
                                    url: "/pages/book/order/order?status=1",
                                });
                            },
                            fail: function(e) {},
                            complete: function(e) {
                                setTimeout(function() {
                                    // 延长一秒取消加载动画
                                    getApp().core.hideLoading();
                                }, 1000);
                                if (e.errMsg == "requestPayment:fail" || e.errMsg == "requestPayment:fail cancel") { //支付失败转到待支付订单列表
                                    getApp().core.showModal({
                                        title: "提示",
                                        content: "订单尚未支付",
                                        showCancel: false,
                                        confirmText: "确认",
                                        success: function(res) {
                                            if (res.confirm) {
                                                getApp().core.redirectTo({
                                                    url: "/pages/book/order/order?status=0",
                                                });
                                            }
                                        }
                                    });
                                    return;
                                }
                                if (e.errMsg == "requestPayment:ok") {
                                    return;
                                }
                                getApp().core.redirectTo({
                                    url: "/pages/book/order/order?status=-1",
                                });
                            },
                        });
                        return;
                    }
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function(res) {
                            // if (res.confirm) {
                            //     getApp().core.redirectTo({
                            //         url: '/pages/book/index/index'
                            //     });
                            // }
                        }
                    });
                }
            },
            complete: function(res) {
                setTimeout(function() {
                    // 延长一秒取消加载动画
                    getApp().core.hideLoading();
                }, 1000);
            }
        });
    },
    switch: function(e) {
        this.setData({
            pay_type: e.currentTarget.dataset.type
        })
    },
    uploadImg: function(e) {
        var self = this;
        var index = e.currentTarget.dataset.id;
        var form_list = self.data.form_list;
        getApp().uploader.upload({
            start: function() {
                getApp().core.showLoading({
                    title: '正在上传',
                    mask: true,
                });
            },
            success: function(res) {
                if (res.code == 0) {
                    form_list[index].default = res.data.url
                    self.setData({
                        form_list: form_list,
                    });
                } else {
                    self.showToast({
                        title: res.msg,
                    });
                }
            },
            error: function(e) {
                self.showToast({
                    title: e,
                });
            },
            complete: function() {
                getApp().core.hideLoading();
            }
        })
    }
})