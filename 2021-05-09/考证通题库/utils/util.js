function attachAns(problem) {
  // console.log(problem);
  var ans = wx.getStorageSync('prob_ans_' + problem._id);
  if (ans) {
    ans = JSON.parse(ans);
    ans.forEach(item => {
      for (var i = 0; i < problem.choices.length; ++i) {
        if (item === problem.choices[i].key) problem.choices[i].checked = true;
      }
    });
    this.setData({ submitted: true });
  } else {
    this.setData({ submitted: false });
  }
  // console.log(problem);
  return problem;
}

module.exports = {
  attachAns: attachAns
}
