var app = getApp();
Page({
    data: {
        img_url: 'http://appuat.huihuishenghuo.com/img/',
        type: ''
    },
    onLoad: function (e) {
        this.setData({
            type: e.type
        })
    },
    onShow: function () {
        var type = this.data.type;
        if (type == '1') {
            wx.setNavigationBarTitle({
                title: '汇汇钱包'
            });
        } else {
            wx.setNavigationBarTitle({
                title: '我的汇币'
            });
        }
    }
});

