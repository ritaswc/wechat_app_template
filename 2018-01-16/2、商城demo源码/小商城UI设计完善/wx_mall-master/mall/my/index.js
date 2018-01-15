//获取应用实例

var app = getApp();
Page({
    data: {
        userInfo : {}
    },
    onLoad: function () {
        var _this = this;
        app.getUserInfo(function(userInfo){

            _this.setData({
                userInfo : userInfo
            })

        })

    }
});
