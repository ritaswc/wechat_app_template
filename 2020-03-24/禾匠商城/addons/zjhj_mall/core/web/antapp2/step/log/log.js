if (typeof wx === 'undefined') var wx = getApp().core;
// step/log/log.js
var util = require('../../utils/helper.js');
var utils = getApp().helper;


Page({
    data: {
        currency: 0,
        bout_ratio: 0,
        total_bout: 0,
        bout: 0,
        page: 2,
        list: [{
                'name': ''
            },
            {
                'step_num': 0
            },
            {
                'user_currency': 0
            },
            {
                'user_num': 0
            },
            {
                'status': 0
            },
        ]
    },
    onReachBottom() {
        let that = this;
        let over = that.data.over
        if (!over) {
            let list = this.data.list;
            let page = this.data.page;
            this.setData({
                loading: true
            })
            getApp().request({
                url: getApp().api.step.activity_log,
                data: {
                    page: page
                },
                success(res) {
                    for (let i = 0; i < res.data.list.length; i++) {
                        list.push(res.data.list[i]);
                    }
                    if (res.data.list.length < 10) {
                        over = true;
                    }
                    that.setData({
                        list: list,
                        page: page + 1,
                        loading: false,
                        over: over
                    })
                }
            })
        }
    },
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        let that = this;
        let i = util.formatTime(new Date());
        let time = i[0] + i[1] + i[2] + i[3] + i[5] + i[6] + i[8] + i[9];
        getApp().core.showLoading({
                title: '数据加载中...',
                mask: true,
            }),
            getApp().request({
                url: getApp().api.step.activity_log,
                success(res) {
                    getApp().core.hideLoading();
                    let info = res.data.info;
                    let currency = 0;
                    if (info.currency > 0) {
                        currency = info.currency;
                    }
                    let list = res.data.list;
                    for (let i = 0; i < list.length; i++) {
                        if (list[i].open_date != undefined) {
                            list[i].date = list[i].open_date.replace("-", "").replace("-", "")
                        }
                    }
                    that.setData({
                        currency: currency,
                        bout_ratio: info.bout_ratio,
                        total_bout: info.total_bout,
                        bout: info.bout,
                        time: time,
                        list: list
                    })
                }
            })
    }
})