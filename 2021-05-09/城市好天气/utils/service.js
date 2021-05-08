
import {AMAPKEY,weatherhost,amaphost} from './key.js'

const wxRequest = (params, url) => {
  wx.showToast({
    title: '加载中',
    icon: 'loading'
  })
  wx.request({
    url: url,
    method: params.method || 'GET',
    data: params.data || {},
    header: {
      'Content-Type': 'application/json'
    },
    success: (res) => {
      params.success && params.success(res)
      wx.hideToast()
    },
    fail: (res) => {
      params.fail && params.fail(res)
    },
    complete: (res) => {
      params.complete && params.complete(res)
    }
  })
}

const getDailyWeather = (params) => wxRequest(params, weatherhost+'/daily.json')

const getHourlyWeather = (params) => wxRequest(params, weatherhost+'/hourly.json')

const getNowWeather = (params) => wxRequest(params, weatherhost+'/now.json')

const getCityName =(callback)=>{
  wx.getLocation({
  type: 'gcj02', //返回可以用于wx.openLocation的经纬度
  success: function(res) {
    const latitude = res.latitude
    const longitude = res.longitude
    const ip =  longitude+","+latitude
    wxRequest({
      data:{
        location:ip,
        key: AMAPKEY
      },
      success:callback
    },amaphost)
  }
})
}

module.exports = {
  getDailyWeather,
  getHourlyWeather,
  getNowWeather,
  getCityName
}
