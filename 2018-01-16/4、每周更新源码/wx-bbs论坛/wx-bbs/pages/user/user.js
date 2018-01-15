var index = require("../../data/index-list.js")
var util = require("../../utils/util.js")
var api = require("../../utils/api.js")

var app = getApp();
Page({
    data: {
    },
    onLoad: function (options) {
        // 页面初始化 options为页面跳转所带来的参数
        var that = this;
        that.setData({ "uid": options.uid })
        that.init();
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
    },
    //初始化信息
    init: function () {
        let that = this;
        app.getInit(function (result) {
            that.setData({
                "user": result.obj._LookUser,
                "minisns": result.obj._Minisns,
                "tmpFile": result.obj.tmpFile
            })
            var tmpFile = result.obj.tmpFile;
            var minisId = result.obj._Minisns.Id;
            var unionid = result.obj._LookUser.unionid;
            var verifyModel = util.primaryLoginArgs(unionid);
            wx.uploadFile({
                url: 'https://snsapi.vzan.com/minisnsapp/userinfo',
                filePath: that.data.tmpFile,
                name: 'file',
                // header: {}, // 设置请求的 header
                formData: {
                    "id": that.data.uid, "fid": minisId, "deviceType": verifyModel.deviceType, "uid": verifyModel.uid,
                    "sign": verifyModel.sign, "timestamp": verifyModel.timestamp, "versionCode": verifyModel.versionCode
                }, // HTTP 请求中其他额外的 form data
                success: function (res) {
                    let result = JSON.parse(res.data)
                    if (result.result == true) {
                        that.setData({ "targetUser": result.obj._User })
                    }
                }
            })
        })

    },
    /**
     * 跳转发帖列表
     */
    myArticleList: function () {
        let that = this;
        wx.navigateTo({
            url: '/pages/mypost/mypost?uid=' + that.data.uid,
            complete: function () {
                console.log("跳转到我的发帖");
            }
        })
    }
})