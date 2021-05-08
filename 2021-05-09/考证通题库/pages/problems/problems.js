var request = require('../../utils/request').request;
var attachAns = require('../../utils/util').attachAns;
var WrongRecord = require('../../utils/wrong-record');
var pending = false, submitted = false;
var index = 0, doneMax = 0, answer = [], chapter = {};
var app = getApp();

Page({
  data: {
    problems: [],
    problem: {
      problem: "",
      images: [],
      choices: [],
      answer: [],
      analysis: ''
    },
    done: 0,
    total: 0,
    wrong: 0,
    // 答案是否正确
    right: false,
    // 是否提交题目
    submitted: false
  },
  onLoad: function() {
    // var category = app.getCategory();
    chapter = app.getChapter();
    // console.log(chapter);
    wx.setNavigationBarTitle({
      title: chapter.name
    })
    this.setData({ total: chapter.count });

    // 获取完成题数
    var done = wx.getStorageSync('cnt_done_' + chapter._id);
    // console.log(done, chapter.count);
    if (done) {
      done = parseInt(done);
      doneMax = done;
      // 全部完成时显示最后一题
      if (done >= chapter.count) done = chapter.count-1;
    } else {
      doneMax = done = 0;
    }
    this.setData({ done: parseInt(done) });

    // 获取错题数
    var wrong = wx.getStorageSync('cnt_wrong_' + chapter._id);
    if (wrong) {
      wrong = parseInt(wrong);
    } else {
      wrong = 0;
    }
    this.setData({ wrong: parseInt(wrong) });

    // 从完成位置加载题目
    var that = this;
    request({
      url: `api/qbank/problem/${chapter._id}/${done}`,
      success: function(res) {
        // 选项数据格式处理
        res = res.map(item => {
          var choices = item.choices;
          item.choices = [];
          for (var key in choices) {
            item.choices.push({ key: key, content: choices[key] });
          }
          return item;
        });
        // 存在后续题目
        if (res.length > 0) {
          that.setData({
            problems: res,
            problem: attachAns.call(that, res[0])
          });
        }
      }
    });
  },
  onUnload: function() {
    // console.log(submitted, doneMax);
    // 存入完成题数及错题数
    if (submitted) doneMax++;
    if (doneMax > this.data.total) doneMax = this.data.total;
    wx.setStorageSync('cnt_done_' + chapter._id, doneMax.toString());
    wx.setStorageSync('cnt_wrong_' + chapter._id, this.data.wrong.toString());
  },
  onShow: function() {
    // 初始化全局变量
    index = 0;
    pending = false;
    submitted = false;
    chapter = app.getChapter();
  },
  checkboxChange: function(e) {
    answer = e.detail.value;
    if (this.data.problem.answer.length === 1) this.submit();
  },
  submit: function() {
    if (!this.data.problem._id) return;
    if (pending || submitted) return;
    submitted = true;
    // 答案对比
    var answer1 = answer;
    var answer2 = this.data.problem.answer;
    answer1.sort();
    answer2.sort();
    if (answer1.toString() === answer2.toString()) {
      this.setData({ right: true });
    } else {
      this.setData({
        right: false,
        wrong: this.data.wrong + 1
      });
      // 加入错题本
      WrongRecord.add(this.data.problem);
    }

    wx.setStorageSync('prob_ans_' + this.data.problem._id, JSON.stringify(answer1));
    this.setData({ submitted: submitted });
  },
  // 上一题
  prev: function() {
    if (pending) return;

    var data = this.data;

    if (data.done === 0) {
      wx.showModal({ title: '提示', content: '已到达第一题' });
      return;
    }

    submitted = false;

    if (index > 0) {
      this.setData({
        problem: attachAns.call(this, data.problems[--index]),
        done: data.done - 1
      });
    } else {
      // 获取题目
      pending = true;
      var that = this;
      request({
        url: `api/qbank/problem/prev/${chapter._id}/${data.done}`,
        success: function(res) {
          res = res.map(item => {
            var choices = item.choices;
            item.choices = [];
            for (var key in choices) {
              item.choices.push({ key: key, content: choices[key] });
            }
            return item;
          });
          if (res.length > 0) {
            index = index + res.length - 1,
            that.setData({
              problems: [...res, ...data.problems],
              problem: attachAns.call(that, res[res.length - 1]),
              done: data.done - 1
            });

          } else {
            // TODO
          }
          pending = false;
        }
      });
    }
  },
  // 下一题
  next: function() {
    if (pending) return;

    var data = this.data;

    // 获取题目
    if (data.done + 1 >= data.total) {
      if (data.done + 1 === data.total) {
        doneMax: doneMax > data.done + 1 ? doneMax : data.done + 1;
        this.setData({ done: data.done + 1 });
      }
      wx.showModal({
        title: '提示',
        content: '已到达最后一题',
        complete: function() {
          wx.navigateBack({ delta: 1 });
        }
      });
      return;
    }

    submitted = false;

    if (data.problems.length > ++index) {
      this.setData({ problem: attachAns.call(this, data.problems[index]) });
    } else {
      pending = true;
      var that = this;

      request({
        url: `api/qbank/problem/${chapter._id}/${data.done + 1}`,
        success: function(res) {
          res = res.map(item => {
            var choices = item.choices;
            item.choices = [];
            for (var key in choices) {
              item.choices.push({ key: key, content: choices[key] });
            }
            return item;
          });
          if (res.length > 0) {
            that.setData({
              problems: [...data.problems, ...res],
              problem: attachAns.call(that, res[0])
            });
          } else {
            // TODO
          }
          pending = false;
        }
      });
    }

    doneMax = doneMax > data.done + 1 ? doneMax : data.done + 1;
    this.setData({ done: data.done + 1 });
  }
})
