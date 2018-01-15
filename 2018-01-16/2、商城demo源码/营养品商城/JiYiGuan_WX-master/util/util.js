const constant = require("./constant.js");
const storage = require("./storage.js");

function isPhone(phone) {
    if (!(/^1(3|4|5|7|8)\d{9}$/.test(phone))) {
        return false;
    }
    return true;
}

function showSuccessToast(config) {
    wx.showToast({
        title: config.title,
        icon: 'success',
        mask: true,
        duration: constant.duration,
        success: config.success
    });
}

function showFailToast(config) {
    wx.showToast({
        title: config.title,
        image: '/image/info.png',
        mask: true,
        duration: constant.duration,
        success: config.success
    });
}

module.exports = {
    isPhone: isPhone,
    showSuccessToast: showSuccessToast,
    showFailToast: showFailToast
};