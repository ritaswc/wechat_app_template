
const app = getApp();

const LIMIT = 5;

const {
    getCourses
} = require('../../utils/api');


Page({
    data: {
        imageRoot: app.globalData.imageRoot,
        winWidth: 0,
        winHeight: 0,
        page: 1,
        courseList: [],
        noResults: false,
        isLoading:true,
        hasMore: false
    },
    onLoad() {
        var that = this
        const _page = that.data.page;
        that.getCoursesHandle(_page)
            .then((res) => {
                that.noData(res.courses);
                that.setData({
                    page: parseInt(res.page),
                    courseList: [...that.data.courseList, ...res.courses],
                    hasMore: that.hasMoreData(res.count, res.page)
                })
                that.setData({isLoading: false});
            }).catch((err) => {
                that.setData({isLoading: false});
                console.log(err);
            });
        wx.pro.getSystemInfo()
            .then((res) => {
                console.log(res);
                that.setData({
                    winWidth: res.windowWidth,
                    winHeight: res.windowHeight
                });
            }).catch((err) => {
                console.log(err);
            });
    },
    getCoursesHandle(page) {
        return wx.pro.request({
            url: `${getCourses}?page=${page}`,
            toast: true
        });
    },
    getMoreCouresHandle() {
        const that = this;
        const _page = that.data.page + 1;
        if (that.data.hasMore && !that.data.isLoading) {
            that.setData({
                isLoading: true,
                hasMore: false
            });
            that.getCoursesHandle(_page)
                .then((res) => {
                    that.setData({
                        page: parseInt(res.page),
                        courseList: [...that.data.courseList, ...res.courses],
                        hasMore: that.hasMoreData(res.count, res.page)
                    })
                    that.setData({isLoading: false});
                }).catch((err) => {
                    that.setData({isLoading: false});
                    console.log(err);
                })
        }
    },
    hasMoreData(count, page) {
        return parseInt(count ) > parseInt(page) * LIMIT;
    },
    noData(dataList) {
        if (dataList.length <= 0) {
            this.setData({
                noResults: true
            })
        } else {
            this.setData({
                noResults: false
            })
        }
    }
})
