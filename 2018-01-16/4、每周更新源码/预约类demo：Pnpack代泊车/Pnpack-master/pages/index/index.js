Page({
    data: {
        order_data: [],

        pnPark: [
            {
                page: "park",
                img: "../../images/park.png",
                title: "我要代泊",
            },
            {
                page: "trip",
                img: "../../images/history.png",
                title: "历史行程",
            },
            {
                page: "car",
                img: "../../images/park.png",
                title: "我的车辆",
            },
            {
                page: "personal",
                img: "../../images/car.png",
                title: "个人设置",
            },
        ]
    },
    onShow: function () {
        var vm = this;
        wx.login({
            success: function (res) {
                //console.log(res);
                if (res.code) {
                    wx.request({
                        url: 'https://mini.iwocar.com/',
                        data: {
                            code: res.code,
                            name: 'papark'
                        },
                        method: 'POST',
                        header: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        success: function (item) {
                            //console.log(item);
                            wx.setStorage({
                                key: "openid",
                                data: item.data.openid
                            });
                            wx.getUserInfo({
                                success: function (msg) {
                                    wx.setStorage({
                                        key: "avatarUrl",
                                        data: msg.userInfo.avatarUrl
                                    });
                                    wx.setStorage({
                                        key: "nickName",
                                        data: msg.userInfo.nickName
                                    });
                                    wx.setStorage({
                                        key: "gender",
                                        data: msg.userInfo.gender
                                    });
                                    wx.request({
                                        url: 'https://wx.viparker.com/valetparking/api/web/index.php/user/is-new-user',
                                        data: {
                                            openid: wx.getStorageSync('openid')
                                        },
                                        header: {
                                            "Content-Type": "application/x-www-form-urlencoded"
                                        },
                                        method: "POST",
                                        success: function (res) {
                                            //console.log(res.data.message);
                                            if (res.data.message == 'new') {
                                                wx.navigateTo({
                                                    url: '../../pages/login/login'
                                                })
                                            }
                                        }
                                    });
                                }
                            })
                        }
                    })
                } else {
                    //console.log('获取用户登录态失败！' + res.errMsg)
                }
            }
        });

        wx.request({
            url: 'https://wx.viparker.com/valetparking/api/web/index.php/index/progressing-order',
            header: {//请求头
                "Content-Type": "application/x-www-form-urlencoded"
            },
            data: {
                openid: wx.getStorageSync('openid'),
            },
            method: "POST",//get为默认方法/POST
            success: function (res) {
                vm.setData({
                    order_data: res.data.data,
                })
            }
        })

    },
    //事件处理函数
    bindViewTapPark: function (e) {
        var page = e.currentTarget.dataset.page;
        wx.navigateTo({
            url: '../' + page + '/' + page
        })
    },
    gotoOrder: function (e) {
        var id = e.currentTarget.dataset.id;
        var ordercode = e.currentTarget.dataset.ordercode;
        wx.navigateTo({
            url: '../trip/order/order?o_id=' + id + '&o_ordercode=' + ordercode
        })
    },
    onPullDownRefresh: function () {
        wx.redirectTo({
            url: '/pages/index/index'
        })
    }
})