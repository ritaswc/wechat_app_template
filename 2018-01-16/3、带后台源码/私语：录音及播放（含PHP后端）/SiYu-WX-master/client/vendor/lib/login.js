var utils = require('./utils');
var Session = require('./session');

/***
 * @class
 * 表示登录过程中发生的异常
 */
var LoginError = (function () {
    function LoginError(message) {
        Error.call(this, message);
        this.message = message;
    }

    LoginError.prototype = new Error();
    LoginError.prototype.constructor = LoginError;

    return LoginError;
})();

/**
 * 微信登录，获取 code 和 encryptData
 */
var getWxLoginResult = function getLoginCode(callback) {
    wx.login({
        success: function (loginResult) {
            wx.getUserInfo({
                success: function (userResult) {
                    callback(null, {
                        code: loginResult.code,
                        signature: userResult.signature,
                        rawData: userResult.rawData,
                        userInfo: userResult.userInfo,
                    });
                },

                fail: function (userError) {
                    var error = new LoginError('获取微信用户信息失败，请检查网络状态');
                    error.detail = userError;
                    callback(error, null);
                },
            });
        },

        fail: function (loginError) {
            var error = new LoginError('微信登录失败，请检查网络状态');
            error.detail = loginError;
            callback(error, null);
        },
    });
};

var noop = function noop() {};
var defaultOptions = {
    method: 'POST',
    success: noop,
    fail: noop,
    loginUrl: null,
};

/**
 * @method
 * 进行服务器登录，以获得登录会话
 *
 * @param {Object} options 登录配置
 * @param {string} options.loginUrl 登录使用的 URL，服务器应该在这个 URL 上处理登录请求
 * @param {string} [options.method] 请求使用的 HTTP 方法，默认为 "POST"
 * @param {Function} options.success(uinfo) 登录成功后的回调函数，参数 userInfo 微信用户信息
 * @param {Function} options.fail(error) 登录失败后的回调函数，参数 error 错误信息
 */
var login = function login(options) {
    options = utils.extend({}, defaultOptions, options);

    if (!defaultOptions.loginUrl) {
        options.fail(new LoginError('登录错误：缺少登录地址，请通过 setLoginUrl() 方法设置登录地址'));
        return;
    }

    var doLogin = () => getWxLoginResult(function (wxLoginError, wxLoginResult) {
        if (wxLoginError) {
            options.fail(wxLoginError);
            return;
        }

        var userInfo    = wxLoginResult.userInfo;
        var code        = wxLoginResult.code;
        var signature   = wxLoginResult.signature;
        var rawData     = wxLoginResult.rawData;

        // 请求服务器登录地址，获得会话信息
        wx.request({
            url: options.loginUrl,
            method: options.method,
            data: {
                code: code,
                raw_data: rawData,
                signature: signature,
            },

            success: function (result) {
                console.log(result)
                var data = result.data;

                // 成功地响应会话信息
                if (data) {
                    if (data.success) {
                        var session = {
                            session: data.session,
                            uinfo: userInfo
                        };
                        Session.set(session);
                        options.success(userInfo);
                    } else {
                        var errorMessage = '登录失败:' + data.msg || '未知错误';
                        var noSessionError = new LoginError(errorMessage);
                        options.fail(noSessionError);
                    }

                // 没有正确响应会话信息
                } else {
                    var errorMessage = '登录请求没有包含会话响应，请确保服务器处理 `' + options.loginUrl + '` 的时候输出登录结果';
                    var noSessionError = new LoginError(errorMessage);
                    options.fail(noSessionError);
                }
            },

            // 响应错误
            fail: function (loginResponseError) {
                var error = new LoginError( '登录失败，可能是网络错误或者服务器发生异常');
                options.fail(error);
            },
        });
    });

    // var session = Session.get();
    // if (session) {
    //     wx.checkSession({
    //         success: function () {
    //             options.success(session);
    //         },

    //         fail: function () {
    //             Session.clear();
    //             doLogin();
    //         },
    //     });
    // } else {
    //     doLogin();
    // }
    
    doLogin();
};

var setLoginUrl = function (loginUrl) {
    defaultOptions.loginUrl = loginUrl;
};

module.exports = {
    LoginError: LoginError,
    login: login,
    setLoginUrl: setLoginUrl,
};
