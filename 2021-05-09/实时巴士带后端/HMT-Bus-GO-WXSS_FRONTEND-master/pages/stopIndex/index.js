/**
 *	HMT Bus GO! (WXSS VER.)
 *
 *	@author CRH380A-2722 <609657831@qq.com>
 *	@copyright 2016-2017 CRH380A-2722
 *	@license MIT
 *	@note 页面逻辑
 */

Page({

	data: {
		stopList: null
	},

	onLoad: function(options) {},

	onReady: function() {},

	onShow: function() {
		this.loadStopList();
	},

	onHide: function() {},

	onUnload: function() {},

	onPullDownRefresh: function() {},

	onReachBottom: function() {},

	onShareAppMessage: function() {},

	formSubmit: function(e) {
		var formData = e.detail.value;
		var link = '/pages/stopSearch/index?name=' + formData.name;
		wx.navigateTo({url: link});
	},

	loadStopList: function() {
		var instance = this;
		wx.showLoading({
			title: '拉取站点列表',
			mask: false
		});
		wx.request({
			url: 'https://hbus.scau.edu.cn/wxss/wxss.GetStopList.php',
			method: 'GET',
			success: function(res) {
				instance.setData({stopList: res.data.stopList});
			},
			fail: function() {
				wx.showModal({
					title: '请求超时',
					content: '可能是您的网络环境不太好，亦或者是服务端出现了故障',
					confirmText: '重新加载',
					cancelText: '取消',
					success: function(res) {
						if (res.confirm) {
							instance.loadStopList();
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