
var app = getApp()
var event = require('../../utils/event')
Page({
    data: {
        head: {
            currentCity: '',
            placestr: '找影视剧 影人 影院'
        },

        indexData: {},
        counts: 10,
        start: 0
    },
    onLoad: function () {
        var that = this
        
        wx.request({
            url: 'http://localhost:8888/api/index?city=' + that.data.currentCity + '&counts=' + that.data.counts + '&start=' + that.data.start,
            success: function (res) {
                console.log(res.data)
                that.setData({
                    indexData: res.data.data,
                    start: that.data.start + that.data.counts
                })
            }
        })
       
    },
    onShow: function () {
        console.log('index-onShow')
        var that = this
        app.getpol(
            function (currentCity) {
                that.setData({
                    'head.currentCity': currentCity
                })
            }
        )
    },
   
    onReachBottom: function () {
        var that = this
        wx.request({
            url: 'http://localhost:8888/api/index?city=' + that.data.currentCity + '&counts=' + that.data.counts + '&start=' + that.data.start,
            success: function (res) {
                var moviesLi = that.data.indexData.moviesList
                var nextLi = res.data.data.moviesList;
                var newLi = moviesLi.concat(nextLi)
                that.setData({
                    'indexData.moviesList': newLi,
                    start: that.data.start + that.data.counts
                })
            }
        })
    }
})
