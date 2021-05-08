const AV = require('../../../../utils/av-weapp.js')
var QQMapWX = require('../../../../utils/qqmap-wx-jssdk.min.js');
var qqmapsdk;
var app = getApp();

Page({
  data: {
    current: 0,
    province: [],
    city: [],
    region: [],
    town: [],
    provinceObjects: [],
    cityObjects: [],
    regionObjects: [],
    townObjects: [],
	  provinceName: '请选择',
    animationData: {},
    maskVisual : 'hide'
  },
  formSubmit : function(e){
	var consignee = e.detail.value.consignee,
		tel = e.detail.value.tel,
		province = e.detail.value.province,
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
      wx.request({
        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Address/saveAddress',
        data: {data:JSON.stringify({uid: 47,openid : null,consignee : consignee,tel : tel,province : province,city : '',district : '',address : address,address_id : ''})},
        method: 'POST',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        success: function(res){
          wx.navigateBack({delta:1});
        },
        fail: function(res) {
          // fail
        }
      })
  },
  getArea: function (pid, cb) {
		var that = this;
		// query area by pid
		var query = new AV.Query('Area');
		query.equalTo('pid', pid);
		query.find().then(function (area) {
			cb(area);
		}, function (err) {
			
		});
	},
  onLoad : function(options){
    // 实例化API核心类
		qqmapsdk = new QQMapWX({
			key: 'BJFBZ-ZFTHW-Y2HRO-RL2UZ-M6EC3-GMF4U'
		});
		var that = this;
        // load province
		this.getArea(0, function (area) {
			var array = [];
			for (var i = 0; i < area.length; i++) {
				array[i] = area[i].get('name');
			}
			that.setData({
				province: array,
				provinceObjects: area
			});
		});
  },
  currentChanged : function(e){
    var current = e.detail.current;
    this.setData({current:current})
  },
  changeCurrent : function(e){
    var current = e.currentTarget.dataset.current;
    this.setData({current:current})
  },
  provinceTapped : function(e){
    // 标识当前点击省份，记录其名称与主键id都依赖它
    	var index = e.currentTarget.dataset.index;
    	// current为1，使得页面向左滑动一页至市级列表
    	// provinceIndex是市区数据的标识
    	this.setData({
    		provinceName: this.data.province[index],
    		regionName: '',
    		townName: '',
    		provinceIndex: index,
    		cityIndex: -1,
    		regionIndex: -1,
    		townIndex: -1,
    		region: [],
    		town: []
    	});
    	var that = this;
    	//provinceObjects是一个LeanCloud对象，通过遍历得到纯字符串数组
    	// getArea方法是访问网络请求数据，网络访问正常则一个回调function(area){}
    	this.getArea(this.data.provinceObjects[index].get('aid'), function (area) {
    		var array = [];
    		for (var i = 0; i < area.length; i++) {
    			array[i] = area[i].get('name');
    		}
			// city就是wxml中渲染要用到的城市数据，cityObjects是LeanCloud对象，用于县级标识取值
			that.setData({
				cityName: '请选择',
				city: array,
				cityObjects: area
			});
			// 确保生成了数组数据再移动swiper
			that.setData({
				current: 1
			});
		});
  },
  cityTapped: function(e) {
    	// 标识当前点击县级，记录其名称与主键id都依赖它
    	var index = e.currentTarget.dataset.index;
    	// current为1，使得页面向左滑动一页至市级列表
    	// cityIndex是市区数据的标识
    	this.setData({
    		cityIndex: index,
    		regionIndex: -1,
    		townIndex: -1,
    		cityName: this.data.city[index],
    		regionName: '',
    		townName: '',
    		town: []
    	});
    	var that = this;
    	//cityObjects是一个LeanCloud对象，通过遍历得到纯字符串数组
    	// getArea方法是访问网络请求数据，网络访问正常则一个回调function(area){}
    	this.getArea(this.data.cityObjects[index].get('aid'), function (area) {
    		var array = [];
    		for (var i = 0; i < area.length; i++) {
    			array[i] = area[i].get('name');
    		}
			// region就是wxml中渲染要用到的城市数据，regionObjects是LeanCloud对象，用于县级标识取值
			that.setData({
				regionName: '请选择',
				region: array,
				regionObjects: area
			});
			// 确保生成了数组数据再移动swiper
			that.setData({
				current: 2
			});
		});
    },
    regionTapped: function(e) {
    	// 标识当前点击镇级，记录其名称与主键id都依赖它
    	var index = e.currentTarget.dataset.index;
    	// current为1，使得页面向左滑动一页至市级列表
    	// regionIndex是县级数据的标识
    	this.setData({
    		regionIndex: index,
    		townIndex: -1,
    		regionName: this.data.region[index],
    		townName: ''
    	});
    	var that = this;
    	//townObjects是一个LeanCloud对象，通过遍历得到纯字符串数组
    	// getArea方法是访问网络请求数据，网络访问正常则一个回调function(area){}
    	this.getArea(this.data.regionObjects[index].get('aid'), function (area) {
			// 假如没有镇一级了，关闭悬浮框，并显示地址
			if (area.length == 0) {
				var areaSelectedStr = that.data.provinceName + that.data.cityName + that.data.regionName;
				that.setData({
					areaSelectedStr: areaSelectedStr
				});
				that.cascadeDismiss();
				return;
			}
			var array = [];
			for (var i = 0; i < area.length; i++) {
				array[i] = area[i].get('name');
			}
			// region就是wxml中渲染要用到的县级数据，regionObjects是LeanCloud对象，用于县级标识取值
			that.setData({
				townName: '请选择',
				town: array,
				townObjects: area
			});
			// 确保生成了数组数据再移动swiper
			that.setData({
				current: 3
			});
		});
    },
    townTapped: function (e) {
    	// 标识当前点击镇级，记录其名称与主键id都依赖它
    	var index = e.currentTarget.dataset.index;
    	// townIndex是镇级数据的标识
    	this.setData({
    		townIndex: index,
    		townName: this.data.town[index]
    	});
    	var areaSelectedStr = this.data.provinceName + this.data.cityName + this.data.regionName + this.data.townName;
    	this.setData({
    		areaSelectedStr: areaSelectedStr
    	});
    	this.cascadeDismiss();
    },
  cascadePopup : function(){
      var animation = wx.createAnimation({
        duration: 500,
        timingFunction: 'ease-in-out'
      })
      this.animation = animation;
      animation.translateY(-285).step();
      this.setData({
          animationData : this.animation.export(),
          maskVisual : 'show'
      })
  },
  cascadeDismiss : function(){
      this.animation.translateY(285).step();
      this.setData({
          animationData : this.animation.export(),
          maskVisual : 'hide'
      })
  },
  backHome : function(e){
    app.backHome();
  }
})