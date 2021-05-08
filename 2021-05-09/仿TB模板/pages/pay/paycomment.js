var app = getApp()
Page({
    data: {
        stars: [0, 1, 2, 3, 4, 5],
        img_url: 'http://appuat.huihuishenghuo.com/img/',
        normalSrc: 'http://appuat.huihuishenghuo.com/img/order/star.png',
        selectedSrc: 'http://appuat.huihuishenghuo.com/img/order/stars.png',
        key: 0,//评分
    },
    onLoad: function () {
    },
    selectStar: function (e) {
        var key = e.currentTarget.dataset.key
        this.setData({
            key: key
        })
    }
})