const app = getApp()

const {
    searchCourse,
} = require('../../utils/api');

Page({
    data: {
        imageRoot: app.globalData.imageRoot,
        winWidth: 0,
        winHeight: 0,
        searchList: [],
        noResults: false
    },
    onLoad: function(option) {
        const that = this;

        const {
            name,
            typeid
        } = option;
        if (name) {
            wx.pro.request({
                url: searchCourse,
                data: {
                    name
                },
                toast: true
            }).then((data) => {
                if (data.length <= 0) {
                    that.setData({
                        noResults: true
                    });

                } else {
                    that.setData({
                        searchList: data,
                        noResults: false
                    });
                }
            }).catch((err) => {
                console.log(err);
            });
        }

        if (typeid) {
            wx.pro.request({
                url: searchCourse,
                data: {
                    typeid
                },
                toast: true
            }).then((data) => {
                if (data.length <= 0) {
                    that.setData({
                        noResults: true
                    });

                } else {
                    that.setData({
                        searchList: data,
                        noResults: false
                    });
                }
            }).catch((err) => {
                console.log(err);
            });
        };
        wx.pro.getSystemInfo().then((res) => {
            this.setData({
                winWidth: res.windowWidth,
                winHeight: res.windowHeight,
            });
        }).catch((err) => {
            console.log(err);
        });
    }
})
