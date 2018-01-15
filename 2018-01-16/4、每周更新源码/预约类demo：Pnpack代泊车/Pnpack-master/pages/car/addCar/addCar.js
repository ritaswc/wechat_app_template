// pages/car/addCar/addCar.js
Page({
  data:{
     vehicle:"未选择",
     brand:"未选择",
     band_id:1,
     logo:"1",
  },
  onLoad:function(options){
     var vm=this
  },
 
   onchoosevehicle:function(){
     wx.navigateTo({
        url: 'chooseVehicle/chooseVehicle'
    })
  },
  onchoosebrand:function(){
     wx.navigateTo({
        url: 'chooseBrand/chooseBrand'
    })
  },
  
  bindSuccess: function(e) {
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/cars/save-car-info',
      header: {//请求头
        "Content-Type": "application/x-www-form-urlencoded"
      },
      data: {
        openid: wx.getStorageSync('openid'),
        brand_id:this.data.band_id,
        car_number:this.data.vehicle,
      },
      method: "POST",//get为默认方法/POST
      success: function (res) {
        
           wx.redirectTo({
         url: '../../car/car'
    })
      }

    })
	
  },
  
})