if (typeof wx === 'undefined') var wx = getApp().core;
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        current_year: '',
        current_month: '',
        month_scroll_x: 100000,
        year_list: [],
        daily_avg: '-',
        month_count: '-',
        up_rate: '-',
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        getApp().core.showNavigationBarLoading();
        getApp().request({
            url: getApp().api.mch.user.tongji_year_list,
            success: function (res) {
                self.setData({
                    year_list: res.data.year_list,
                    current_year: res.data.current_year,
                    current_month: res.data.current_month,
                });
                self.setMonthScroll();
                self.getMonthData();
            },
            complete: function () {
                getApp().core.hideNavigationBarLoading();
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
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {
        getApp().page.onHide(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {
        getApp().page.onUnload(this);
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {

    },

    changeMonth: function (e) {
        var self = this;
        var y_index = e.currentTarget.dataset.yearIndex;
        var m_index = e.currentTarget.dataset.monthIndex;
        for (var i in self.data.year_list) {
            if (i == y_index) {
                self.data.year_list[i].active = true;
                self.data.current_year = self.data.year_list[i].year;
            } else {
                self.data.year_list[i].active = false;
            }
            for (var j in self.data.year_list[i].month_list) {
                if (i == y_index && j == m_index) {
                    self.data.year_list[i].month_list[j].active = true;
                    self.data.current_month = self.data.year_list[i].month_list[j].month;
                } else {
                    self.data.year_list[i].month_list[j].active = false;
                }
            }
        }
        self.setData({
            year_list: self.data.year_list,
            current_year: self.data.current_year,
        });
        self.setMonthScroll();
        self.getMonthData();
    },

    setMonthScroll: function () {
        var self = this;
        var device_info = getApp().core.getSystemInfoSync();
        var item_width = device_info.screenWidth / 5;
        var left_count = 0;
        for (var i in self.data.year_list) {
            var is_active = false;
            for (var j in self.data.year_list[i].month_list) {
                if (self.data.year_list[i].month_list[j].active) {
                    is_active = true;
                    break;
                } else {
                    left_count++;
                }
            }
            if (is_active)
                break;
        }
        self.setData({
            month_scroll_x: (left_count - 0) * item_width,
        });
    },

    setCurrentYear: function () {
        var self = this;
        for (var i in self.data.year_list) {
            if (self.data.year_list[i].active) {
                self.data.current_year = self.data.year_list[i].year;
                break;
            }
        }
        self.setData({
            current_year: self.data.current_year,
        });
    },

    getMonthData: function () {
        var self = this;
        getApp().core.showNavigationBarLoading();
        self.setData({
            daily_avg: '-',
            month_count: '-',
            up_rate: '-',
        });
        getApp().request({
            url: getApp().api.mch.user.tongji_month_data,
            data: {
                year: self.data.current_year,
                month: self.data.current_month,
            },
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        daily_avg: res.data.daily_avg,
                        month_count: res.data.month_count,
                        up_rate: res.data.up_rate,
                    });
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                    });
                }
            },
            complete: function () {
                getApp().core.hideNavigationBarLoading();
            },
        });
    },

});