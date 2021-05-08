if (typeof wx === 'undefined') var wx = getApp().core;
// step/join/join.js
Page({
    data: {
        name: 0,
        open_date: '',
        step_num: 0,
        bail_currency: 0,
        join: false
    },

    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        let that = this;
        let id;
        if (options.id == null) {
            getApp().core.reLaunch({
                url: "../index/index"
            })
        } else {
            id = options.id;
        }
        getApp().core.showLoading({
                title: '数据加载中...',
                mask: true,
            }),
            getApp().request({
                url: getApp().api.step.activity_detail,
                data: {
                    activity_id: id
                },
                success(res) {
                    getApp().core.hideLoading();
                    let open_date = res.data.list.open_date.replace(".", "/").replace(".", "/");
                    that.setData({
                        id: id,
                        name: res.data.list.name,
                        open_date: open_date,
                        step_num: res.data.list.step_num,
                        bail_currency: res.data.list.bail_currency,
                    })
                }
            })
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
    apply: function() {
        let that = this;
        getApp().request({
            url: getApp().api.step.activity_join,
            data: {
                activity_id: that.data.id
            },
            success(res) {
                let open_date = that.data.open_date.slice(5);
                if (res.code == 0) {
                    getApp().core.redirectTo({
                        url: '../dare/dare?open_date=' + open_date + '&join=true'
                    })
                } else {
                    if (res.msg == '活力币不足' && that.data.store.option.step.currency_name){
                        getApp().core.showModal({
                            content: that.data.store.option.step.currency_name + '不足',
                            showCancel: false,
                        });
                    }else{
                        getApp().core.showModal({
                            content: res.msg,
                            showCancel: false,
                        });
                    }

                }
            }
        })
    }
})