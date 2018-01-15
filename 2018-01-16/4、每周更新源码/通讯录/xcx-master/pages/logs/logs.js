//logs.js
// var event = require('../event.js')
var util = require('../../utils/util.js')
var xcxPage = require('../xcx.js');

Page(xcxPage({
  data: {
    logs: []
  },
  onLoad: function(data) {
    this.$emit('index-emit', 'index-emit-params', 'index-emit-params');
    this.$emit('index-emit2', 'index-emit2-params', 'index-emit2-params');

    console.log(data);
    this.setData({
      logs: (wx.getStorageSync('logs') || []).map(function(log) {
        return util.formatTime(new Date(log))
      })
    });
    this.$emit('index-emit', 'emit触发事件', '3');
  },
  navBack() {
    this.$invoke('pages/index/index', 'testInvoke', 'arguments1', 'arguments2')
      // event.emit('DataChanged', 'Log-Page-Btn-Press');
    wx.navigateBack();
  },
}))