var app = getApp();
var server = require('../../utils/server');
Page({
	data: {
		img_url: 'http://appuat.huihuishenghuo.com/img/',
		filterId: 1,
		searchWords: '',
		placeholder: '输入商家、分类或商圈',
		shops: app.globalData.shops
	},
	onLoad: function () {
		
	},
	onShow: function () {
		this.setData({
			showResult: false
		});
	},
	inputSearch: function (e) {
		this.setData({
			searchWords: e.detail.value
		});
	},
	doSearch: function() {
		this.setData({
			showResult: true
		});
	},
	tapFilter: function (e) {
		
	}
});

