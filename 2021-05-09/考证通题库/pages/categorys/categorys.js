var request = require('../../utils/request').request;
var app = getApp();

Page({
  data: {
    categorys: [],
    category: null,
    value: [0],
    submitLoading: false
  },
  onLoad: function() {
    var that = this;
    request({
      url: 'api/qbank/category',
      success: function(res) {
        that.setData({
          categorys: res,
          category: res[0]
        });
        // 初始化当前位置
        var category = app.getCategory();
        if (category) {
          for (var i = 0; i < res.length; ++i) {
            if (category._id === res[i]._id) {
              var arr = [];
              arr.push(i);
              that.setData({ value: arr, category: category });
              break;
            }
          }
        }
      }
    });
  },
  handlePickerChange: function(e) {
    const val = e.detail.value;
    this.setData({
      category: this.data.categorys[val[0]],
    })
  },
  handleSubmit: function(e) {
    const category = this.data.category;
    if (!category) return;
    // 设置选定种类
    app.setCategory(category);
    try {
      wx.setStorageSync('category', JSON.stringify(category));
    } catch (e) {
      wx.showModal({ title: '错误', content: '选定证书时写入异常' });
    }
    wx.navigateBack({ delta: 1 });
  }
})
