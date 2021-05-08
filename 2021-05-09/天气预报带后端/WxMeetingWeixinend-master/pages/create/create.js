//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    motto: '预约会议',
    currentDate: new Date(),
    meetingRooms: ['会议室1', '会议室2']
  },
  //事件处理函数
  formSubmit: function() {
      var that = this;
    // 获取天气信息
    wx.request({
        url: 'https://liuanchen.com/w/meeting',
        method: 'POST',
        header: {
            'content-type': 'application/json'
        },
        data: {
            meetingDate: that.getData('meetingDate'),
            meetingTime: that.getData('meetingTime'),
            meetingRoorm: that.getData('meetingRoom'),
            title: that.getData('title'),
            content: that.getData('content')
        },
        success: function(res) {
        wx.navigateTo({
            url: '../detail/detail'
        })
        },
        fail: function(error) {
        that.setData({
                errMsg: error.errMsg
            })
        }
    })
  },
  onLoad: function () {
  }
})
