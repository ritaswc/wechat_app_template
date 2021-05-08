/**
 *	HMT Bus GO! (WXSS VER.)
 *
 *	@author CRH380A-2722 <609657831@qq.com>
 *	@copyright 2016-2017 CRH380A-2722
 *	@license MIT
 *	@note 页面逻辑
 */

Page({

	data: {},

	onLoad: function(options) {
		var instance = this;
		instance.loadStopInfo(options.id);
	},

	onReady: function() {},

	onShow: function() {},

	onHide: function() {},

	onUnload: function() {},

	onPullDownRefresh: function() {},

	onReachBottom: function() {},

	onShareAppMessage: function() {},

	loadStopInfo: function(id) {
		var instance = this;
		var stopId = id;
		wx.showLoading({
			title: '拉取站点详情',
			mask: true
		});
		wx.request({
			url: 'https://hbus.scau.edu.cn/wxss/wxss.GetStopInfo.php',
			method: 'GET',
			data: {
				id: stopId
			},
			success: function(res) {
				instance.setData({
					stopInfo: res.data.stopInfo,
					totalLine: res.data.totalLine,
					lineList: res.data.lineList
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
							instance.loadStopInfo(stopId);
						}
					}
				});
			},
			complete: function() {
				wx.hideLoading();
			}
		});
	}

});