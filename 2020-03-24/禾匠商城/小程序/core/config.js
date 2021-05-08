module.exports = function(e) {
    getApp().api;
    var t = getApp().core, g = getApp();
    if (e && "function" == typeof e) {
        var n = t.getStorageSync(g.const.STORE_CONFIG);
        n && e(n), g.config ? n = g.config : (getApp().trigger.add(getApp().trigger.events.callConfig, "call", function(t) {
            e(t);
        }), getApp().configReadyCall && "function" == typeof getApp().configReadyCall || (getApp().configReadyCall = function(t) {
            getApp().trigger.run(getApp().trigger.events.callConfig, function() {}, t);
        }));
    }
};