/**
 *  HMT Bus GO! (WXSS VER.)
 *
 *  @author CRH380A-2722 <609657831@qq.com>
 *  @copyright 2016-2017 CRH380A-2722
 *  @license MIT
 *	@note 小程序逻辑
 */

/* 注册小程序 */

App({

	onLaunch: function(options) {},

	onShow: function(options) {},

	onHide: function() {},

	onError: function(msg) {
		console.log(msg);
	},

	globalData: {
		locationInfo: null
	},

	getLocationInfo: function(cb){
	var app = this;
	if (this.globalData.locationInfo) {
			cb(this.globalData.locationInfo);
		} else {
			wx.getLocation({
				type: 'gcj02', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标
				success: function(res) {
					app.globalData.locationInfo = res;
					cb(app.globalData.locationInfo);
				},
				fail: function() {
					// fail
				},
				complete: function() {
					// complete
				}
			});
		}
	}

});
