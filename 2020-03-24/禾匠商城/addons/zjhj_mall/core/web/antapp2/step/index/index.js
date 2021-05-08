if (typeof wx === 'undefined') var wx = getApp().core;
// step/index/index.js
var util = require('../../utils/helper.js');
var utils = getApp().helper;

Page({
    data: {
        dare: false,
        my: '0',
        todayStep: '0',
        authorize: true,
        overStep: '0',
        banner_list: [],
        useStep: '0',
        nowAdd: '0.00',
        today: '',
        nextAdd: '0.00',
        people: '2153',
        friend: [],
        now: false,
        convert_ratio: '',
        activity_data: [{
            id: 0
        }, {
            open_date: ''
        }, {
            name: ''
        }, {
            bail_currency: 0
        }, {
            step_num: 0
        }],
        convert_max: 0,
        title: '',
        goods: [],
        user_id: 0,
        time: '',
        encrypted_data: '',
        iv: '',
        code: '',
        page: 2,
        unit_id: '',
        user_id: '',
        over: false
    },

    switch: function(e) {
        let off = e.detail.value;
        let remind = 0;
        if (off == true) {
            remind = 1;
        } else {
            remind = 0;
        }
        getApp().request({
            url: getApp().api.step.remind,
            data: {
                remind: remind,
            }
        })
    },
    // 步数兑换按钮
    exchange: function() {
        let that = this;
        let iv, code, encrypted_data;
        let nowAdd = that.data.nowAdd;
        let todayStep = that.data.todayStep * (1 + nowAdd / 100);
        let useStep = that.data.useStep;
        let convert_ratio = that.data.convert_ratio;
        let convert_max = that.data.convert_max;
        let nowStep = parseInt(todayStep);
        if (convert_max > 0 && nowStep > +convert_max) {
            nowStep = +convert_max;
        }
        nowStep = nowStep - useStep;
        // 当前步数减去兑换过的步数
        let overStep = that.data.overStep;
        let price = (nowStep / convert_ratio).toString().match(/^\d+(?:\.\d{0,2})?/);
        if (price < 0.01 || overStep == 0) {
            getApp().core.showModal({
                content: "步数不足",
                showCancel: false,
            });
        } else {
            getApp().core.showModal({
                content: '确认把' + overStep + '步兑换为' + price + (that.data.store.option.step.currency_name ? that.data.store.option.step.currency_name : '活力币'),
                success(res) {
                    if (res.confirm) {
                        getApp().core.showLoading({
                            title: '兑换中...',
                            mask: true,
                        });
                        getApp().core.login({
                            success(res) {
                                code = res.code;
                                getApp().core.getWeRunData({
                                    success(res) {
                                        iv = res.iv;
                                        encrypted_data = res.encryptedData;
                                        getApp().request({
                                            url: getApp().api.step.convert,
                                            method: 'post',
                                            data: {
                                                iv: iv,
                                                code: code,
                                                encrypted_data: encrypted_data,
                                                num: that.data.todayStep
                                            },
                                            success(res) {
                                                getApp().core.hideLoading();
                                                if (res.code == 0) {
                                                    if (convert_max > 0 && nowStep > +convert_max) {
                                                        nowStep = +convert_max;
                                                    }
                                                    // 当前步数减去兑换过的步数
                                                    nowStep = nowStep - res.list.num;
                                                    let money = (+that.data.my + +res.list.convert).toFixed(2);
                                                    that.setData({
                                                        overStep: nowStep,
                                                        my: money,
                                                        useStep: res.list.num
                                                    })
                                                } else {
                                                    getApp().core.showModal({
                                                        content: res.msg,
                                                        showCancel: false,
                                                    });
                                                }
                                            }
                                        });
                                    }
                                })
                            }
                        })
                    }
                },
                fail(res) {
                    getApp().core.hideLoading();
                    getApp().core.showModal({
                        content: "为确保您的正常使用，请完善授权操作",
                        showCancel: false,
                    });
                }
            })
        }
    },
    adError: function(e) {
        let that = this;
        console.log(e.detail)
    },
    onShareAppMessage: function(res) {
        getApp().page.onShareAppMessage(this);
        var user_info = getApp().getUser();
        var res = {
            path: "/step/dare/dare?user_id=" + user_info.id,
            title: this.data.title ? this.data.title : '步数挑战',
        };
        return res;
    },
    onReachBottom() {
        let that = this;
        let over = that.data.over
        if (!over) {
            let encrypted_data = this.data.encrypted_data;
            let iv = this.data.iv;
            let code = this.data.code;
            let user_id = this.data.user_id;
            let goods = this.data.goods;
            let page = this.data.page;
            this.setData({
                loading: true
            })
            getApp().request({
                url: getApp().api.step.index,
                method: 'POST',
                data: {
                    encrypted_data: encrypted_data,
                    iv: iv,
                    code: code,
                    user_id: user_id,
                    page: page
                },
                success(res) {
                    for (let i = 0; i < res.data.goods_data.length; i++) {
                        goods.push(res.data.goods_data[i]);
                    }
                    if (res.data.goods_data.length < 6) {
                        over = true;
                    }
                    that.setData({
                        goods: goods,
                        page: page + 1,
                        over: over,
                        loading: false
                    })
                }
            })
        }
    },
    refresh: function() {
        getApp().core.showLoading({
            title: '步数加载中...',
            mask: true,
        });
        let that = this;
        let convert_max = that.data.convert_max;
        that.runData(that.data.user_id, convert_max);
    },
    onShow: function() {
        if (this.data.now == false) {
            return;
        }
        let that = this;
        let code, iv, encrypted_data;
        let user_id = that.data.user_id;
        getApp().core.login({
            success(res) {
                code = res.code;
                // 获取iv和encryptedData
                getApp().core.getWeRunData({
                    success(res) {
                        iv = res.iv;
                        encrypted_data = res.encryptedData;
                        getApp().request({
                            url: getApp().api.step.index,
                            method: 'POST',
                            data: {
                                encrypted_data: encrypted_data,
                                iv: iv,
                                code: code,
                                user_id: user_id,
                                page: 1
                            },
                            success(res) {
                                getApp().core.hideLoading();
                                let activity_data = res.data.activity_data;
                                let user_data = res.data.user_data;
                                let my = res.data.user_data.step_currency;
                                that.setData({
                                    activity_data: activity_data,
                                    user_data: user_data,
                                    my: my
                                })
                            }
                        })
                    }
                })
            }
        })
    },
    //授权完成后加载数据
    runData: function(user_id, convert_max) {
        let that = this;
        let code, iv, encrypted_data;
        getApp().core.login({
            success(res) {
                code = res.code;
                // 获取iv和encryptedData
                getApp().core.getWeRunData({
                    success(res) {
                        iv = res.iv;
                        encrypted_data = res.encryptedData;
                        getApp().request({
                            url: getApp().api.step.index,
                            method: 'POST',
                            data: {
                                encrypted_data: encrypted_data,
                                iv: iv,
                                code: code,
                                user_id: user_id,
                                page: 1
                            },
                            success(res) {
                                // 隐藏load提示框，获取数据
                                getApp().core.hideLoading();
                                let activity_data;
                                let dare;
                                if (res.data.activity_data.id == null) {
                                    dare = false;
                                    activity_data = [];
                                } else {
                                    dare = true;
                                    activity_data = res.data.activity_data;
                                }
                                let step = res.data.run_data.stepInfoList;
                                let user = res.data.user_data;
                                let finish;
                                let banner_list = [{
                                    pic_url: '../image/ad.png'
                                }];
                                if (res.data.banner_list.length > 0) {
                                    banner_list = res.data.banner_list;
                                }
                                let unit_id = false;
                                if (res.data.ad_data !== null) {
                                    unit_id = res.data.ad_data.unit_id;
                                }
                                let my = user.step_currency;
                                let ad_data = res.data.ad_data;
                                let goods = res.data.goods_data;
                                let todayStep = step[step.length - 1].step;
                                let today = step[step.length - 1].timestamp;
                                // 获取明天步数加成百分比数字
                                let nextAdd = user.ratio / 10;
                                // 助力好友的头像，并通过比较时间来计算当前加成
                                let friend = user.invite_list;
                                let useStep = 0;
                                let nowAdd = 0;
                                if (user.now_ratio) {
                                    nowAdd = user.now_ratio / 10;
                                }
                                let checked;
                                // 每日兑换提醒开关
                                if (user.remind == 0) {
                                    checked = false;
                                } else if (user.remind == 1) {
                                    checked = true;
                                }
                                // 如果有兑换过的情况
                                if (user.convert_num > 0) {
                                    useStep = user.convert_num;
                                }
                                // 获取当前步数
                                let overStep = parseInt(todayStep * (1 + nowAdd / 100));
                                // 如果最大步数限制存在且小于当前步数显示最大步数限制
                                if (convert_max > 0 && overStep > +convert_max) {
                                    overStep = +convert_max;
                                }
                                // 当前步数减去兑换过的步数
                                overStep = overStep - +useStep;
                                // 当前步数超过1000，在百位前加一个逗号
                                if (overStep >= 1000) {
                                    overStep = String(overStep).replace(/(\d)(?=(\d{3})+$)/g, "$1,");
                                }
                                // 判断步数挑战赛参赛状态以及完成状态
                                let open_date = '';
                                if (activity_data.open_date != undefined) {
                                    open_date = activity_data.open_date.replace(".", "").replace(".", "")
                                }
                                if (activity_data.step_num > todayStep) {
                                    finish = false;
                                } else {
                                    finish = true;
                                }
                                if (overStep < 0) {
                                    overStep = 0;
                                }
                                let length = friend.length;
                                that.setData({
                                    overStep: overStep,
                                    todayStep: todayStep,
                                    nextAdd: nextAdd,
                                    friend: friend,
                                    today: today,
                                    'finish': finish,
                                    nowAdd: nowAdd,
                                    my: my,
                                    now: true,
                                    user: user,
                                    'length': length,
                                    banner_list: banner_list,
                                    useStep: useStep,
                                    goods: goods,
                                    user_id: user_id,
                                    "checked": checked,
                                    encrypted_data: encrypted_data,
                                    iv: iv,
                                    page: 2,
                                    code: code,
                                    open_date: open_date,
                                    activity_data: activity_data,
                                    dare: dare,
                                    ad_data: ad_data,
                                    unit_id: unit_id,
                                    user_id: user.user_id,
                                })
                            },
                            fail(res) {
                                getApp().core.showModal({
                                    content: res.errMsg,
                                    showCancel: false,
                                });
                            }
                        })
                    },
                    fail(res) {
                        if (res.errMsg == 'getWeRunData:fail cancel') {
                            getApp().core.showModal({
                                content: '读取失败，请稍后再试',
                                showCancel: false,
                            });
                        } else if (res.errMsg == 'getWeRunData: fail device not support') {
                            getApp().core.showModal({
                                content: '请在微信中搜索"微信运动"公众号，并点击关注',
                                showCancel: false,
                            });
                        } else {
                            getApp().core.showModal({
                                content: res.errMsg,
                                showCancel: false,
                            });
                        }

                    }
                })
            },
            fail(res) {
                getApp().core.showModal({
                    content: res.errMsg,
                    showCancel: false,
                });
            }
        })
    },
    openSetting() {
        let that = this;
        getApp().core.openSetting({
            success(res) {
                if (res.authSetting['scope.werun'] == true && res.authSetting['scope.userInfo'] == true) {
                    that.setData({
                        authorize: true
                    })
                    getApp().core.showLoading({
                        title: '步数加载中...',
                        mask: true,
                    });
                    let user_id = that.data.user_id;
                    let convert_max = that.data.convert_max;
                    that.runData(user_id, convert_max);
                }
            },
            fail(res) {
                that.setData({
                    authorize: false
                })
                getApp().core.hideLoading();
            }
        })
    },
    onLoad: function(options) {
        var now = false;
        getApp().page.onLoad(this, options);
        let user_id = 0;
        if (options.scene !== null) {
            const scene = decodeURIComponent(options.scene)
            var scene_obj = utils.scene_decode(scene);
            if (scene_obj.uid > 0) {
                user_id = scene_obj.uid;
            }
        }
        if (options.user_id > 0) {
            user_id = options.user_id;
        }
        this.setData({
            user_id: user_id,
            now: now
        })
        let i = util.formatTime(new Date());
        let time = i[0] + i[1] + i[2] + i[3] + i[5] + i[6] + i[8] + i[9];
        this.setData({
            time: time
        });
        getApp().core.showLoading({
            title: '步数加载中...',
            mask: true,
        });
        getApp().page.onShow(this);
        getApp().core.showShareMenu({
            withShareTicket: true
        })
        var that = this;
        let convert_max;
        // 获取小程序标题
        getApp().request({
            url: getApp().api.step.setting,
            success(res) {
                if (res.code == 0) {
                    var title = res.data.title;
                    var share_title = res.data.share_title;
                    convert_max = res.data.convert_max;
                    if (title) {
                        getApp().core.setNavigationBarTitle({
                            title: title,
                        })
                        that.setData({
                            title: title,
                            share_title: share_title
                        })
                    }
                    // 获取活力币兑换比例
                    that.setData({
                        convert_ratio: res.data.convert_ratio,
                        convert_max: convert_max
                    })
                    // 查看是否授权
                    getApp().core.getSetting({
                        success(res) {
                            if (res.authSetting['scope.werun'] == true && res.authSetting['scope.userInfo'] == true) {
                                that.runData(user_id, convert_max);
                            } else if (res.authSetting['scope.userInfo'] == true) {
                                getApp().core.authorize({
                                    scope: 'scope.werun',
                                    success(res) {
                                        if (res.errMsg == "authorize:ok") {
                                            // 读取数据
                                            that.runData(user_id, convert_max);
                                        }
                                    },
                                    fail(res) {
                                        that.setData({
                                            authorize: false
                                        })
                                        getApp().core.hideLoading();
                                    }
                                });
                            }
                        },
                        fail(res) {
                            that.setData({
                                authorize: false
                            })
                            getApp().core.hideLoading();
                        }
                    })
                }
            },
            fail(res) {
                getApp().core.showModal({
                    content: res.errMsg,
                    showCancel: false,
                });
            }
        });
    },
    onShareAppMessage: function(res) {
        getApp().page.onShareAppMessage(this);
        var user_info = getApp().getUser();
        var res = {
            path: "/step/index/index?user_id=" + user_info.id,
            title: this.data.share_title ? this.data.share_title : this.data.title,
        };
        return res;
    }
})