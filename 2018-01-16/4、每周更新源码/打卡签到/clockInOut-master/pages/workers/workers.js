var Api = require('../../utils/api.js')
var util = require('../../utils/util.js')
var companyId, commutingTime;
Page({
  data: {
    lists: [],
    date: '2017-03-02',
  },
  onLoad: function (options) {
    this.setData({
      token: wx.getStorageSync('token')
    })
    companyId = options.companyId
  },
  onShow: function () {
    this.obtainList()
  },
  onShareAppMessage: function () {
     return {
       title: '打卡咯',
       path: '/pages/login/login?'
     }
   },
  obtainList: function (cb) {
    util.obtainIndate((inday) => {
      wx.request({
        url: Api.clockList + wx.getStorageSync('token'),
        method: 'GET',
        success: (res) => {
          this.setData({
            lists: res.data,
            date: inday
          })
          this.obtainDate(this.data.date)
        },
        fail: function (fail) {
          console.log(fail)
        },
      })
    })
  },
  obtainDate: function (d) {
    wx.request({
      url: Api.clockList + wx.getStorageSync('token'),
      data: {
        today: d
      },
      method: 'GET',
      success: (res) => {
        console.log(res)
        this.setData({
          lists: res.data.map((newdata) => {
            for (var i = 0; i < newdata.punchCardRecord.length; i++) {
              if (newdata.punchCardRecord[i] != null) {
                newdata.punchCardRecord[i].sweeps.map((changeSecond) => {
                  var time = changeSecond.h_m_s.split(':')
                  changeSecond.h_m_s = time[0] + ":" + time[1]
                  return changeSecond
                })
                return Object.assign(newdata, { times: newdata.punchCardRecord[i].sweeps })
              }
            }
            return newdata
          })

        })
        console.log(this.data.lists)
      },
      fail: function (fail) {
        console.log(fail)
      },
    })
  },
  dataChange: function (event) {
    console.log(event.detail.value)
    this.setData({
      date: event.detail.value,
    })
    this.obtainDate(event.detail.value)
  },

  formatChange: function (list) {
    // 你可能需要将时间12:00:00的格式转成12:00
    var newtime = []
    return newtime
  },
  more_click: function (e) {
    wx.navigateTo({ url: '/pages/signMemo/signMemo?staffid=' + e.currentTarget.dataset.staffid })
  },
})