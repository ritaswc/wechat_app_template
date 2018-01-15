var WrongRecord = require('../../utils/wrong-record');
var attachAns = require('../../utils/util').attachAns;
var app = getApp();

var index = 1;

Page({
  data: {
    problem: {
      problem: "",
      images: [],
      choices: [],
      answer: [],
      analysis: ''
    }
  },
  onLoad: function(option) {
  },
  onShow: function() {
    index = 1;
    var res = WrongRecord.getNext(index);
    if (!res) {
      wx.showModal({
        title: '提示',
        content: '错题本中还没有题目',
        complete: function() {
          wx.navigateBack({ delta: 1 });
        }
      });
    } else {
      index = res.index;
      this.setData({ problem : attachAns.call(this, res.problem) });
    }
  },
  del: function() {
    if (!this.data.problem._id) return;
    WrongRecord.del(index);
    this.next();
  },
  next: function() {
    var res = WrongRecord.getNext(index + 1);
    if (!res) {
      wx.showModal({
        title: '提示',
        content: '已到达最后一题',
        complete: function() {
          wx.navigateBack({ delta: 1 });
        }
      });
    } else {
      index = res.index;
      this.setData({ problem : attachAns.call(this, res.problem) });
    }
  },
  prev: function() {
    if (index > 1) {
      var res = WrongRecord.getNext(index - 1);
      if (!res) {
        wx.showModal({ title: '提示', content: '已到达第一题' });
      } else {
        index = res.index;
        this.setData({ problem : attachAns.call(this, res.problem) });
      }
    } else {
      wx.showModal({ title: '提示', content: '已到达第一题' });
    }
  }
});
