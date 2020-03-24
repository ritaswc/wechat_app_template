if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    data: {
        page_num:1,
        is_loading:false,
    },

    onLoad (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        });
        getApp().request({
            url: getApp().api.lottery.detail,
            data: {
                id: options.id ? options.id : 0,
                lottery_id:options.lottery_id ? options.lottery_id : 0,
                form_id:options.form_id,
                page_num: 1,
            },
            success: function (res) {
                if(res.code == 0){
                    self.setData(res.data);
                }
            },
            complete: function(res){
                getApp().core.hideLoading();
            }
        });
        getApp().request({ 
            url: getApp().api.lottery.setting,
            success: function (res) {
                if(res.code==0){
                    var title = res.data.title;
                    if(title){
                        getApp().core.setNavigationBarTitle({
                            title:title,
                        })
                        self.setData({
                            title:title
                        })
                    }
                }
            },
        });

    },

    submit: function(e) {
        var goods_id = this.data.list.goods_id;
        var attr = JSON.parse(this.data.list.attr);
        getApp().core.navigateTo({
            url: "/pages/order-submit/order-submit?lottery_id=" + this.data.list.id + "&goods_info=" + JSON.stringify({
                goods_id: goods_id,
                attr: attr,
                num: 1,
            }),
        });
    },

    userload: function(){
        var self = this;
        var is_loading = self.data.is_loading;
        if (is_loading) {
            return;
        }
        var lottery_id = self.data;
        var page_num = self.data.page_num + 1;

        getApp().core.showLoading({
            title: '加载中',
        });
        getApp().request({
            url: getApp().api.lottery.detail,
            data: {
                id: this.data.list.id,
                page_num: page_num,
            },
            success: function (res) {
                if(res.code == 0){
                    if (res.data.user_list == null || res.data.user_list == 0) {
                        self.setData({
                            is_loading: true
                        })
                        return;
                    }
                    self.setData({
                        user_list:self.data.user_list.concat(res.data.user_list),
                        page_num:page_num
                    })                        
                }
            },
            complete: function (res) {
                getApp().core.hideLoading();
            }
        });
    },
    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);
        getApp().core.hideLoading();
        let user_info = getApp().getUser();
        let lottery_id = this.data.list.lottery_id;
        var res = {
            path: "/lottery/goods/goods?user_id=" + user_info.id + "&id="+ lottery_id,
            title: this.data.title?this.data.title:'抽奖',
        };
        return res;
    }
})

