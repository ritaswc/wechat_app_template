/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author LuWei
 */

var hj = null;
if (typeof wx !== "undefined") {
    // 微信小程序
    hj = wx;
} else if (typeof swan !== "undefined") {
    hj = swan;
} else {
    // 支付宝小城
    hj = my;

    var getSystemInfoSync = my.getSystemInfoSync;
    hj.getSystemInfoSync = function () {
        var data = getSystemInfoSync();
        return data;
    };

    var setStorageSync = my.setStorageSync;
    hj.setStorageSync = function (key, value) {
        return setStorageSync({
            key: key,
            data: value,
        });
    };

    var getStorageSync = my.getStorageSync;
    hj.getStorageSync = function (key) {
        var res = getStorageSync({key: key});
        if (res)
            return res.data;
        return res;
    };

    var removeStorageSync = my.removeStorageSync;
    hj.removeStorageSync = function (key) {
        removeStorageSync({
            key: key,
        });
    };

    hj.request = function (args) {
        if (args.method.toLowerCase() == 'get' && args.data) {
            var params = getApp().helper.objectToUrlParams(args.data, true);
            args.url += '&' + params;
            args.data = null;
        }
        my.httpRequest(args);
    };

    hj.setNavigationBarColor = function (args) {
        return;// 与setNavigationBarTitle冲突先不执行
        if (!args.backgroundColor)
            return;
        my.setNavigationBar({
            backgroundColor: args.backgroundColor,
        });
    };

    hj.setNavigationBarTitle = function (args) {
        if (!args.title)
            return;
        my.setNavigationBar({
            title: args.title,
        });
    };

    var toast = my.showToast;
    hj.showToast = function (args) {
        if (args.title) {
            return toast({
                type: 'none',
                content: args.title,
            });
        }
    };

    var previewImage = my.previewImage;
    hj.previewImage = function (args) {
        if (args.current) {
            var current = args.urls.indexOf(args.current);
            if (current == -1) {
                current = 0;
            }
            return previewImage({
                current: current,
                urls: args.urls,
            })
        } else {
            return previewImage({
                urls: args.urls,
            })
        }
    }

    var animation = my.createAnimation;
    hj.createAnimation = function (args) {
        return animation({
            duration: args.duration ? args.duration : 400,
            timeFunction: args.timingFunction ? args.timingFunction : 'linear',
            delay: args.delay ? args.delay : 0,
            transformOrigin: args.transformOrigin ? args.transformOrigin : '50% 50% 0',
        })
    }

    hj.showModal = function (args) {
        if (args.showCancel == false) {
            // alert
            my.alert({
                title: args.title,
                content: args.content,
                buttonText: args.confirmText ? args.confirmText : '确定',
                success: function (res) {
                    if (args.success) {
                        args.success({ confirm: true, cancel: false });
                    }
                },
                fail: args.fail,
                complete: args.complete,
            });
        } else {
            // confirm
            my.confirm({
                title: args.title,
                content: args.content,
                confirmButtonText: args.confirmText ? args.confirmText : '确定',
                cancelButtonText: args.cancelText ? args.cancelText : '取消',
                success: function (res) {
                    if (res.confirm) {
                        // 确认
                        args.success({confirm: true, cancel: false});
                    } else {
                        // 取消
                        args.success({confirm: false, cancel: true});
                    }
                },
                fail: args.fail,
                complete: args.complete,
            });
        }
    };

    hj.requestPayment = function (args) {
        my.tradePay({
            // orderStr: args._res.data,
            tradeNO: args._res.data.trade_no || '',
            success: function (res) {
            },
            fail: function (res) {
            },
            complete: function (res) {
                var complete_data = {};
                res.resultCode = parseInt(res.resultCode);
                switch (res.resultCode) {
                    case 9000:
                        if (typeof args.success == 'function')
                            args.success({
                                errMsg: 'requestPayment:ok',
                            });
                        complete_data['errMsg'] = "requestPayment:ok";
                        break;
                    case 6001:
                        if (typeof args.fail == 'function')
                            args.fail({
                                errMsg: 'requestPayment:fail cancel',
                            });
                        complete_data['errMsg'] = 'requestPayment:fail cancel';
                        break;
                    case 6002:
                        if (typeof args.fail == 'function')
                            args.fail({
                                errMsg: 'requestPayment:fail cancel',
                            });
                        complete_data['errMsg'] = 'requestPayment:fail cancel';
                        break;
                    default:
                        if (typeof args.fail == 'function')
                            args.fail({
                                errMsg: 'requestPayment:fail',
                            });
                        complete_data['errMsg'] = 'requestPayment:fail';
                        break;
                }
                if (typeof args.complete == 'function')
                    args.complete(complete_data);
            }
        });
    };

    hj.setClipboardData = function (args) {
        args['text'] = args.data || '';
        my.setClipboard(args);
    };

    var makePhoneCall = my.makePhoneCall;
    hj.makePhoneCall = function (args) {
        args['number'] = args.phoneNumber || '';
        makePhoneCall(args);
    };

    hj.getSetting = function (args) {

    };

    var saveImage = my.saveImage;
    hj.saveImageToPhotosAlbum = function (args) {
        saveImage({
            url: args.filePath,
            success: args.success,
            fail: function (e) {
                e.errMsg = e.errorMessage || '';
                args.fail(e)
            },
            complete: args.complete,
        })
    };

    var downloadFile = my.downloadFile;
    hj.downloadFile = function (args) {
        downloadFile({
            url: args.url,
            success: function (e) {
                args.success({tempFilePath: e.apFilePath});
            },
            fail: args.fail,
            complete: args.complete,
        })
    }

    hj.setClipboardData = function (args) {
        my.setClipboard({
            text: args.data,
            success: args.success,
            fail: args.fail,
            complete: args.complete,
        })
    };

    var chooseImage = my.chooseImage;
    hj.chooseImage = function (args) {
        chooseImage({
            success: function (e) {
                if (typeof args.success != 'function')
                    return;
                var wx_e = {
                    tempFilePaths: [],
                    tempFiles: [],
                };
                for (var i in e.apFilePaths) {
                    wx_e.tempFilePaths.push(e.apFilePaths[i]);
                    wx_e.tempFiles.push({
                        path: e.apFilePaths[i],
                    });
                }
                args.success(wx_e);
            },
            error: function (e) {
                if (typeof args.error != 'function')
                    return;
                args.error(e);
            },
            complete: function (e) {
                if (typeof args.complete != 'function')
                    return;
                args.complete(e);
            }
        });
    };

    var showActionSheet = my.showActionSheet;
    hj.showActionSheet = function (args) {
        showActionSheet({
            items: args.itemList || [],
            success: function (res) {
                if (typeof args.success !== 'function')
                    return;
                args.success({
                    tapIndex: res.index
                });
            },
        });
    };

    var uploadFile = my.uploadFile;
    hj.uploadFile = function (args) {
        args['fileName'] = args.name || '';
        args['fileType'] = args.fileType || 'image';
        uploadFile(args);
    };

}
module.exports = hj;