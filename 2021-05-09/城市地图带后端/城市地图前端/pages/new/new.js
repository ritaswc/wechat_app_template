var app = getApp()
Page({
  data: {
    radioItems: [
        {name: '有洗手间', value: '0'},
        {name: '无洗手间', value: '1', checked: true}
    ],
    bathroom: 0
  },
  radioChange: function (e) {
      console.log('radio发生change事件，携带value值为：', e.detail.value);

      var radioItems = this.data.radioItems;
      for (var i = 0, len = radioItems.length; i < len; ++i) {
          radioItems[i].checked = radioItems[i].value == e.detail.value;
      }

      this.setData({
          radioItems: radioItems,
          bathroom: bathroom
      });
  },
  formSubmit: function(e) {
    var that = this
    var bathroom
    if (e.detail.value.bathroom == '1') {
      bathroom = '1'
    } else {
      bathroom = '0'
    }

    var city = e.detail.value.city
    var name = e.detail.value.spot_name
    var longitude = e.detail.value.longitude
    var latitude = e.detail.value.latitude
    var download_speed = e.detail.value.download_speed
    var upload_speed = e.detail.value.upload_speed
    var price_indication = e.detail.value.price_indication
    var commit_message = e.detail.value.commit_message


    var comment_request_url = "http://192.168.2.2:8000/api/v1/spots/?format=json"
    wx.request({
      method: 'POST',
      data: {
         'city': city,
         'name': name,
         'longitude': longitude,
         'latitude': latitude,
         'download_speed': download_speed,
         'upload_speed': upload_speed,
         'price_indication': price_indication,
         'bathroom': bathroom,
         'commit_message': commit_message,
         'commit_user_name': wx.getStorageSync('user_data').username,
         'commit_user_id': wx.getStorageSync('user_data').id
      },
      url: comment_request_url,
      header: {
        'content-type':'application/x-www-form-urlencoded'
      },
      success: function(res) {
        var commit_data = res.data
        console.log('commit_data')
        console.log(commit_data)

        wx.showToast({
          title: '已提交',
          icon: 'success',
          duration: 2000
        })
      }
    })
  },
  markertap(e) {
    console.log(e.markerId)
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function () {
    console.log('onLoad')
    var that = this
    wx.getLocation({
      type: 'wgs84',
      success: function(res) {
        var latitude = res.latitude
        var longitude = res.longitude

        that.setData({
          longitude: longitude,
          latitude: latitude
        })



      }
    })
  }
})
