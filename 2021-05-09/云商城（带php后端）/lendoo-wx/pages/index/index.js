const AV = require('../../utils/av-weapp.js')
Page({
	data: {
		banner: [],
		goods: [],
		bannerHeight: Math.ceil(290.0 / 750.0 * getApp().screenWidth)
	},
	onLoad: function (options) {
		this.loadBanner();
		this.loadMainGoods();
		this.getInviteCode(options);
	},
	getInviteCode: function (options) {
		if (options.uid != undefined) {
			wx.showToast({
				title: '来自用户:' + options.uid + '的分享',
				icon: 'success',
				duration: 2000
			})
		}
	},
	loadBanner: function () {
		var that = this;
		var query = new AV.Query('Banner');
		// query.include('image');
		query.find().then(function (bannerObjects) {
			var banner = [];
			for (var i = 0; i < bannerObjects.length; i++) {
				banner.push(bannerObjects[i].get('image').get('url'));
			}
			that.setData({
				banner: banner
			});
		});
	},
	loadMainGoods: function () {
		var that = this;
		var query = new AV.Query('Goods');
		query.equalTo('isHot', true);
		query.find().then(function (goodsObjects) {
			that.setData({
				goods: goodsObjects
			});
		});
	},
	showDetail: function (e) {
		var index = e.currentTarget.dataset.index;
		var goodsId = this.data.goods[index].id;
		wx.navigateTo({
			url: "../goods/detail/detail?objectId=" + goodsId
		});
	},
	showCategories: function () {
		// wx.navigateTo({
		// 	url: "../category/category"
		// });
		wx.switchTab({
			url: "../category/category"
		});
	},
	showOrders: function () {
		wx.navigateTo({
			url: "../order/list/list?status=1"
		});
	},
	onShareAppMessage: function () {
		return {
			title: '灵动开源电商系统',
			desc: '一个基于LeanCloud开发的开源电商系统',
			path: '/pages/index/index?uid=4719784'
		}
	},
	showGoods: function () {
		wx.navigateTo({
			url: '../goods/detail/detail?objectId=5816e3b22e958a0054a1d711'
		});
	}
})