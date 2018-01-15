var api = require("../../utils/api.js")
var util = require("../../utils/util.js")

var app = getApp()
Page({
    data: {
    },
    onLoad: function (options) {
        // 页面初始化 options为页面跳转所带来的参数
        this.setData({ "keyWord": "试试" })
        this.init();
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

    init: function () {
        let that = this;
        app.getInit(function (result) {
            var tmpFile = result.obj.tmpFile;
            var minisId = result.obj._Minisns.Id;
            var unionid = result.obj._LookUser.unionid;
            var verifyModel = util.primaryLoginArgs(unionid);
            // 设置全局数据
            that.setData({ "user": result.obj._LookUser, "minisns": result.obj._Minisns, "tmpFile": tmpFile })
        })

        let keyWrod = that.data.keyWord;
        that.searchHandler(keyWrod);
    },

    /**
     * 搜索
     */
    search: function (e) {
        let keyWord = e.detail.value.keyWord
        this.searchHandler(keyWord)
    },
    searchHandler: function (keyWrod) {
        let that = this
        var tmpFile = that.data.tmpFile;
        var minisId = that.data.minisns.Id;
        var unionid = that.data.user.unionid;
        var verifyModel = util.primaryLoginArgs(unionid);
        let data = {
            "deviceType": verifyModel.deviceType, "timestamp": verifyModel.timestamp,
            "uid": unionid, "versionCode": verifyModel.versionCode, "sign": verifyModel.sign,
            "id": minisId, "keyWord": keyWrod, "pageIndex": 1
        }
        that.setData({ "loading": true })
        api.getartlistbykeyword(data, tmpFile,
            {
                "success": function (result) {
                    // 评论倒序
                    let articles = result.objArray
                    for (let i = 0; i < articles.length; i++) {
                        if (articles[i].articleComments) {
                            articles[i].articleComments = articles[i].articleComments.reverse();
                        }
                    }
                    that.setData({ "articles": articles })
                },
                complete: function () { // 关闭加载
                    that.setData({ "loading": false })
                }
            })
    },

    /**
     * 展示大图
     */
    showBigImg: function (e) { // 展示大图
        var src = e.currentTarget.dataset.src;
        wx.previewImage({
            current: src, // 当前显示图片的链接，不填则默认为 urls 的第一张
            urls: [src],
        })
        return false;
    },

    /**
     * 跳转到用户信息
     */
    navicateToUser: function (e) {
        let uid = e.currentTarget.dataset.id;
        wx.navigateTo({
            url: '/pages/user/user?uid=' + uid,
        })
    },

    /**
     * 播放声音 
     */
    playAudio: function (event) {
        let vid = event.currentTarget.dataset.vId;
        let vSrc = event.currentTarget.dataset.vSrc;
        util.playVoice(vid, vSrc)
    },

})