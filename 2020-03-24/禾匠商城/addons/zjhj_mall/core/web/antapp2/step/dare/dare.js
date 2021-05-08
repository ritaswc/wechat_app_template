if (typeof wx === 'undefined') var wx = getApp().core;
// step/dare/dare.js
var util = require('../../utils/helper.js');
var utils = getApp().helper;

Page({
    data: {
        unit_id: '',
        ad: false,
        space: false,
        step: 0,
        page: 2,
        over:false,
        success: false
    },
    onReachBottom() {
        let that = this;
        let over = that.data.over;
        let activity_data = that.data.activity_data;
        let user_id, code, iv, encrypted_data;
        if (!over) {
            let page = this.data.page;
            this.setData({
                loading: true
            })
            getApp().core.login({
                success(res) {
                    code = res.code;
                    // 获取iv和encryptedData
                    getApp().core.getWeRunData({
                        success(res) {
                            iv = res.iv;
                            encrypted_data = res.encryptedData;
                            getApp().request({
                                url: getApp().api.step.activity,
                                method: 'POST',
                                data: {
                                    encrypted_data: encrypted_data,
                                    iv: iv,
                                    code: code,
                                    user_id: user_id,
                                    page:page
                                },
                                success(res) {
                                    getApp().core.hideLoading();
                                    for (let i = 0; i < res.list.activity_data.length; i++) {
                                        activity_data.push(res.list.activity_data[i]);
                                    }
                                    
                                    if (res.list.activity_data.length < 3) {
                                        over = true;
                                    }
                                    for (let i = 0; i < activity_data.length; i++) {
                                        activity_data[i].date = activity_data[i].open_date.replace("-", "").replace("-", "")
                                    }
                                    that.setData({
                                        page: page + 1,
                                        over: over,
                                        loading: false,
                                        activity_data: activity_data,
                                    })

                                }
                            })
                        }
                    })
                }
            })
        }
    },
    openSetting() {
        let that = this;
        let user_id = that.data.user_id;
        getApp().core.openSetting({
            success(res) {
                if (res.authSetting['scope.werun'] == true && res.authSetting['scope.userInfo'] == true) {
                    that.setData({
                        authorize: true
                    })
                    getApp().core.showLoading({
                        title: '数据加载中...',
                        mask: true,
                    });
                    that.activity(user_id);
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
        getApp().page.onLoad(this, options);
        let page = 2;
        let that = this;
        let open_date = false;
        let join = false;
        let user_id;
        if (options.user_id !== null) {
            user_id = options.user_id;
        }
        getApp().request({
            url: getApp().api.step.setting,
            success(res) {
                if (res.code == 0) {
                    that.setData({
                        title: res.data.title,
                        share_title: res.data.share_title
                    })
                    }
            }
        })
        let i = util.formatTime(new Date());
        let time = i[0] + i[1] + i[2] + i[3] + i[5] + i[6] + i[8] + i[9];
        this.setData({
            page: page,
            time: time
        });
        if (options.open_date !== null) {
            open_date = options.open_date;
        }
        if (options.join !== null) {
            join = options.join;
        }
        that.setData({
            join: join,
            open_date: open_date
        })
        getApp().core.showLoading({
            title: '数据加载中...',
            mask: true,
        });
        getApp().core.getSetting({
            success(res) {
                if (res.authSetting['scope.werun'] == true && res.authSetting['scope.userInfo'] == true) {
                    that.activity(user_id);
                } else {
                    getApp().core.authorize({
                        scope: 'scope.userInfo',
                        success(res) {
                            getApp().core.authorize({
                                scope: 'scope.werun',
                                success(res) {
                                    if (res.errMsg == "authorize:ok") {
                                        // 读取数据
                                        that.activity(user_id);
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
                    })
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
    activity:function(user_id){
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
                            url: getApp().api.step.activity,
                            method: 'POST',
                            data: {
                                encrypted_data: encrypted_data,
                                iv: iv,
                                code: code,
                                user_id: user_id,
                            },
                            success(res) {
                                let step = res.list.run_data;
                                getApp().core.hideLoading();
                                let ad_data = res.list.ad_data;
                                let activity_data = res.list.activity_data;
                                let space;
                                space = false;
                                if (activity_data.length < 1) {
                                    space = true;
                                }
                                else {
                                    for (let i = 0; i < activity_data.length; i++) {
                                        activity_data[i].date = activity_data[i].open_date.replace("-", "").replace("-", "")
                                    }
                                }
                                let unit_id = false;
                                let ad = false;
                                if (ad_data !== null) {
                                    unit_id = res.list.ad_data.unit_id;
                                    ad = true;
                                }
                                that.setData({
                                    unit_id: unit_id,
                                    step: step,
                                    space: space,
                                    activity_data: activity_data,
                                    ad_data: ad_data,
                                    ad: ad
                                })

                            }
                        })
                    }
                })
            }
        })
    },
    adError: function(e) {
        let that = this;
        console.log(e.detail)
    },
    close: function() {
        this.setData({
            join: false
        })
    },
    onShareAppMessage: function(res) {
        getApp().page.onShareAppMessage(this);
        this.setData({
            join: false
        })
        var user_info = getApp().getUser();
        var res = {
            path: "/step/index/index?user_id=" + user_info.id,
            title: this.data.share_title ? this.data.share_title : this.data.title,
        };
        return res;
    },
    submit: function(e) {
        let code, iv, encrypted_data;
        console.log(e)
        let id = e.currentTarget.dataset.id;
        let step_num = e.currentTarget.dataset.step;
        let that = this;
        let num = this.data.step;
        getApp().core.showLoading({
            title: '正在提交...',
            mask: true,
        });
        //比较当前步数与目标步数
            // 获取code
            getApp().core.login({
                success(res) {
                    code = res.code;
                    // 获取iv和encryptedData
                    getApp().core.getWeRunData({
                        success(res) {
                            iv = res.iv;
                            encrypted_data = res.encryptedData;
                            getApp().request({
                                url: getApp().api.step.activity_submit,
                                method: 'POST',
                                data: {
                                    code: code,
                                    iv: iv,
                                    encrypted_data: encrypted_data,
                                    num: num,
                                    activity_id: id
                                },
                                success(res) {
                                    getApp().core.hideLoading();
                                    if (res.code == 0) {
                                        that.setData({
                                            success: true
                                        })
                                    } else {
                                        getApp().core.showModal({
                                            content: res.msg,
                                            showCancel: false,
                                        });
                                    }
                                }
                            })
                        }
                    })
                }
            })
    },
    success: function() {
        this.setData({
            success: false,
        })
        getApp().core.redirectTo({
            url: '../dare/dare'
        })
    }
})