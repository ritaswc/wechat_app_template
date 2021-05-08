module.exports = function (user_info) {
    this.core.setStorageSync(this.const.USER_INFO, user_info);
}