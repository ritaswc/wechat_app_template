const app = getApp();
const {
    apiRoot,
    banner,
    qulityVideo,
    recommendedCourse
} = require('../../utils/api');


Page({
    data: {
        imageRoot: app.globalData.imageRoot,
        apiRoot,
        winWidth: 0,
        winHeight: 0,
        banners: [],
        qulityVideos: [],
        recommendedCourses: []
    },
    onLoad() {
        const that = this;
        wx.pro.request({
            url: banner,
        }).then((data) => {
            that.setData({
                banners: data.banners
            });
        }).catch((err) => {
            console.log(err);
        });
        wx.pro.request({
            url: qulityVideo,
        }).then((data) => {
            that.setData({
                qulityVideos: data.videos
            });
        }).catch((err) => {
            console.log(err);
        });
        wx.pro.request({
            url: recommendedCourse,
        }).then((data) => {
            that.setData({
                recommendedCourses: data.recommendedCourses
            });
        }).catch((err) => {
            console.log(err);
        });
        wx.pro.getSystemInfo()
            .then((res) => {
                that.setData({
                    winWidth: res.windowWidth,
                });
            }).catch((err) => {
                console.log(err);
            });
    },
    gotoCollege() {
        wx.switchTab({
            url: "../college/index"
        });
    }
})
