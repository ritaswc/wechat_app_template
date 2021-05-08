// pages/huangye/huangye.js

let TENCENT_KEY = "AJPBZ-S6MRU-NFIVK-4BH5A-IZA57-OKB24"

Page({
  data: {
    nation: "",
    province: "",
    city: "",
    district: "",
    street: "",
    street_number: ""

  },

  getLocation: function () {
    var that = this
    wx.getLocation({
      type: 'wgs84', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标  
      success: function (res) {
        // success  
        let longitude = res.longitude
        let latitude = res.latitude
        that.loadCity(longitude, latitude)
      },
      fail: function () {
        // fail  
      },
      complete: function () {
        // complete  
      }
    })
  },

  loadCity: function (longitude, latitude) {
    var that = this

    wx.request({

      // url: 'http://apis.map.qq.com/ws/geocoder/v1/?location=' + latitude + ',' + longitude + '&key=' + TENCENT_KEY,  
      url: 'https://api.map.baidu.com/geocoder/v2/?ak=0FuoX30MFf7YMrdS5Wi9GGAcHBblKDuu&callback=?&location=' + latitude + ',' + longitude + '&output=json',
      
      data: {},
      method: 'GET',
      header: {
        'Content-Type': 'application/json'
      },
      success: function (res) {
        // success  
        console.log(res);

        that.setData({
          nation: res.data.result.address_component.nation,
          province: res.data.result.address_component.province,
          city: res.data.result.address_component.city,
          district: res.data.result.address_component.district,
          street: res.data.result.address_component.street,
          street_number: res.data.result.address_component.street_number
        })

        console.log(that.data.nation,that.data.province,that.data.city)
        // var city = res.data.result.addressComponent.city;
        // that.setData({ city: city });
      },
      fail: function () {
        // fail  
      },
      complete: function () {
        // complete  
      }
    })
  },

onLoad: function (options) {
  // 页面初始化 options为页面跳转所带来的参数
  //获取定位
  this.getLocation()
},
onReady: function () {
  // 页面渲染完成
},
onShow: function () {
  // 页面显示
},
onHide: function () {
  // 页面隐藏
},
onUnload: function () {
  // 页面关闭
}
})