Page({
  tap: function(e) {
    console.log(e);
    if (e.target.dataset.breathe === true) {
      wx.navigateTo({
        url: '../breathe/breathe'
      });
    }
  },
});
