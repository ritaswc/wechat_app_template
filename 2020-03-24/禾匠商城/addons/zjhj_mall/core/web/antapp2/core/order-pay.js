var app = getApp();

function setOnShowScene(scene) {
    if (!app.onShowData)
        app.onShowData = {};
    app.onShowData['scene'] = scene;
}

var pay = {
    init: function(page, _app) {
        var _this = this;
        var api = getApp().api;
        _this.page = page;
        app = _app;

        // 可能成为上级的用户ID
        var parentUserId = getApp().core.getStorageSync(getApp().const.PARENT_ID);
        if (!parentUserId) {
            parentUserId = 0;
        }

        //订单列表的订单支付
        _this.page.orderPay = function(e) {
            var index = e.currentTarget.dataset.index;
            var order_list = _this.page.data.order_list;
            var order = order_list[index];
            var pay_type_list = new Array();
            if (typeof _this.page.data.pay_type_list !== 'undefined') {
                pay_type_list = _this.page.data.pay_type_list;
            } else if (typeof order.pay_type_list !== 'undefined') {
                pay_type_list = order.pay_type_list;
            } else if (typeof order.goods_list[0].pay_type_list !== 'undefined') {
                pay_type_list = order.goods_list[0].pay_type_list;
            } else {
                var pp = {};
                pp['payment'] = 0
                pay_type_list.push(pp);
            }


            var pages = getCurrentPages();
            var new_page = pages[pages.length - 1];
            var route = new_page.route;
            var paramData = {}
            if (route.indexOf('pt') != -1) {
                url = api.group.pay_data;
                paramData.order_id = order.order_id;
            } else if (route.indexOf('miaosha') != -1) {
                url = api.miaosha.pay_data;
                paramData.order_id = order.order_id;
            } else if (route.indexOf('book') != -1) {
                url = api.book.order_pay;
                paramData.id = order.id;
            } else {
                var url = api.order.pay_data;
                paramData.order_id = order.order_id
            }

            paramData.parent_id = parentUserId; // TODO 从缓存中获取
            paramData.condition =  2; //首次付款

            if (pay_type_list.length == 1) {
                getApp().core.showLoading({
                    title: "正在提交",
                    mask: true,
                });
                if (pay_type_list[0]['payment'] == 0) {
                    WechatPay(paramData, url, route);
                }
                if (pay_type_list[0]['payment'] == 3) {
                    BalancePay(paramData, url, route);
                }
            } else {
                getApp().core.showModal({
                    title: '提示',
                    content: '选择支付方式',
                    cancelText: '余额支付',
                    confirmText: '线上支付',
                    success: function(res) {
                        if (res.confirm) {
                            getApp().core.showLoading({
                                title: "正在提交",
                                mask: true,
                            });
                            WechatPay(paramData, url, route);
                        } else if (res.cancel) {
                            BalancePay(paramData, url, route);
                        }
                    }
                })
            }

            function WechatPay(paramData, url, route) {
                paramData.pay_type = "WECHAT_PAY";
                app.request({
                    url: url,
                    data: paramData,
                    complete: function() {
                        getApp().core.hideLoading();
                    },
                    success: function(res) {
                        if (res.code == 0) {
                            setOnShowScene('pay');
                            getApp().core.requestPayment({
                                _res: res,
                                timeStamp: res.data.timeStamp,
                                nonceStr: res.data.nonceStr,
                                package: res.data.package,
                                signType: res.data.signType,
                                paySign: res.data.paySign,
                                success: function(e) {},
                                fail: function(e) {},
                                complete: function(e) {

                                    if (e.errMsg == "requestPayment:fail" || e.errMsg == "requestPayment:fail cancel") { //支付失败转到待支付订单列表
                                        getApp().core.showModal({
                                            title: "提示",
                                            content: "订单尚未支付",
                                            showCancel: false,
                                            confirmText: "确认",
                                            success: function(res) {
                                                if (res.confirm) {
                                                    getApp().core.redirectTo({
                                                        url: "/" + route + "?status=0",
                                                    });
                                                }
                                            }
                                        });
                                        return;
                                    }

                                    getApp().core.redirectTo({
                                        url: "/" + route + "?status=1",
                                    });


                                },
                            });
                        }
                        if (res.code == 1) {
                            getApp().core.showModal({
                                title: '提示',
                                content: res.msg,
                                showCancel: false,
                            });
                        }

                    }
                });
            }

            function BalancePay(paramData, url, route) {
                paramData.pay_type = "BALANCE_PAY";
                var user_info = getApp().getUser();
                getApp().core.showModal({
                    title: '当前账户余额：' + user_info.money,
                    content: '是否使用余额',
                    success: function(e) {
                        if (e.confirm) {
                            getApp().core.showLoading({
                                title: "正在提交",
                                mask: true,
                            });
                            app.request({
                                url: url,
                                data: paramData,
                                complete: function() {
                                    getApp().core.hideLoading();
                                },
                                success: function(res) {
                                    if (res.code == 0) {
                                        getApp().core.redirectTo({
                                            url: "/" + route + "?status=1",
                                        });
                                    }
                                    if (res.code == 1) {
                                        getApp().core.showModal({
                                            title: '提示',
                                            content: res.msg,
                                            showCancel: false
                                        });
                                    }
                                }
                            });
                        }
                    }
                })
            }
        };


        //订单支付
        _this.page.order_submit = function(data, order_type) {
            var url_submit = api.order.submit;
            var url_pay_data = api.order.pay_data;
            var route = "/pages/order/order";
            if (order_type == 'pt') {
                url_submit = api.group.submit;
                url_pay_data = api.group.pay_data;
                route = "/pages/pt/order/order";
            } else if (order_type == 'ms') {
                url_submit = api.miaosha.submit;
                url_pay_data = api.miaosha.pay_data
                route = "/pages/miaosha/order/order";
            } else if (order_type == 'pond') {
                url_submit = api.pond.submit;
                url_pay_data = api.order.pay_data;
                route = "/pages/order/order";
            } else if (order_type == 'scratch') {
                url_submit = api.scratch.submit;
                url_pay_data = api.order.pay_data;
                route = "/pages/order/order";
            } else if (order_type == 'lottery') {
                url_submit = api.lottery.submit;
                url_pay_data = api.order.pay_data;
                route = "/pages/order/order";
            } else if (order_type == 'step') {
              url_submit = api.step.submit;
              url_pay_data = api.order.pay_data;
              route = "/pages/order/order";
            } else if (order_type == 's') {
                url_submit = api.order.new_submit;
                url_pay_data = api.order.pay_data;
                route = "/pages/order/order";
            }

            if (data.payment == 3) {
                var user_info = getApp().getUser();
                getApp().core.showModal({
                    title: '当前账户余额：' + user_info.money,
                    content: '是否确定使用余额支付',
                    success: function(e) {
                        if (e.confirm) {
                            submit_pay();
                        }
                    }
                })
            } else {
                submit_pay();
            }

            function submit_pay() {
                getApp().core.showLoading({
                    title: "正在提交",
                    mask: true,
                });
                //提交订单
                app.request({
                    url: url_submit,
                    method: "post",
                    data: data,
                    success: function(res) {
                        if (res.code == 0) {
                            getApp().page.bindParent({
                                parent_id: parentUserId, // TODO 从缓存中获取
                                condition: 1, //首次下单
                            })

                            if (res.data.p_price != undefined && res.data.p_price === 0) {
                              if (order_type == 'step'){
                                getApp().core.showToast({
                                  title: "兑换成功",
                                });
                              }else{
                                getApp().core.showToast({
                                  title: "提交成功",
                                });
                              }

                                setTimeout(function() {
                                    getApp().core.redirectTo({
                                        url: "/pages/order/order?status=1",
                                    });
                                }, 2000);



                                return;
                            }
                            setTimeout(function() {
                                _this.page.setData({
                                    options: {},
                                });
                            }, 1);
                            var order_id = res.data.order_id || '';
                            var order_id_list = res.data.order_id_list ? JSON.stringify(res.data.order_id_list) : '';

                            var pay_type = '';
                            //获取支付数据
                            if (data.payment == 0) {
                                app.request({
                                    url: url_pay_data,
                                    data: {
                                        order_id: order_id,
                                        order_id_list: order_id_list,
                                        pay_type: 'WECHAT_PAY',
                                        parent_user_id: parentUserId, // TODO 从缓存中获取
                                        condition: 2, //首次付款
                                    },
                                    success: function(res) {
                                        if (res.code == 0) {
                                            setTimeout(function() {
                                                getApp().core.hideLoading();
                                            }, 1000);
                                            setOnShowScene('pay');
                                            if (res.data && res.data.price == 0) {
                                                if (typeof _this.page.data.goods_card_list !== 'undefined' && _this.page.data.goods_card_list.length > 0) {
                                                    _this.page.setData({
                                                        show_card: true
                                                    });
                                                } else {
                                                    getApp().core.redirectTo({
                                                        url: route + "?status=1",
                                                    });
                                                }
                                            } else {
                                                //发起支付
                                                getApp().core.requestPayment({
                                                    _res: res,
                                                    timeStamp: res.data.timeStamp,
                                                    nonceStr: res.data.nonceStr,
                                                    package: res.data.package,
                                                    signType: res.data.signType,
                                                    paySign: res.data.paySign,
                                                    success: function(e) {},
                                                    fail: function(e) {},
                                                    complete: function(e) {
                                                        if (e.errMsg == "requestPayment:fail" || e.errMsg == "requestPayment:fail cancel") { //支付失败转到待支付订单列表
                                                            getApp().core.showModal({
                                                                title: "提示",
                                                                content: "订单尚未支付",
                                                                showCancel: false,
                                                                confirmText: "确认",
                                                                success: function(res) {
                                                                    if (res.confirm) {
                                                                        getApp().core.redirectTo({
                                                                            url: route + "?status=0",
                                                                        });
                                                                    }
                                                                }
                                                            });
                                                            return;
                                                        }
                                                        if (e.errMsg == "requestPayment:ok") {
                                                            if (typeof _this.page.data.goods_card_list !== 'undefined' && _this.page.data.goods_card_list.length > 0) {
                                                                _this.page.setData({
                                                                    show_card: true
                                                                });
                                                            } else {
                                                                if (order_type == 'pt') {
                                                                    if (_this.page.data.type == 'ONLY_BUY') {
                                                                        getApp().core.redirectTo({
                                                                            url: route + "?status=2",
                                                                        });
                                                                    } else {
                                                                        getApp().core.redirectTo({
                                                                            url: "/pages/pt/group/details?oid=" + order_id,
                                                                        });
                                                                    }
                                                                } else {
                                                                    getApp().core.redirectTo({
                                                                        url: route + "?status=1",
                                                                    });
                                                                }
                                                            }
                                                            return;
                                                        }
                                                    },
                                                });

                                            }

                                            var quick_list = getApp().core.getStorageSync(getApp().const.QUICK_LIST)
                                            if (quick_list) {
                                                var length = quick_list.length;
                                                for (var i = 0; i < length; i++) {
                                                    var goods = quick_list[i]['goods'];
                                                    var length2 = goods.length;
                                                    for (var a = 0; a < length2; a++) {
                                                        goods[a]['num'] = 0;
                                                    }
                                                }
                                                getApp().core.setStorageSync(getApp().const.QUICK_LISTS, quick_list)

                                                var carGoods = getApp().core.getStorageSync(getApp().const.CARGOODS)
                                                var length = carGoods.length;
                                                for (var i = 0; i < length; i++) {
                                                    carGoods[i]['num'] = 0;
                                                    carGoods[i]['goods_price'] = 0;
                                                    page.setData({
                                                        'carGoods': carGoods
                                                    });
                                                }
                                                getApp().core.setStorageSync(getApp().const.CARGOODS, carGoods)

                                                var total = getApp().core.getStorageSync(getApp().const.TOTAL)
                                                if (total) {
                                                    total.total_num = 0;
                                                    total.total_price = 0;
                                                    getApp().core.setStorageSync(getApp().const.TOTAL, total)
                                                }

                                                var check_num = getApp().core.getStorageSync(getApp().const.CHECK_NUM)
                                                check_num = 0;
                                                getApp().core.setStorageSync(getApp().const.CHECK_NUM, check_num)

                                                var quick_hot_goods_lists = getApp().core.getStorageSync(getApp().const.QUICK_HOT_GOODS_LISTS)
                                                var length = quick_hot_goods_lists.length;
                                                for (var i = 0; i < length; i++) {
                                                    quick_hot_goods_lists[i]['num'] = 0;
                                                    page.setData({
                                                        'quick_hot_goods_lists': quick_hot_goods_lists
                                                    });
                                                }
                                                getApp().core.setStorageSync(getApp().const.QUICK_HOT_GOODS_LISTS, quick_hot_goods_lists)
                                            }
                                            return;
                                        }

                                        if (res.code == 1) {
                                            getApp().core.hideLoading();
                                            getApp().core.showModal({
                                                title: '提示',
                                                content: res.msg,
                                                showCancel: false,
                                            });
                                            return;
                                        }
                                    }
                                });
                            } else if (data.payment == 2) {
                                pay_type = 'HUODAO_PAY';
                                pay()
                            } else if (data.payment == 3) {
                                pay_type = 'BALANCE_PAY';
                                pay()
                            }

                            function pay() {
                                app.request({
                                    url: url_pay_data,
                                    data: {
                                        order_id: order_id,
                                        order_id_list: order_id_list,
                                        pay_type: pay_type,
                                        form_id: data.formId,
                                        parent_user_id: parentUserId, // TODO 从缓存中获取
                                        condition: 2, //首次付款
                                    },
                                    success: function(res) {
                                        if (res.code == 0) {
                                            setTimeout(function() {
                                                getApp().core.hideLoading();
                                            }, 1000);
                                            if (order_type == 'pt') {
                                                if (_this.page.data.type == 'ONLY_BUY') {
                                                    getApp().core.redirectTo({
                                                        url: route + "?status=2",
                                                    });
                                                } else {
                                                    getApp().core.redirectTo({
                                                        url: "/pages/pt/group/details?oid=" + order_id,
                                                    });
                                                }
                                            } else {
                                                if (typeof _this.page.data.goods_card_list !== 'undefined' && _this.page.data.goods_card_list.length > 0 && data.payment != 2) {
                                                    _this.page.setData({
                                                        show_card: true
                                                    });
                                                } else {
                                                    getApp().core.redirectTo({
                                                        url: route + "?status=-1",
                                                    });
                                                }
                                            }
                                        } else {
                                            getApp().core.hideLoading();
                                            getApp().core.showModal({
                                                title: "提示",
                                                content: res.msg,
                                                showCancel: false,
                                                confirmText: "确认",
                                                success: function(res) {
                                                    if (res.confirm) {
                                                        getApp().core.redirectTo({
                                                            url: route + "?status=0",
                                                        });
                                                    }
                                                }
                                            });
                                            return;
                                        }
                                    }

                                });
                            }
                        }
                        if (res.code == 1) {
                            getApp().core.hideLoading();
                            if (res.msg == '活力币不足' && _this.page.data.store.option.step.currency_name) {
                                getApp().core.showModal({
                                    title: '提示',
                                    content: _this.page.data.store.option.step.currency_name + '不足',
                                    showCancel: false,
                                });
                            } else {
                                getApp().core.showModal({
                                    title: '提示',
                                    content: res.msg,
                                    showCancel: false,
                                });
                            }
                        }
                    }
                });
            }
        }
    },
};
module.exports = pay;