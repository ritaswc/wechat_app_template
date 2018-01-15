var wrongs = [];
var app = getApp();

exports.getNext = function(index) {
  var category = app.getCategory();
  for (var i = index; i < 1000; ++i) {
    var problem = wx.getStorageSync("wrong_prob_" + category._id + '_' + i);
    if (problem) {
      problem = JSON.parse(problem);
      if (!problem._id) continue;
      return { index: i, problem: problem };
    } else {
      return null;
    }
  }
}

exports.getPrev = function(index) {
  var category = app.getCategory();
  for (var i = index; i > 0; --i) {
    var problem = wx.getStorageSync("wrong_prob_"+ category._id + '_' + i);
    if (problem) {
      problem = JSON.parse(problem);
      if (!problem._id) continue;
      return { index: i, problem: problem };
    } else {
      return null;
    }
  }
}

exports.save = function() {

}

exports.add = function(problem) {
  var category = app.getCategory();
  wx.getStorage({
    key: 'wrong_cnt_' + category._id,
    success: function(res) {
      var cnt = parseInt(res.data) + 1;
      wx.setStorage({
        key: "wrong_prob_"+ category._id + '_' + cnt,
        data: JSON.stringify(problem)
      });
      wx.setStorage({
        key: "wrong_cnt_"+ category._id,
        data: cnt.toString()
      });
    },
    fail: function() {
      wx.setStorage({
        key: "wrong_prob_"+ category._id + '_1',
        data: JSON.stringify(problem)
      });
      wx.setStorage({
        key: "wrong_cnt_"+ category._id,
        data: '1'
      });
    }
  });
}

exports.del = function(index) {
  var category = app.getCategory();
  wx.setStorage({
    key: "wrong_prob_"+ category._id + '_' + index,
    data: '{}'
  })
  wx.showToast({
    title: '移除题目成功',
    icon: 'success',
    duration: 700
  })
}
