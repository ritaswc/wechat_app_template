var app = getApp();
Page({
  data: {
    favs: [],
    is_empty: false
  },
  
  onPullDownRefresh: function () {
        wx.stopPullDownRefresh();
    },
  /* 学校codereverse */
  reverse_codes: function () {
    var codes = app.codes;
    var reverse_codes = {}
    for (var x in codes) {
      reverse_codes[codes[x]] = x
    };
    return reverse_codes;
  },

  /* 根据code获取学校名字 */
  getSchoolName: function (favs) {
    var reverse_codes = this.reverse_codes();
    for (var i = 0; i < favs.length; i++) {
      favs[i]['school_name'] = reverse_codes[favs[i].school]
    }
    return favs;
  },

  /* 页面加载 */
  onLoad: function (options) {
    var that = this;
    var favs = wx.getStorageSync('favs') || [];
    favs = that.getSchoolName(favs);
    that.setData({
      favs: favs,
      is_empty: favs != []
    });
    wx.setNavigationBarTitle({ title: "我的图书收藏" })
  },

  /* 页面初次渲染完成 */
  onReady: function () {
    wx.setNavigationBarTitle({ title: "我的图书收藏" })
  },

  /* 页面显示 */
  onShow: function () {
    wx.setNavigationBarTitle({ title: "我的图书收藏" })
    var that = this;
    var favs = wx.getStorageSync('favs') || [];
    favs = that.getSchoolName(favs);
    that.setData({
      favs: favs,
      is_empty: (favs.length == 0)
    });
  },
})