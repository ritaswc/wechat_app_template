/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author LuWei
 */

/***
 * 商城的配置
 * 例如：商城信息、存在服务器的图片
 */
module.exports = function (callback) {
    let api = getApp().api;
    let core = getApp().core;
    var app = getApp();
    if (callback && typeof callback === 'function') {
        var config = core.getStorageSync(app.const.STORE_CONFIG);
        if (config) {
            callback(config);
        }
        if (app.config) {
            config = app.config;
        } else {
            getApp().trigger.add(getApp().trigger.events.callConfig, 'call', function (config) {
                callback(config)
            });
            if (getApp().configReadyCall && typeof getApp().configReadyCall == 'function'){

            } else {
                getApp().configReadyCall = function (config) {
                    getApp().trigger.run(getApp().trigger.events.callConfig, function () {

                    }, config);
                }
            }
        }
    }
};