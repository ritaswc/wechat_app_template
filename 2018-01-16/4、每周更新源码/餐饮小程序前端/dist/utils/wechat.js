'use strict';

var Promise = require('./bluebird');

function login() {
  return new Promise(function (resolve, reject) {
    wx.login({ success: resolve, fail: reject });
  });
}

function getUserInfo() {
  return new Promise(function (resolve, reject) {
    wx.getUserInfo({ success: resolve, fail: reject });
  });
}

module.exports = { login: login, getUserInfo: getUserInfo };
//# sourceMappingURL=wechat.js.map
