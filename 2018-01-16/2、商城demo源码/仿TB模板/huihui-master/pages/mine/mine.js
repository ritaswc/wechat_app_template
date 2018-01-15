var app = getApp();
var server = require('../../utils/server');
Page({
	data: {
		img_url: 'http://appuat.huihuishenghuo.com/img/'
	},
	onLoad: function () {
	},
	onShow: function () {
		this.setData({
			userInfo: app.globalData.userInfo
		});
	},
	setTap: function () {
		wx.navigateTo({
			url: 'account',
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
	voucherTap: function () {
		wx.navigateTo({
			url: 'voucher',
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
	WalletTap: function () {
		wx.navigateTo({
			url: 'wallet?type=1',
			success: function (res) {

			},
			fail: function () {
				// fail
			},
			complete: function () {
				// complete
			}
		})
	},
	hWalletTap: function () {
		wx.navigateTo({
			url: 'wallet?type=2',
			success: function (res) {

			},
			fail: function () {
				// fail
			},
			complete: function () {
				// complete
			}
		})
	},
	collectionTap: function () {
		wx.navigateTo({
			url: 'collection',
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
	prizeTap: function () {
		wx.navigateTo({
			url: 'prize',
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
	contactTap: function () {
		wx.makePhoneCall({
			phoneNumber: '13553835046'
		})
	}
});

