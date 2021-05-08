var base = getApp();
Page({
    data: {
        userInfo: {},
        jzb: "0.00",
        loaded: false,
        exp: 0,
        phone: "",
        levels: 0,
        headimg: "https://m.bestcake.com/images/icon_user.jpg"
    },
    exist: function () {
        base.user.userid = 0;
        base.user.sessionid = "";
        base.user.clear();     
        wx.redirectTo({
            url: '../phone/phone'
        });
    },
    onReady: function () {
        // 页面渲染完成

    },
    tomyorder: function () {
        wx.navigateTo({
            url: '../user/myorder/myorder'
        })
    },
    tomyaddress: function () {
        wx.navigateTo({
            url: '../user/myaddress/myaddress'
        })
    },
    changeimg: function () {//更改头像
        var _this = this;
        wx.showModal({
            title: '',
            content: '确定要更换头像？',
            success: function (res) {
                if (res.confirm) {
                    _this.up();
                }
            }
        })
    },
    up: function () {
        var _this = this;
        wx.chooseImage({
            success: function (res) {
                var tempFilePaths = res.tempFilePaths
                wx.uploadFile({
                    url: base.path.www+"upload.ashx", //仅为示例，非真实的接口地址
                    filePath: tempFilePaths[0],
                    name: 'file',
                    formData: {
                        'user': 'test'
                    },
                    success: function (res) {
                        var data = JSON.parse(res.data);                       
                        data.Tag += '?v='+Math.random();
                        base.user.headimg = data.Tag;
                       //缓存数据更新
                       var objuser = {};
                        objuser.userid = base.user.userid;
                        objuser.sessionid = base.user.sessionid;
                        objuser.jzb = base.user.jzb;
                        objuser.exp = base.user.exp;
                        objuser.phone = base.user.phone;
                        objuser.levels = base.user.levels;
                        objuser.headimg = data.Tag;
                        base.user.setCache(objuser);
                        _this.setData({
                            headimg: data.Tag
                        });
                        if (data.Status == "ok") {
                            base.get({ c: "UserCenter", m: "UpdateMemberHeadImage", imgurl: data.Tag }, function (d) { 
                                var d = d.data;if (d.Status == "ok") {}});
                        }
                        else {
                            wx.showModal({
                                showCancel: false,
                                title: '',
                                content: data.Msg
                            })
                        }
                    },
                    faile: function (res) {
                        var data = JSON.parse(res.data);
                        wx.showModal({
                            showCancel: false,
                            title: '',
                            content: data.Msg
                        })
                    }
                })
            }
        })
    },
    onLoad: function () {
        var that = this
        //调用应用实例的方法获取全局数据
        // base.getUserInfo(function (userInfo) {
        //     //更新数据
        //     that.setData({
        //         userInfo: userInfo
        //     })
        // })        
        if (!base.user.islogin()) {
            wx.redirectTo({
                // url: '../login/login'
                url: '../phone/phone'
            });
        }
        else {
            this.setData({
                loaded: true,
                jzb: base.user.jzb,
                exp: base.user.exp,
                phone: base.user.phone,
                levels: base.user.levels,
                headimg: base.user.headimg

            });
        }




    }
});