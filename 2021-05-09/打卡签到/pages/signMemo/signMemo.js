var util = require('../../utils/util.js')
var staffId, Inday, flag = true;
Page({
  data: {
    winWidth: '',
    winHeight: '',
    date: '2017-03',
    modalDate: '',
    modalTime: '',
    modalHidden: false,
    weeks: [{ week: '一', TYPE: 'vWeeklabel' },
    { week: '二', TYPE: 'vWeeklabel' },
    { week: '三', TYPE: 'vWeeklabel' },
    { week: '四', TYPE: 'vWeeklabel' },
    { week: '五', TYPE: 'vWeeklabel' },
    { week: '六', TYPE: 'vWeeklabel' },
    { week: '日', TYPE: 'vWeeklabel' },],
    calendars: []
  },
  onLoad: function (options) {
    wx.getSystemInfo({
      success: (res) => {
        this.setData(
          {
            winWidth: res.windowWidth,
            winHeight: res.windowHeight,
          });
      }
    })
    staffId = options.staffid

    this.loadDate(this.data.date)
  },
  loadDate: function (date) {
    var inday = new Date();
    inday.setMonth(inday.getMonth() + 1)
    Inday = inday.getDate()
    var newdate = new Date(date)
    newdate.setDate(1)
    this.setData({ calendars: this.data.weeks, date: date })
    for (var n = 1; n < newdate.getDay(); n++) {//
      // 判断一号是星期几
      this.setData({
        calendars: this.data.calendars.concat({ week: '', TYPE: 'vWeeknull' }),
      })
    }
    for (var m = 1; m < 31; m++) {
      this.setData({
        calendars: this.data.calendars.concat({ week: m, TYPE: 'vWeekgray' })
      })
    }
    util.loadStaffDate(date, staffId, (day, backData) => {
      console.log(backData)

      // 每一天
      for (var i = 0; i < backData.data.length; i++) {
        // 循环某一天
        var SignDate = new Date(backData.data[i].today)
        console.log(SignDate.getDay())

        this.setData({
          calendars: this.data.calendars.map((m) => {
            console.log(SignDate.getDate())
            if (m.week == Inday) {
              //如果是今天
              m.TYPE = 'vWeekinday'
              flag = false
              return Object.assign(m, { time: backData.data[i].sweeps[0].h_m_s })
            } else {
              if (m.week == SignDate.getDate()) {
                // 如果是有打卡的
                for (var y = 0; y < backData.data[i].sweeps.length; y++)
                  if (backData.data[i].sweeps[y].conditions != 'ok') {
                    // 如果打卡不异常
                    m.TYPE = 'vWeekyellow'
                    return Object.assign(m, { time: backData.data[i].sweeps[y].h_m_s })
                  }
              } else {
                if (flag == false) {
                  // m.TYPE = 'vWeekred'
                }
              }
            }
            return m;
          }
          ),
          date: date
        })
      }
    })
    this.setData({
      calendars: this.data.calendars.map((m) => {
        if (m.week == Inday) {
          //如果是今天
          m.TYPE = 'vWeekinday'
        }
        return m
      }
      )
    })
    flag = true
  },

  changeDate: function (e) {
    var str = e.detail.value.split('-')
    this.loadDate(e.detail.value)
  },
  ToChangeName: function (e) {
    wx.navigateTo({ url: '/pages' })
  },
  day_click: function (e) {
    console.log(e)
    this.setData({
      modalHidden: true,
      modalDate: this.data.date + '-' + e.currentTarget.dataset.msg,
      modalTime: e.currentTarget.dataset.type
    })
  },
  modal_click: function (e) {
    this.setData({
      modalHidden: false,
      modalDate: '',
      modalTime: ''
    })
  },


})