Page({
  /*时间选择器*/
  data: {
    date: '2016-09-01',
    date2: '2017-09-01',
  },
  bindDateChange: function(e) {
    this.setData({
      date: e.detail.value
    })
  },
  bindDateChange2: function(e) {
    this.setData({
      date2: e.detail.value
    })
  }
})