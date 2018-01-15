var SESSION_KEY = 'weapp_session_' + 'F2C224D4-2BCE-4C64-AF9F-A6D872000D1A';    //WX_SESSION_MAGIC_ID

var Session = {
    get: function () {
        return wx.getStorageSync(SESSION_KEY) || null;
    },

    set: function (session) {
        wx.setStorageSync(SESSION_KEY, session);
    },

    clear: function () {
        wx.removeStorageSync(SESSION_KEY);
    },
};

module.exports = Session;