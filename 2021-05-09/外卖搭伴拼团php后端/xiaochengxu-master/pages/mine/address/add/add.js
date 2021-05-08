
var app = getApp();
var city = require("../../../../utils/city.js");
var QQMapWX = require('../../../../utils/qqmap-wx-jssdk.min.js');
var qqmapsdk;
Page({
  data:{
    addressInfo : {}
  },
  address_id : '',
  onLoad: function (options) {
    qqmapsdk = new QQMapWX({
			key: 'HPNBZ-B426V-CZQPP-UN4R6-QYOF2-MYFU3'
		});
   var that = this;
   var address_id = options.address_id;
   if(address_id != 'undefined'){
      this.loadAddressInfo(address_id);
      this.address_id = address_id;
      that.province = this.data.addressInfo.province;
      that.city = this.data.addressInfo.city;
      that.district = this.data.addressInfo.district;
   }   
   city.init(that);
  },
  loadAddressInfo : function(address_id){
    var addressList = wx.getStorageSync('addressList');
    for(var i=0;i<addressList.length;i++){
      if(addressList[i]['address_id'] == address_id){console.log(addressList[i])
        this.setData({addressInfo:addressList[i]})
      }
    }
  },
  formSubmit : function(e){
    var consignee = e.detail.value.consignee,
      tel = e.detail.value.tel,
      province = this.data.city.selectedProvince,
      city = this.data.city.selectedCity,
      district = this.data.city.selectedDistrict,
      address = e.detail.value.address;
    if(consignee == '' || tel == '' || province == '' || address == ''){
      wx.showToast({
        title : '请填写相关信息',
        duration : 1000,
        mask : true
      })
      return;
    }	
    if(!/1[3-8]\d{9}/.test(tel)){
      wx.showToast({
        title : '请输入正确的手机号',
        duration : 1000,
        mask : true
      })
      return;
    }
    var page = this;
    var uid = wx.getStorageSync('uid');
    wx.request({
      url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Address/saveAddress',
      data: {data:JSON.stringify({uid: uid,openid : null,consignee : consignee,tel : tel,province : province,city : city,district : district,address : address,address_id : page.address_id})},
      method: 'POST',
      header: {
          'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){
        wx.navigateBack({delta:1});
        wx.setStorageSync('addressList','');
      },
      fail: function(res) {
        // fail
      }
    })
  },
  fetchPOI: function () {
    	var that = this;
    	// 调用接口
    	qqmapsdk.reverseGeocoder({
    		poi_options: 'policy=1',
    		get_poi: 1,
		    success: function(res) {
				console.log(res);
				// that.setData({
				// 	areaSelectedStr: res.result.address
				// });
		    },
		    fail: function(res) {
		//         console.log(res);
		    },
		    complete: function(res) {
		//         console.log(res);
		    }
    	});
    },
  backHome : function(e){
    app.backHome();
  }
})