var hj = null;

if ("undefined" != typeof wx) hj = wx; else if ("undefined" != typeof swan) hj = swan; else {
    hj = my;
    var getSystemInfoSync = my.getSystemInfoSync;
    hj.getSystemInfoSync = function() {
        return getSystemInfoSync();
    };
    var setStorageSync = my.setStorageSync;
    hj.setStorageSync = function(e, t) {
        return setStorageSync({
            key: e,
            data: t
        });
    };
    var getStorageSync = my.getStorageSync;
    hj.getStorageSync = function(e) {
        var t = getStorageSync({
            key: e
        });
        return t ? t.data : t;
    };
    var removeStorageSync = my.removeStorageSync;
    hj.removeStorageSync = function(e) {
        removeStorageSync({
            key: e
        });
    }, hj.request = function(e) {
        if ("get" == e.method.toLowerCase() && e.data) {
            var t = getApp().helper.objectToUrlParams(e.data, !0);
            e.url += "&" + t, e.data = null;
        }
        my.httpRequest(e);
    }, hj.setNavigationBarColor = function(e) {}, hj.setNavigationBarTitle = function(e) {
        e.title && my.setNavigationBar({
            title: e.title
        });
    };
    var toast = my.showToast;
    hj.showToast = function(e) {
        if (e.title) return toast({
            type: "none",
            content: e.title
        });
    };
    var previewImage = my.previewImage;
    hj.previewImage = function(e) {
        if (e.current) {
            var t = e.urls.indexOf(e.current);
            return -1 == t && (t = 0), previewImage({
                current: t,
                urls: e.urls
            });
        }
        return previewImage({
            urls: e.urls
        });
    };
    var animation = my.createAnimation;
    hj.createAnimation = function(e) {
        return animation({
            duration: e.duration ? e.duration : 400,
            timeFunction: e.timingFunction ? e.timingFunction : "linear",
            delay: e.delay ? e.delay : 0,
            transformOrigin: e.transformOrigin ? e.transformOrigin : "50% 50% 0"
        });
    }, hj.showModal = function(t) {
        0 == t.showCancel ? my.alert({
            title: t.title,
            content: t.content,
            buttonText: t.confirmText ? t.confirmText : "确定",
            success: function(e) {
                t.success && t.success({
                    confirm: !0,
                    cancel: !1
                });
            },
            fail: t.fail,
            complete: t.complete
        }) : my.confirm({
            title: t.title,
            content: t.content,
            confirmButtonText: t.confirmText ? t.confirmText : "确定",
            cancelButtonText: t.cancelText ? t.cancelText : "取消",
            success: function(e) {
                e.confirm ? t.success({
                    confirm: !0,
                    cancel: !1
                }) : t.success({
                    confirm: !1,
                    cancel: !0
                });
            },
            fail: t.fail,
            complete: t.complete
        });
    }, hj.requestPayment = function(n) {
        my.tradePay({
            tradeNO: n._res.data.trade_no || "",
            success: function(e) {},
            fail: function(e) {},
            complete: function(e) {
                var t = {};
                switch (e.resultCode = parseInt(e.resultCode), e.resultCode) {
                  case 9e3:
                    "function" == typeof n.success && n.success({
                        errMsg: "requestPayment:ok"
                    }), t.errMsg = "requestPayment:ok";
                    break;

                  case 6001:
                  case 6002:
                    "function" == typeof n.fail && n.fail({
                        errMsg: "requestPayment:fail cancel"
                    }), t.errMsg = "requestPayment:fail cancel";
                    break;

                  default:
                    "function" == typeof n.fail && n.fail({
                        errMsg: "requestPayment:fail"
                    }), t.errMsg = "requestPayment:fail";
                }
                "function" == typeof n.complete && n.complete(t);
            }
        });
    }, hj.setClipboardData = function(e) {
        e.text = e.data || "", my.setClipboard(e);
    };
    var makePhoneCall = my.makePhoneCall;
    hj.makePhoneCall = function(e) {
        e.number = e.phoneNumber || "", makePhoneCall(e);
    }, hj.getSetting = function(e) {};
    var saveImage = my.saveImage;
    hj.saveImageToPhotosAlbum = function(t) {
        saveImage({
            url: t.filePath,
            success: t.success,
            fail: function(e) {
                e.errMsg = e.errorMessage || "", t.fail(e);
            },
            complete: t.complete
        });
    };
    var downloadFile = my.downloadFile;
    hj.downloadFile = function(t) {
        downloadFile({
            url: t.url,
            success: function(e) {
                t.success({
                    tempFilePath: e.apFilePath
                });
            },
            fail: t.fail,
            complete: t.complete
        });
    }, hj.setClipboardData = function(e) {
        my.setClipboard({
            text: e.data,
            success: e.success,
            fail: e.fail,
            complete: e.complete
        });
    };
    var chooseImage = my.chooseImage;
    hj.chooseImage = function(a) {
        chooseImage({
            success: function(e) {
                if ("function" == typeof a.success) {
                    var t = {
                        tempFilePaths: [],
                        tempFiles: []
                    };
                    for (var n in e.apFilePaths) t.tempFilePaths.push(e.apFilePaths[n]), t.tempFiles.push({
                        path: e.apFilePaths[n]
                    });
                    a.success(t);
                }
            },
            error: function(e) {
                "function" == typeof a.error && a.error(e);
            },
            complete: function(e) {
                "function" == typeof a.complete && a.complete(e);
            }
        });
    };
    var showActionSheet = my.showActionSheet;
    hj.showActionSheet = function(t) {
        showActionSheet({
            items: t.itemList || [],
            success: function(e) {
                "function" == typeof t.success && t.success({
                    tapIndex: e.index
                });
            }
        });
    };
    var uploadFile = my.uploadFile;
    hj.uploadFile = function(e) {
        e.fileName = e.name || "", e.fileType = e.fileType || "image", uploadFile(e);
    };
}

module.exports = hj;