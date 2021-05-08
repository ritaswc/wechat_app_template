/**
 *	HMT Bus GO! (WXSS VER.)
 *
 *	@author CRH380A-2722 <609657831@qq.com>
 *	@copyright 2016-2017 CRH380A-2722
 *	@license MIT
 *	@note 页面逻辑
 */

var interval = null;

Page({

	data: {switchChecked: false},

	onLoad: function(options) {
		var instance = this;
		instance.loadLineInfo(options.id);
	},

	onReady: function() {},

	onShow: function() {},

	onHide: function() {},

	onUnload: function() {
		clearInterval(interval);
		this.setData({
			switchChecked: false
		});
	},

	onPullDownRefresh: function() {},

	onReachBottom: function() {},

	onShareAppMessage: function() {},

	loadLineInfo: function(id) {
		var instance = this;
		var lineId = id;
		wx.showLoading({
			title: '拉取线路详情',
			mask: true
		});
		wx.request({
			url: 'https://hbus.scau.edu.cn/wxss/wxss.GetLineInfo.php',
			method: 'GET',
			data: {id: lineId},
			success: function(res) {
				instance.setData({
					lineInfo: res.data.lineInfo,
					totalStops: res.data.totalStops,
					stops: res.data.stops
				});
			},
			fail: function() {
				wx.showModal({
					title: '请求超时',
					content: '可能是您的网络环境不太好，亦或者是服务端出现了故障',
					confirmText: '重新加载',
					cancelText: '取消',
					success: function(res) {
						if (res.confirm) {
							instance.loadLineInfo(lineId);
						}
					}
				});
			},
			complete: function() {
				wx.hideLoading();
			}
		});
	},

	refresh: function(e) {
		var lineId = e.currentTarget.id;
		var instance = this;
		wx.showLoading({
			title: 'Loading',
			mask: true
		});
		wx.request({
			url: 'https://hbus.scau.edu.cn/wxss/wxss.RefreshStop.php',
			method: 'GET',
			data: {id: lineId},
			success: function(res) {
				instance.setData({stops: res.data.stops});
			},
			fail: function() {
				wx.showModal({
					title: '请求超时',
					content: '可能是您的网络环境不太好，亦或者是服务端出现了故障',
					confirmText: '重新加载',
					cancelText: '取消',
					success: function(res) {
						if (res.confirm) {
							instance.refresh();
						}
					}
				});
			},
			complete: function() {
				wx.hideLoading();
			}
		});
	},

	autoRefresh: function(e) {
		var instance = this;
		if (e.detail.value) {
			interval = setInterval(function() {
				instance.refresh(e);
			}, 5000);
		} else {
			clearInterval(interval);
		}
	}

});