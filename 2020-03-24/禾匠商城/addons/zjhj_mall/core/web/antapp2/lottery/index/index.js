if (typeof wx === 'undefined') var wx = getApp().core;
var is_loading = false;
var is_no_more = true;
var interval_list = false;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        p: 1,
        naver:'index'
    },
    
    onLoad (options) {
        getApp().page.onLoad(this, options);
    },

    onShow(){
        getApp().page.onShow(this);
        getApp().core.showLoading({
            title: '加载中',
        });
        var self = this;
        self.data.p = 1;
        getApp().request({
            url: getApp().api.lottery.index,
            success: function (res) {
                if(res.code == 0){
                    self.setData(res.data);                    
                    if (res.data.new_list != null && res.data.new_list.length > 0) {
                        is_no_more = false;
                    }
                    var time = [];
                    res.data.new_list.forEach(function(item,index,array){
                        time.push(item.end_time);
                    })
                    self.setTimeStart(time);

                }
            },
            complete: function (res) {
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
                    }
                }
            },
        });
    },

    onHide: function () {
        getApp().page.onHide(this);
        clearInterval(interval_list);
    },

    onUnload: function () {
        getApp().page.onUnload(this);
        clearInterval(interval_list);
    },

    setTimeStart: function (e) {
        var self  = this;
        var list = [];
        clearInterval(interval_list);
        interval_list = setInterval(function(){
            e.forEach(function(item,index,array){
                var nowTime = new Date();
                var times = parseInt((item - nowTime.getTime() / 1000))
                if(times > 0){
                    var day = Math.floor(times / (60 * 60 * 24));
                    var hour = Math.floor(times / (60 * 60)) - (day * 24);
                    var minute = Math.floor(times / 60) - (day * 24 * 60) - (hour * 60);
                    var second = Math.floor(times) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
                }

                // day<10 ? day="0"+day : day;
                // hour<10 ? hour="0"+hour : hour;
                // minute<10 ? minute="0"+minute : minute;
                // second<10 ? second="0"+second : second;
                let time_list = {
                    'day': day,
                    'hour':hour,
                    'minute':minute,
                    'second':second,
                };
                list[index] = time_list;
            })
        
            self.setData({
                time_list:list
            })
        },1000);
    },

    submit: function(e) {
        var formId = e.detail.formId;
        var lottery_id = e.currentTarget.dataset.lottery_id;
        getApp().core.navigateTo({
            url: "/lottery/detail/detail?lottery_id=" + lottery_id + "&form_id=" + formId,
        });
    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function() {
        if (is_no_more) {
            return;
        }
        this.loadData();
    },

    // 上拉加载数据
    loadData: function() {
        if (is_loading) {
            return;
        }
        is_loading = true;
        getApp().core.showLoading({
            title: '加载中',
        });
        var self = this;
        var p = self.data.p + 1;
        getApp().request({
            url: getApp().api.lottery.index,
            data: {
                page: p
            },
            success: function(res) {
                if (res.code == 0) {
                    var new_list = self.data.new_list;
                    if (res.data.new_list == null || res.data.new_list.length == 0) {
                        is_no_more = true;
                        return;
                    }
                    new_list = new_list.concat(res.data.new_list);
                    self.setData({
                        new_list:new_list,
                        p: p
                    });
                    var time = [];
                    new_list.forEach(function(item,index,array){
                        time.push(item.end_time);
                    })
                    self.setTimeStart(time);
                } else {
                    self.showToast({
                        title: res.msg,
                    });
                }
            },
            complete: function (res) {
                getApp().core.hideLoading();
                is_loading = false;
            }
        });
    },
    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var res = {
            path: "lottery/index/index?user_id=" + self.data.__user_info.id,
            success: function (e) { },
        };
        return res;
    },
})
