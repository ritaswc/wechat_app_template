module.exports = function() {
    var t = this.core.getStorageSync(this.const.USER_INFO);
    return t || "";
};