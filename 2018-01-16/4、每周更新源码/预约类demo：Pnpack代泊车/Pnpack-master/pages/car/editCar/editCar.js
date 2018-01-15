Page({
  data:{
     vehicle:"未选择",
     brand:"未选择",
     band_id:3,
     car_id:1,
     logo:"1",
  },
  onLoad:function(options){
     var vm=this;
      console.log(options.car_id);
       console.log(options.car_name);
      vm.setData({
       vehicle:options.car_name,
       brand:options.brand_name,
       car_id:options.car_id,
       brand_id:options.brand_id,
       
     

      })
       
  },
 
   onchoosevehicle:function(){
     wx.navigateTo({
        url: '../addCar/chooseVehicle/chooseVehicle'
    })
  },
  onchoosebrand:function(){
     wx.navigateTo({
        url: '../addCar/chooseBrand/chooseBrand'
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
        car_id:this.data.car_id,
        
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