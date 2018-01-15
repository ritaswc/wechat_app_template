// pages/goodsearch/goodshop.js

var app = getApp();
Page({
    data: {
        systemInfo: [],
        loadingHidden: false,
        list: [],
        num: 1,
    },
    bindscrolltoupper: function () {
        console.log("到顶部");
        // this.setData({
        //     list: [],
        //     num: 1,
        // })
        // this.requestData();
    },
    bindscrolltolower: function () {

    },
    scroll: function (e) {
        console.log(e);
    },
    clickitem: function (e) {   //带着specId 去详情界面
        var specId = e.currentTarget.dataset.specid;
        wx.navigateTo({
            url: '../goodsDetail/goodsDetail?specId=' + specId,
        })
    },
    onLoad: function (options) {
        // 页面初始化 options为页面跳转所带来的参数
        var that = this;
        app.getSystemInfo(function (res) {
            that.setData({
                systemInfo: res,
            })
        })
        this.requestData(options);
    },
    requestData: function (o) {
        console.log(o.id);
        var that = this;
        wx.request({
            url: 'https://www.leimingtech.com/front/goods/api/goodslist',
            data: {
                keyword:o.id,
                searchType:'keywordSearch',
                //pageNo:this.data.num,
            },
            header: {
                'Content-Type': 'application/json'
            },
            success: function (res) {
                console.log(res.data)
                that.setData({
                    loadingHidden: true,
                    list: that.data.list.concat(res.data.data)
                })
            }
        })
    },
    onReady: function () {
        // 页面渲染完成
    },
    onShow: function () {
        // 页面显示
    },
    onHide: function () {
        // 页面隐藏
    },
    onUnload: function () {
        // 页面关闭
    }
})