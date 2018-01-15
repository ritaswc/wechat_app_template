import { getDailyWeather, getNowWeather, getCityName } from '../../utils/service'
import { WEATHERKEY } from '../../utils/key'
import event from '../../utils/event'

// const app = getApp()
// console.log(app)
Page({
  data: {
    Day: '',
    Night: '',
    unit: 'c',
    lang: 'zh-Hans',
    city: 'local',
    now: {},
    future: {},
  },

  onLoad() {
    event.on("CityChanged", this, this.setCityData)
    event.on("TempChanged", this, this.setTempUnit)
    event.on("LangChanged", this, this.setLang)
    this.setCityData(this.data.city)
    this.setLang(this.data.lang)
  },

  onShow() {
    if(this.data.city !== 'local'){
      this.loadData()
    }
  },

  setCityData(city) {
    var that = this
    if (city === 'local') {
      getCityName((res) => {
        that.setData({ city: res.data.regeocode.addressComponent.city })
        that.loadData()
      })
    } else {
      that.setData({ city: city })
    }
  },

  setTempUnit(unit) {
    this.setData({ unit: unit })
  },

  setLang(lang) {
    const _ = wx.T._

    this.setData({
      lang:lang,
      Day: _('Day'),
      Night: _('Night'),
    })
  },

  loadData() {
    var that = this
    getNowWeather({
      data: {
        key: WEATHERKEY,
        location: this.data.city,
        language: this.data.lang,
        unit: this.data.unit,
      },
      success: (res) => {
        const result = res.data.results[0]
        const cityName = result.location.name
        const temperature = result.now.temperature
        const text = result.now.text
        that.setData({
          now:
          {
            cityName: cityName,
            temperature: temperature,
            text: text
          }
        })
      }
    })

    getDailyWeather({
      data: {
        key: WEATHERKEY,
        location: this.data.city,
        language: this.data.lang,
        unit: this.data.unit,
        start: 0,
        days: 3
      },
      success: (res) => {
        const _ = wx.T._
        const future = []
        const results = res.data.results[0]
        const daily = results.daily
        const weekday = [_('Today'), _('Tomorrow'), _('DayAfterTmw')]
        for (var i in daily) {
          future.push({
            day: weekday[i],
            code_day: daily[i].code_day,
            code_night: daily[i].code_night,
            high: daily[i].high,
            low: daily[i].low
          })
        }
        that.setData({ future: future })
      }
    })
  },
})
