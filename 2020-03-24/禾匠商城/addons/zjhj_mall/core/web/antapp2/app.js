/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author
 */

// 不要删这行注释，微擎版用的：siteInfo: require('siteinfo.js')

let platform = null;
if (typeof wx !== 'undefined') {
    platform = 'wx';
}
if (typeof my !== 'undefined') {
    platform = 'my';
}

/***
 * 加载的模块请在这里配置
 * @type {*[]}
 */
let modules = [{
        name: 'helper',
        file: './utils/helper.js',
    },
    {
        name: 'const',
        file: './core/const.js',
    },
    {
        name: 'getConfig',
        file: './core/config.js',
    },
    {
        name: 'page',
        file: './core/page.js',
    },
    {
        name: 'request',
        file: './core/request.js',
    },
    {
        name: 'core',
        file: './core/core.js',
    },
    {
        name: 'api',
        file: './core/api.js',
    },
    {
        name: 'getUser',
        file: './core/getUser.js',
    },
    {
        name: 'setUser',
        file: './core/setUser.js',
    },
    {
        name: 'login',
        file: './core/login.js',
    },
    {
        name: 'trigger',
        file: './core/trigger.js',
    },
    {
        name: 'uploader',
        file: './utils/uploader.js',
    },
    {
        name: 'orderPay',
        file: './core/order-pay.js',
    },
];

/***
 * App对象配置
 * @type {{onLaunch: args.onLaunch, onShow: args.onShow}}
 */
let args = {
    _version: "2.8.9",
    platform: platform,
    query: null,
    onLaunch: function() {
        this.getStoreData();
    },
    onShow: function(e) {
        if (e.scene)
            this.onShowData = e;
        if (e && e.query) {
            this.query = e.query
        }
        if (this.getUser()) {
            this.trigger.run(this.trigger.events.login);
        }
    },
    is_login: false,
    login_complete: false,
    is_form_id_request: true
};
for (let i in modules) {
    args[modules[i].name] = require('' + modules[i].file);
}

var _web_root = args.api.index.substr(0, args.api.index.indexOf('/index.php'));
args.webRoot = _web_root;
args.getauth = function(object) {
    var app = this;
    if (app.platform == 'my') {
        if (object.success) {
            var res = {
                authSetting: {}
            }
            res.authSetting[object.author] = true;
            object.success(res);
        }
    } else {
        app.core.getSetting({
            success: function(res) {
                console.log(res);
                if (typeof res.authSetting[object.author] === 'undefined') {
                    app.core.authorize({
                        scope: object.author,
                        success: function(res) {
                            if (object.success) {
                                res.authSetting = {};
                                res.authSetting[object.author] = true;
                                object.success(res);
                            }
                        }
                    });
                } else if (res.authSetting[object.author] == false) {
                    app.core.showModal({
                        title: '是否打开设置页面重新授权',
                        content: object.content,
                        confirmText: '去设置',
                        success: function(e) {
                            if (e.confirm) {
                                app.core.openSetting({
                                    success: function(res) {
                                        if (object.success) {
                                            object.success(res);
                                        }
                                    },
                                    fail: function(res) {
                                        if (object.fail) {
                                            object.fail(res);
                                        }
                                    },
                                    complete: function(res) {
                                        if (object.complete)
                                            object.complete(res);
                                    }
                                })
                            } else {
                                if (object.cancel) {
                                    app.getauth(object);
                                }
                            }
                        }
                    })
                } else {
                    if (object.success) {
                        object.success(res);
                    }
                }
            }
        })
    }
};

args.getStoreData = function() {
    var app = this;
    var api = this.api;
    var core = this.core;
    app.request({
        url: api.default.store,
        success: function(res) {
            if (res.code == 0) {
                core.setStorageSync(app.const.STORE, res.data.store);
                core.setStorageSync(app.const.STORE_NAME, res.data.store_name);
                core.setStorageSync(app.const.SHOW_CUSTOMER_SERVICE, res.data.show_customer_service);
                core.setStorageSync(app.const.CONTACT_TEL, res.data.contact_tel);
                core.setStorageSync(app.const.SHARE_SETTING, res.data.share_setting);
                app.permission_list = res.data.permission_list;
                core.setStorageSync(app.const.WXAPP_IMG, res.data.wxapp_img);
                core.setStorageSync(app.const.WX_BAR_TITLE, res.data.wx_bar_title);
                core.setStorageSync(app.const.ALIPAY_MP_CONFIG, res.data.alipay_mp_config);
                core.setStorageSync(app.const.STORE_CONFIG, res.data);
                setTimeout(function(e) {
                    app.config = res.data;
                    if (app.configReadyCall) {
                        app.configReadyCall(res.data);
                    }
                }, 1000)
            }
        },
        complete: function() {
            //page.login();
        }
    });
}

let app = App(args);