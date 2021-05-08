const app = getApp();

Page({
    data: {
        userInfo: {},
        winWidth: 0,
        winHeight: 0,
    },
    onLoad: function() {
        wx.pro.getSystemInfo().then((res) => {
            this.setData({
                winWidth: res.windowWidth,
                winHeight: res.windowHeight
            });
        }).catch((err) => {
            console.log(err);
        });
        wx.pro.getUserInfo().then((res) => {
            console.log(res);
            this.setData({
                userInfo: res.userInfo
            });
        }).catch((err) => {
            console.log(err);
        })
    }
})
