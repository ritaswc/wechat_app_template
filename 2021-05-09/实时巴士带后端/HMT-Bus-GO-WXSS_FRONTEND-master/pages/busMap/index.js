/**
 *	HMT Bus GO! (WXSS VER.)
 *
 *	@author CRH380A-2722 <609657831@qq.com>
 *	@copyright 2016-2017 CRH380A-2722
 *	@license MIT
 *	@note 页面逻辑
 */

var app = getApp();

var interval = null;

Page({

	data: {
		panelDisplay: 'none',
		longitude: 113.354530,
		latitude: 23.155907,
		height: 350,
		scale: 18,
		markers: [],
		controls: [
			{
				id: 1,
				iconPath: '/source/map-marker/marker-location.png',
				position: {left: 5, top: 295, width: 40, height: 40},
				clickable: true
			},
		],
		polyline: null,
		online: null,
		offline: null,
		busLine: null
	},

	regionChange: function(e) {

	},

	markerTap: function(e) {
		var instance = this;
		var id = e.markerId;
		/* 车辆ID区段: 1~50，其余为站点ID */
		if (id > 50) {
			id = id - 50;
			wx.navigateTo({url: '/pages/stopInfo/index?id=' + id});
		}
	},

	controlTap: function(e) {
		if (e.controlId == 1) {
			this.mapCtx.moveToLocation();
		}
	},

	onLoad: function(options) {},

	onReady: function() {
		this.mapCtx = wx.createMapContext('busMap');
	},

	onShow: function() {
		var instance = this;
		app.getLocationInfo(function(locationInfo) {
			console.log('map', locationInfo);
		});
		this.loadLocation();
		this.loadBusLine();
		this.refreshDevice();
		interval = setInterval(function() {
			instance.loadLocation();
			instance.refreshDevice();
		}, 5000);
	},

	onHide: function() {
		clearInterval(interval);
	},

	onUnload: function() {},

	onPullDownRefresh: function() {},

	onReachBottom: function() {},

	onShareAppMessage: function() {},

	loadLocation: function() {
		var instance = this;
		wx.request({
			url: 'https://hbus.scau.edu.cn/wxss/wxss.GetLocation.php',
			method: 'GET',
			success: function(res) {
				instance.setData({markers: res.data});
			},
			fail: function() {
				instance.loadStopLocation();
			}
		});
	},

	loadBusLine: function() {
		var instance = this;
		wx.request({
			url: 'https://hbus.scau.edu.cn/wxss/wxss.GetPolyline.php',
			method: 'GET',
			success: function(res) {
				instance.setData({lineList: res.data});
			},
			fail: function() {
				instance.loadBusLine();
			}
		});
	},

	showBusLine: function(e) {
		var instance = this;
		var lineId = parseInt(e.currentTarget.id);
		wx.request({
			url: 'https://hbus.scau.edu.cn/wxss/wxss.GetPolyline.php',
			method: 'GET',
			data: {id: lineId},
			success: function(res) {
				instance.setData({polyline: [res.data]});
			},
			fail: function() {
				instance.showBusLine(e);
			}
		});
	},

	refreshDevice: function() {
		var instance = this;
		wx.request({
			url: 'https://hbus.scau.edu.cn/wxss/wxss.GetRealTimeStatus.php',
			method: 'GET',
			success: function(res) {
				instance.setData({
					online: res.data.online,
					offline: res.data.offline
				});
			},
			fail: function() {
				instance.refreshDevice();
			}
		});
	}

});