// pages/map/map.js
Page({
    data:{
        latitude:'',
        longitude:'',
         markers: [{
          id: 0,
          latitude: '',
          longitude: '',
          width: 50,
          height: 50
        }],
       
    },
    onLoad:function(options){  //markers 用于标记显示用户的位置, 页面传过来的经度纬度用来显示中心点
        console.log(options)
        let latitude = options.latitude;
        let longitude = options.longitude;
         this.setData({ //====企业所在位置;
            latitude:latitude,
            longitude:longitude
        }) 
        var that = this;
        wx.getLocation({ //====获取用户当前位置;
        type: 'wgs84',
        success: function(res) { 
              let latitude = res.latitude;
              let longitude = res.longitude;
              let tmplatitude = '30.28911';
              let tmplongitude = '120.087'
              that.setData({
                'markers[0].latitude':tmplatitude,
                'markers[0].longitude':tmplongitude
              })
            }
        })
    }
})