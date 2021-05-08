if (typeof wx === 'undefined') var wx = getApp().core;
var is_more = false;
Page({

    /**
    * 页面的初始数据
    */
    data: {
        show: false
    },

    /**
    * 生命周期函数--监听页面加载
    */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
    },

    getData: function () {
        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        });
        getApp().request({
            url: getApp().api.recharge.record,
            data: {
                date: self.data.date_1 || ''
            },
            success: function (res) {
                self.setData({
                    list: res.data.list,
                });
                getApp().core.hideLoading();
                is_more = false;
            }
        });
    },

    /**
    * 生命周期函数--监听页面初次渲染完成
    */
    onReady: function () {
        getApp().page.onReady(this);
    },

    /**
    * 生命周期函数--监听页面显示
    */
    onShow: function () {
        getApp().page.onShow(this);

        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        });
        var user_info = getApp().core.getStorageSync(getApp().const.USER_INFO);
        getApp().request({
            url: getApp().api.recharge.index,
            success: function (res) {
                user_info.money = res.data.money;
                getApp().core.setStorageSync(getApp().const.USER_INFO,user_info);
                self.setData({
                    user_info: user_info,
                    list: res.data.list,
                    setting: res.data.setting,
                    date_1: res.data.date,
                    date: res.data.date.replace('-', '年') + '月'
                });
                getApp().core.hideLoading();
            }
        });
    },


    dateChange: function (e) {
        if (is_more) {
            return;
        }
        is_more = true;
        var date_1 = e.detail.value;
        var date = date_1.replace('-', '年') + '月';
        this.setData({
            date: date,
            date_1: date_1
        });
        this.getData();
    },

    dateUp: function () {
        var self = this;
        if (is_more) {
            return;
        }
        is_more = true;
        var date_1 = self.data.date_1;
        var date = self.data.date;
        var d = new Date(date_1);
        d.setMonth(d.getMonth() + 1);
        var m = d.getMonth() + 1;
        m = m.toString();
        m = m[1] ? m : '0' + m;
        self.setData({
            date: d.getFullYear() + '年' + m + '月',
            date_1: d.getFullYear() + '-' + m
        });
        self.getData();
    },

    dateDown: function () {
        var self = this;
        if (is_more) {
            return;
        }
        is_more = true;
        var date_1 = self.data.date_1;
        var date = self.data.date;
        var d = new Date(date_1);
        d.setMonth(d.getMonth() - 1);
        var m = d.getMonth() + 1;
        m = m.toString();
        m = m[1] ? m : '0' + m;
        self.setData({
            date: d.getFullYear() + '年' + m + '月',
            date_1: d.getFullYear() + '-' + m
        });
        self.getData();
    },

    click: function () {
        this.setData({
            show: true
        });
    },
    close: function () {
        this.setData({
            show: false
        });
    },
    GoToDetail: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var list = self.data.list;
        var order = list[index];
        getApp().core.navigateTo({
            url: '/pages/balance/detail?order_type=' + order.order_type + '&id=' + order.id,
        })
    }
});