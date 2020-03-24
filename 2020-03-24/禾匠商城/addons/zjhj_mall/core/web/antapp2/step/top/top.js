if (typeof wx === 'undefined') var wx = getApp().core;
// step/top.js
Page({
    data: {
        friend: true,
        country: false,
        avatar: '',
        name: '',
        noun: '',
        bg: "../image/topBG.png",
        id: 1,
        page: 2,
        money: '',
        loading: false,
        unit_id: '',
        list: [],
        over: false,
        ad: false
    },
    adError: function(e) {
        let that = this;
        console.log(e.detail)
    },
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        let that = this;
        let list = this.data.list;
        getApp().core.showLoading({
                title: '数据加载中...',
                mask: true,
            }),
            getApp().request({
                url: getApp().api.step.ranking,
                data: {
                    status: 1,
                    page: 1
                },
                success(res) {
                    getApp().core.hideLoading();
                    let user = res.data.user;
                    let list = res.data.list;
                    let ad_data = res.data.ad_data;
                    if (list.length > 3) {
                        for (let i = 3; i < list.length; i++) {
                            list[i].noun = i + 1;
                        }
                        list[0].img = '../image/top1.png';
                        list[1].img = '../image/top2.png';
                        list[2].img = '../image/top3.png';
                    } else if (list.length > 0 && list.length <= 3) {
                        list[0].img = '../image/top1.png';
                        if (list.length > 1) {
                            list[1].img = '../image/top2.png';
                        }
                        if (list.length > 2) {
                            list[2].img = '../image/top3.png';
                        }
                    }
                    let unit_id = false;
                    let ad = false;
                    if (res.data.ad_data !== null) {
                        unit_id = res.data.ad_data.unit_id;
                        ad = true
                    }

                    that.setData({
                        list: list,
                        name: user.user.nickname,
                        avatar: user.user.avatar_url,
                        noun: user.raking,
                        money: user.step_currency,
                        unit_id: unit_id,
                        ad_data: ad_data,
                        ad: ad
                    })
                }
            })
    },
    onReachBottom() {
        let that = this;
        let over = that.data.over;
        if (!over) {
            let id = this.data.id;
            let list = this.data.list;
            let page = this.data.page;
            this.setData({
                loading: true
            })
            getApp().request({
                url: getApp().api.step.ranking,
                data: {
                    status: id,
                    page: page
                },
                success(res) {
                    let remain = res.data.list;
                    list = list.concat(remain);
                    this.data.loading = false;
                    for (let i = (page - 1) * 10; i < list.length; i++) {
                        list[i].noun = i + 1;
                    }
                    if (remain.length < 10) {
                        over = true;
                    }
                    that.setData({
                        list: list,
                        id: id,
                        page: page + 1,
                        loading: false,
                        over: over
                    })
                }
            })
        }
    },
    change: function(e) {
        getApp().core.showLoading({
            title: '数据加载中...',
            mask: true,
        });
        let id = e.target.id;
        let friend, country;
        let that = this;
        let list = this.data.list;
        if (id == 1) {
            friend = true,
                country = false
        } else if (id == 2) {
            friend = false,
                country = true
        }
        getApp().request({
            url: getApp().api.step.ranking,
            data: {
                status: id,
            },
            success(res) {
                getApp().core.hideLoading();
                let user = res.data.user;
                let list = res.data.list;
                if (list.length > 3) {
                    for (let i = 3; i < list.length; i++) {
                        list[i].noun = i + 1;
                    }
                    list[0].img = '../image/top1.png';
                    list[1].img = '../image/top2.png';
                    list[2].img = '../image/top3.png';
                } else if (list.length > 0 && list.length <= 3) {
                    list[0].img = '../image/top1.png';
                    if (list.length > 1) {
                        list[1].img = '../image/top2.png';
                    }
                    if (list.length > 2) {
                        list[2].img = '../image/top3.png';
                    }
                }
                that.setData({
                    list: list,
                    id: id,
                    name: user.user.nickname,
                    avatar: user.user.avatar_url,
                    noun: user.raking,
                    money: user.step_currency,
                    friend: friend,
                    page: 2,
                    over:false,
                    country: country
                })
            }
        })
    }
})