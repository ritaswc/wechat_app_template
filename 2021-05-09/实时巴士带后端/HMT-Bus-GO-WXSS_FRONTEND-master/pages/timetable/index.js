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
		type: null,
		typeNum: null,
		lineName: null,
		lineStart: null,
		lineEnd: null,
		timetable: null,
		tableCount: null
	},

	onLoad: function(options) {
		var instance = this;
		instance.loadTimetable(options.id, options.type);
	},

	onReady: function() {},

	onShow: function() {},

	onHide: function() {},

	onUnload: function() {},

	onPullDownRefresh: function() {},

	onReachBottom: function() {},

	onShareAppMessage: function() {},

	loadTimetable: function(id, type) {
		var instance = this;
		var lineId = id;
		var tableType = null;
		if (type == 'weekend') {
			tableType = 2;
			instance.setData({
				type: '周末/节假日',
				typeNum: tableType
			});
		} else {
			tableType = 1;
			instance.setData({
				type: '工作日',
				typeNum: tableType
			});
		}
		wx.showLoading({
			title: '拉取时刻表',
			mask: true
		});
		wx.request({
			url: 'https://hbus.scau.edu.cn/wxss/wxss.GetTimetable.php',
			method: 'GET',
			data: {
				id: lineId,
				type: tableType
			},
			success: function(res) {
				var data = res.data;
				instance.setData({
					lineName: data.line_name,
					lineStart: data.line_start,
					lineEnd: data.line_end,
					timetable: data.timetable,
					tableCount: data.timetable.length
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
							instance.loadTimetable(id, type);
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