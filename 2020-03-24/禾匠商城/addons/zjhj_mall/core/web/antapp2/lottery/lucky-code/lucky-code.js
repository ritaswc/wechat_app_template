if (typeof wx === 'undefined') var wx = getApp().core;
Page({
    data: {
        num:0,
        args: false,
        page: 1,
        load: false,
    },

    onLoad (options) {
        getApp().page.onLoad(this, options);
        if(!options){
            return;
        };
       
        var self = this; 
        self.setData(options);
        getApp().core.showLoading({
            title: '加载中',
        });
        getApp().request({
            url: getApp().api.lottery.lucky_code,
            data: {
                id:options.id,
            },
            success: function (res) {
                if(res.code == 0){
                    self.setData(res.data);
                    var list = res.data;
                }
            },
            complete: function(res){
                getApp().core.hideLoading();
            }
        });
    },

    onShow(){
        getApp().page.onShow(this);
    },


    // 上拉加载数据
    userload: function() {
        let self = this;
        if (self.data.args || self.data.load) return;
        self.setData({
            load: true,
        });

        getApp().core.showLoading({
            title: '加载中',
        });
        var page = self.data.page + 1;
        getApp().request({
            url: getApp().api.lottery.lucky_code,
            data: {
                id:self.data.id,
                page: page,
            },
            success: function (res) {
                if (res.code == 0) {
                    if (res.data.parent == null || res.data.parent.length == 0) {
                        self.setData({
                            args : true,
                        });
                        return;
                    }
                    self.setData({
                        parent: self.data.parent.concat(res.data.parent),
                        page: page,
                    });
                } else {
                    self.showToast({
                        title: res.msg,
                    });
                }
            },
            complete: function () {
                getApp().core.hideLoading();
                self.setData({
                    load: false
                })
            }
        });
    },
})

