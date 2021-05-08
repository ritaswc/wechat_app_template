var crypt = require("./utils/crypt.js");
var util = require("./utils/util.js");
var Promise = require("./utils/bluebird.js")
var api = require("./utils/api.js")
//app.js
var that;
var inited = false;// 初始化过程
var fid = 3;
App({
    onLaunch: function () {
        that = this;
        //调用API从本地缓存中获取数据
        // this.getTypes();
        // this.login();
        // this.getUserInfo();
        // this.init();
    },

    /**
     * 获取初始数据
     */
    getInit: function (cb) {
        var that = this;
        var init = wx.getStorageSync('init');
        if (typeof init == 'undefined' || init == '') {
            that.init(cb);
        } else {
            cb(init);
        }
    },

    /**
     * 初始化数据
     */
    init: function (cb) {

        api.wxApi(wx.login)({})
            .then(function (loginRes) {
                return "https://api.weixin.qq.com/sns/jscode2session?" + "appid=wx61575c2a72a69def&secret=442cc056f5824255611bef6d3afe8d33&" +
                    "js_code=" + loginRes.code + "&grant_type=authorization_code";
            })
            .then(function (url) {
                return api.wxApi(wx.request)({ "url": url, "method": "GET" })
                    .then(function (sessionRes) {
                        return sessionRes
                    })
            })
            .then(function (sessionRes) {
                return api.wxApi(wx.getUserInfo)({})
                    .then(function (userInfoRes) {
                        var str = crypt.decryptUserInfo(userInfoRes.encryptedData, sessionRes.data.session_key, userInfoRes.iv); // 解密用户信息
                        var userInfo = JSON.parse(str);
                        console.log("获取用户信息成功", userInfo)
                        wx.setStorageSync('userInfo', userInfo)
                        return userInfo
                    })
            })
            .then(function (userInfo) {
                return api.wxApi(wx.downloadFile)({ "url": "https://snsapi.vzan.com/images/vzan.jpg", "type": "image" })
                    .then(function (res) {
                        console.log("下载文件成功")
                        return res.tempFilePath
                    })
            })
            .then(function (tmpFile) {
                return api.wxApi(wx.saveFile)({ "tempFilePath": tmpFile })
                    .then(function (res) {
                        console.log("保存下载文件到本地成功")
                        wx.setStorageSync('tmpFile', res.savedFilePath);
                        return res.savedFilePath
                    })
            })
            .then(function (tmpFile) {
                let verifyModel = util.primaryLoginArgs(wx.getStorageSync('userInfo').unionId);
                api.wxApi(wx.uploadFile)({
                    "url": "https://snsapi.vzan.com/minisnsapp/userinfo", "filePath": tmpFile, "name": "file",
                    "formData": {
                        "fid": fid, "deviceType": verifyModel.deviceType, "uid": verifyModel.uid,
                        "sign": verifyModel.sign, "timestamp": verifyModel.timestamp, "versionCode": verifyModel.versionCode
                    }
                })
                    .then(function (res) {
                        console.log("APP.js 初始化", res)
                        var result = JSON.parse(res.data);
                        wx.setStorageSync('user', result.obj._LookUser);
                        wx.setStorageSync('minisns', result.obj._Minisns);
                        wx.setStorageSync('myArtCount', result.obj._MyArtCount);
                        wx.setStorageSync('myMinisnsCount', result.obj._MyMinisnsCount);
                        wx.setStorageSync('concernCount', result.obj.ConcernCount);
                        wx.setStorageSync('myConcernCount', result.obj.MyConcernCount);
                        result.obj.tmpFile = wx.getStorageSync('tmpFile');
                        wx.setStorageSync('init', result);
                        console.log("APP.JS loginRes ", res);
                        if (typeof cb == "function") {
                            cb(result);
                        }
                    })
            })



        // wx.login({
        //     success: function (loginRes) {
        //         var url = "https://api.weixin.qq.com/sns/jscode2session?" + "appid=wx61575c2a72a69def&secret=442cc056f5824255611bef6d3afe8d33&" +
        //             "js_code=" + loginRes.code + "&grant_type=authorization_code";
        //         wx.request({
        //             url: url,
        //             method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        //             success: function (sessionRes) {
        //                 wx.getUserInfo({
        //                     success: function (userInfoRes) {
        //                         var str = crypt.decryptUserInfo(userInfoRes.encryptedData, sessionRes.data.session_key, userInfoRes.iv); // 解密用户信息
        //                         var userInfo = JSON.parse(str);
        //                         var verifyModel = util.primaryLoginArgs(userInfo.unionId);
        //                         console.log("获取用户信息成功", userInfo)
        //                         // 登陆服务器
        //                         // wx.request({
        //                         //     url: 'https://snsapi.vzan.com/minisnsapp/loginByWeiXin',
        //                         //     data: {
        //                         //         "deviceType": verifyModel.deviceType, "uid": verifyModel.uid,
        //                         //         "sign": verifyModel.sign, "timestamp": verifyModel.timestamp, "versionCode": verifyModel.versionCode,
        //                         //         "openid": userInfo.openId, "nickname": userInfo.nickName, "sex": userInfo.gender, "province": userInfo.province,
        //                         //         "city": userInfo.city, "country": userInfo.country, "headimgurl": userInfo.avatarUrl, "unionid": userInfo.unionId
        //                         //     },
        //                         //     method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        //                         //     header: { "content-type": "multipart/form-data;charset=UTF-8" }, // 设置请求的 header
        //                         //     success: function (res) {
        //                         //         console.log("loginByWeiXin", res);
        //                         //     }
        //                         // })

        //                         // var formdata = new FormData();
        //                         // formdata.append("fid",fid); formdata.append("deviceType",verifyModel.deviceType);
        //                         // formdata.append("uid", verifyModel.uid); formdata.append("sign", verifyModel.sign);
        //                         // formdata.append("timestamp",verifyModel.timestamp); formdata.append("versionCode", verifyModel.versionCode);


        //                         wx.downloadFile({
        //                             url: "https://snsapi.vzan.com/images/vzan.jpg",
        //                             type: 'image', // 下载资源的类型，用于客户端识别处理，有效值：image/audio/video
        //                             // header: {}, // 设置请求的 header
        //                             success: function (res) {
        //                                 console.log("下载文件", res);
        //                                 wx.saveFile({
        //                                     tempFilePath: res.tempFilePath,
        //                                     success: function (save) {
        //                                         console.log("SaveFile");
        //                                         wx.setStorageSync('tmpFile', save.savedFilePath);
        //                                         wx.uploadFile({ // 获取userInfo
        //                                             url: 'https://snsapi.vzan.com/minisnsapp/userinfo',
        //                                             filePath: wx.getStorageSync('tmpFile'),
        //                                             name: 'file',
        //                                             // header: {}, // 设置请求的 header
        //                                             formData: {
        //                                                 "fid": fid, "deviceType": verifyModel.deviceType, "uid": verifyModel.uid,
        //                                                 "sign": verifyModel.sign, "timestamp": verifyModel.timestamp, "versionCode": verifyModel.versionCode
        //                                             }, // HTTP 请求中其他额外的 form data
        //                                             success: function (res) {
        //                                                 console.log("APP.js 初始化", res)
        //                                                 var result = JSON.parse(res.data);
        //                                                 wx.setStorageSync('user', result.obj._LookUser);
        //                                                 wx.setStorageSync('minisns', result.obj._Minisns);
        //                                                 wx.setStorageSync('myArtCount', result.obj._MyArtCount);
        //                                                 wx.setStorageSync('myMinisnsCount', result.obj._MyMinisnsCount);
        //                                                 wx.setStorageSync('concernCount', result.obj.ConcernCount);
        //                                                 wx.setStorageSync('myConcernCount', result.obj.MyConcernCount);
        //                                                 result.obj.tmpFile = save.savedFilePath;
        //                                                 wx.setStorageSync('init', result);
        //                                                 console.log("APP.JS loginRes ", res);
        //                                                 if (typeof cb == "function") {
        //                                                     cb(result);
        //                                                 }
        //                                             }
        //                                         })
        //                                     }
        //                                 })
        //                             }
        //                         })

        //                     }
        //                 })
        //             }
        //         })
        //     },
        //     complete: function () {
        //         console.log("APP.js wx.login 初始化数据结束");
        //         inited = true;
        //         // 模拟数据
        //         // wx.setStorageSync('minisnsInfo', {"id":3});
        //         // wx.setStorageSync('userInfo', {"unionid":"oW2wBwaOWQ2A7RLzG3fcpmfTgnPU","Openid":"obiMY0YuQSpXSAY21oWjKw-OJC0E",
        //         // "IsSign":true,"ArticleCount":20,"CommentCount":12,"PraiseCount":1231,"IsWholeAdmin":false})
        //     }
        // })
    },

})