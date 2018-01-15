// pages/goodsearch/goodsearch.js
Page({
  data: {
    goodsearch: '搜索界面',
    cancelValue: '取消',
    searchName: '',
    list: [],  //历史搜索
    hotsearch: ''
  },
  bindKeyInput: function (e) {
    var value = e.detail.value;
    if (value > 0 || value.length > 0) {
      this.setData({
        searchName: value,
        cancelValue: '确认',
      })
    } else {
      this.setData({
        cancelValue: '取消',
      })
    }

  },
  //清空缓存数据
  clearSearchStorage: function () {
    wx.setStorageSync('searchData', [])
    this.setData({
      list: []
    })
  },
  click: function () {
    if (this.data.cancelValue == '确认') {
      if (this.data.searchName != '') {
        //调用API从本地缓存中获取数据  
        var searchData = wx.getStorageSync('searchData') || []
        if (searchData.indexOf(this.data.searchName) == -1) {
          searchData.push(this.data.searchName)
          wx.setStorageSync('searchData', searchData)
        }
      }
      wx.redirectTo({
        url: '../goodlist/goodlist?id=' + this.data.searchName,
      })
    } else {
      wx.navigateBack({
        delta: 1, // 回退前 delta(默认为1) 页面
      })
    }
  },
  //点击button
  clickName: function (e) {
    //获取button内容 赋值
    this.setData({
      hotsearch: e.currentTarget.dataset.name,
      cancelValue: '确认',
      searchName: e.currentTarget.dataset.name,
    })
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
    var getSearch = wx.getStorageSync('searchData');
    this.setData({
      list: getSearch,
      inputValue: '',

    })
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  }
})