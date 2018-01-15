//app.js
App({
  onLaunch: function () {

  },
  onShow: function() {

  },
  onHide: function() {

  },
  getUserInfo: function(cb) {
    var that = this
    if (this.globalData.userInfo) {
      typeof cb == "function" && cb(this.globalData.userInfo)
    } else {
      //调用登录接口
      wx.login({
        success: function () {
          wx.getUserInfo({
            success: function (res) {
              that.globalData.userInfo = res.userInfo
              typeof cb == "function" && cb(that.globalData.userInfo)
            }
          })
        }
      })
    }
  },
  getCategory: function() {
    if (!this.globalData.category) {
      // 获取选定种类
      try {
        var value = wx.getStorageSync('category')
        if (value) {
          this.globalData.category = JSON.parse(value);
        }
      } catch (e) {
        wx.showModal({ title: '错误', content: '读取选定证书时异常' });
      }
    }
    return this.globalData.category;
  },
  setCategory: function(category) {
    this.globalData.category = category;
  },
  getChapter: function() {
    return this.globalData.chapter;
  },
  setChapter: function(chapter) {
    this.globalData.chapter = chapter;
  },
  globalData: {
    userInfo: null,
    category: null,
    chapter: null
  }
})
