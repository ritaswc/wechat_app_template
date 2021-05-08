var HOST = require('../pages/config').HOST;

var pendingCnt = 0;

exports.request = (options) => {

  if (pendingCnt++ === 0) {
    wx.showToast({
      title: '加载中',
      icon: 'loading',
      duration: 10000
    });
  }

  return wx.request({
    url: HOST + options.url,
    success: function(res) {
      res = res.data;
      if (res.code != 0) {
        wx.showModal({ title: '错误', content: res.data });
        if (options.fail) return options.fail(res.data);
      }
      if (options.success) return options.success(res.data);
    },
    fail: function(res) {
      res = res.data;
      wx.showModal({ title: '错误', content: res.data });
      if (options.fail) return options.fail(res.data);
    },
    complete: function(res) {
      if (--pendingCnt === 0) wx.hideToast();
    }
  });
};
