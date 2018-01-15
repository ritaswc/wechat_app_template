
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

function setStorage(key, value) {
  return new Promise(function (resolve, reject) {
    wx.setStorage({ key: key, data: value, success: resolve, fail: reject });
  });
}

function getStorage(key) {
  return new Promise(function (resolve, reject) {
    wx.getStorage({ key: key, success: resolve, fail: reject });
  });
}

module.exports = {
  login: login,
  getUserInfo: getUserInfo,
  setStorage: setStorage,
  getStorage: getStorage,
  original: wx
};