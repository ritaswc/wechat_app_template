// pages/scan/scan.js
var Util = require('../util/util.js')
Page({
  data: {
    ISBN: '',
    book: {},
    url: ''
  },
  /**
   * 共享给其他人查阅
   */
  share: function () {
    Util.networkStatus()
    if (this.data.url !== '') {
      wx.showModal({
        title: '共享成功',
        content: '请前往 ' + this.data.url + ' 进行查看！'
      })
      return false;
    }
    var that = this;
    wx.showModal({
      title: '提示',
      content: '⚠️ 是否承诺将书送到需要的人手中？',
      success: function (res) {
        if (!res.confirm) {
          return false;
        }
        that._shareToHacpai();
      }
    })
  },
  /**
   * 页面渲染完成，隐藏导航 loading 效果
   */
  onReady: function () {
    wx.hideNavigationBarLoading();
  },
  /**
   * 分享到黑客派
   */
  _shareToHacpai: function () {
    var that = this;
    wx.request({
      url: 'https://hacpai.com/book/share',
      data: {
        ISBN: this.data.ISBN
      },
      method: 'POST',
      header: {
        'content-type': 'application/json',
        'Cookie': 'b3log-latke=' + wx.getStorageSync('cookie')
      },
      success: function (res) {
        if (res.errMsg !== 'request:ok') {
          wx.showToast({
            title: res.errMsg,
            icon: 'loading',
            duration: 8000
          })
          return false;
        }
        if (!res.data.sc) {
          wx.showToast({
            title: res.data.msg,
            icon: 'loading',
            duration: 8000
          })
          return false;
        }
        that.setData({
          url: res.data.url
        })
        wx.showModal({
          title: '分享成功',
          content: '请前往 ' + res.data.url + ' 进行查看！'
        })
      },
      fail: function (res) {
        wx.showToast({
          title: res.errMsg,
          icon: 'loading',
          duration: 8000
        })
      }
    })
  },
  /**
   * 获取书本信息
   */
  onLoad: function (options) {
    wx.showNavigationBarLoading();
    Util.networkStatus()
    let that = this;
    that.setData({
      ISBN: options.ISBN
    });
    wx.request({
      url: 'https://hacpai.com/book/info',
      data: {
        ISBN: options.ISBN
      },
      method: 'POST',
      header: {
        'content-type': 'application/json',
        'Cookie': 'b3log-latke=' + wx.getStorageSync('cookie')
      },
      success: function (res) {
        if (res.errMsg !== 'request:ok') {
          wx.showToast({
            title: res.errMsg,
            icon: 'loading',
            duration: 8000
          })
          return false;
        }
        if (!res.data.sc) {
          wx.showToast({
            title: res.data.msg,
            icon: 'loading',
            duration: 8000
          })
          return false;
        }
        res.data.book.bookCatalogList = res.data.book.bookCatalog.split('\n');
        that.setData({
          book: res.data.book
        })
        wx.setNavigationBarTitle({
          title: '《' + res.data.book.bookTitle + '》纸质实体书免费送啦！'
        })
      },
      fail: function (res) {
        wx.showToast({
          title: res.errMsg,
          icon: 'loading',
          duration: 8000
        })
      }
    })
  }
})