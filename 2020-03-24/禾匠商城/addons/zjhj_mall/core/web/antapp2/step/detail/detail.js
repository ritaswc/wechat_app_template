if (typeof wx === 'undefined') var wx = getApp().core;
// step/detail/detail.js
Page({
    data: {
        number: 0,
        _num: 1,
        page: 2,
        list: [],
        over: false
    },
    tab: function(e) {
        let that = this;
        let num = e.target.dataset.num;
        getApp().core.showLoading({
                title: '数据加载中...',
                mask: true,
            }),
            getApp().request({
                url: getApp().api.step.log,
                data: {
                    status: num,
                },
                success(res) {
                    getApp().core.hideLoading();
                    let list = res.data.log;
                    that.setData({
                        number: res.data.user.step_currency,
                        list: list,
                        _num: num,
                        page: 2
                    })
                }
            })
    },
    onReachBottom() {
        let that = this;
        let over = that.data.over
        if (!over) {
            let id = this.data.id;
            let list = this.data.list;
            let _num = this.data._num;
            let page = this.data.page;
            this.setData({
                loading: true
            })
            getApp().request({
                url: getApp().api.step.log,
                data: {
                    status: _num,
                    page: page
                },
                success(res) {
                    for (let i = 0; i < res.data.log.length; i++) {
                        list.push(res.data.log[i]);
                    }
                    if (res.data.log.length < 6) {
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
        getApp().core.showLoading({
                title: '数据加载中...',
                mask: true,
            }),
            getApp().request({
                url: getApp().api.step.log,
                data: {
                    status: 1,
                    page: 1
                },
                success(res) {
                    getApp().core.hideLoading();
                    let list = res.data.log;
                    that.setData({
                        number: res.data.user.step_currency,
                        list: list
                    })
                }
            })
    }
})