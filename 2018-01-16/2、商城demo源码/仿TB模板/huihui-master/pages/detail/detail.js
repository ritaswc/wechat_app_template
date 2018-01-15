var app = getApp();
var server = require('../../utils/server');
Page({
	data: {
		img_url: 'http://appuat.huihuishenghuo.com/img/'
	},
	onLoad: function () {

	},
	onShow: function () {

	},
	onScroll: function (e) {
		if (e.detail.scrollTop > 100 && !this.data.scrollDown) {
			this.setData({
				scrollDown: true
			});
		} else if (e.detail.scrollTop < 100 && this.data.scrollDown) {
			this.setData({
				scrollDown: false
			});
		}
	},
	EventHandle: function () {
		wx.navigateTo({
			url: 'vouchers',
			success: function (res) {
				// success
			},
			fail: function () {
				// fail
			},
			complete: function () {
				// complete
			}
		})
	},
	toPay: function () {
		wx.navigateTo({
			url: '../pay/pay',
			success: function (res) {
				// success
			},
			fail: function () {
				// fail
			},
			complete: function () {
				// complete
			}
		})
	},
	commentTap: function () {
		wx.navigateTo({
			url: 'comment',
			success: function (res) {
				// success
			},
			fail: function () {
				// fail
			},
			complete: function () {
				// complete
			}
		})
	},
	makePhoneCall: function () {
		wx.makePhoneCall({
			phoneNumber: '13553835046' //仅为示例，并非真实的电话号码
		})
	}
});