
var app = getApp()
var event = require('../../../utils/event')
Page({
    data: {
        position: '',
        recent: [],
        li: [],
        scrollId: ''
    },
    onLoad: function (prama) {
        wx.setNavigationBarTitle({
            title: '当前城市-' + prama.city
        })
        this.setData({
            position: app.globalData.positionCity,
            recent: wx.getStorageSync('recity') || []
        })

        var that = this
        wx.request({
            url: 'http://localhost:8888/api/positionlist',
            success: function (res) {
                console.log(res.data.li)
                that.setData({
                    li: res.data.li,
                })
            }
        })
    },
    selcity: function (ev) {
        console.log(ev);
        var tar = ev.target.dataset.set;
        if (tar) {

            var recity = wx.getStorageSync('recity') || []
            if (recity[0] != tar) { recity.unshift(tar) }
            var nowrecity = recity.slice(0, 2)
            wx.setStorageSync('recity', nowrecity)
            wx.navigateBack();
        }
    },

    scrollto: function (ev) {
        console.log(ev)
        var tar = ev.target.dataset.to
        if (tar) {
            this.setData({
                scrollId: tar
            })
        }
    }
})