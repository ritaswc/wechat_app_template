var utils = require('./utils');
var Session = require('./session');
var loginLib = require('./login');

var noop = function noop() {};

/***
 * @class
 * 表示请求过程中发生的异常
 */
var RequestError = (function () {
    function RequestError(message) {
        Error.call(this, message);
        this.message = message;
    }

    RequestError.prototype = new Error();
    RequestError.prototype.constructor = RequestError;

    return RequestError;
})();

function request(options) {
    if (typeof options !== 'object') {
        var message = '请求传参应为 object 类型，但实际传了 ' + (typeof options) + ' 类型';
        throw new RequestError(message);
    }

    var requireLogin = options.login;
    var success = options.success || noop;
    var fail = options.fail || noop;
    var complete = options.complete || noop;

    // 成功回调
    var callSuccess = function () {
        success.apply(null, arguments);
        complete.apply(null, arguments);
    };

    // 失败回调
    var callFail = function (error) {
        fail.call(null, error);
        complete.call(null, error);
    };

    // 是否已经进行过重试
    var hasRetried = false;

    if (requireLogin) {
        doRequestWithLogin();
    } else {
        doRequest();
    }

    // 登录后再请求
    function doRequestWithLogin() {
        loginLib.login({ success: doRequest, fail: callFail });
    }

    // 实际进行请求的方法
    function doRequest() {

        wx.request(utils.extend({}, options, {

            success: function (response) {
                var data = response.data;
                if (data) {
                    if (!data.success){
                        if (!hasRetried) {
                            hasRetried = true;
                            doRequestWithLogin();
                            return;
                        }
                        // 清除登录态
                        message = data.msg || '未知错误';
                        var error = new RequestError(message);
                        callFail(error);
                    }else{
                        callSuccess.apply(null, arguments);
                        return;
                    }
                }else{
                    var errorMessage = '请求没有包含会话响应，请确保服务器处理 `' + options.url + '` 的时候输出登录结果';
                    var noSessionError = new RequestError(errorMessage);
                    options.fail(noSessionError);
                }
            },

            fail: callFail,
            complete: noop,
        }));
    };

};

module.exports = {
    RequestError: RequestError,
    request: request,
};