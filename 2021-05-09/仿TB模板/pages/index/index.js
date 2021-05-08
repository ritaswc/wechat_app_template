var app = getApp();
var server = require('../../utils/server');
Page({
	data: {
		img_url: 'http://appuat.huihuishenghuo.com/img/',
		filterId: 1,
		address: '定位中…',
		banners: [
			{
				id: 3,
				img: 'http://huihuishenghuo.com/huihui8/static/img/ban1.jpg',
				url: '',
				name: '摇一摇'
			},
			{
				id: 1,
				img: 'http://huihuishenghuo.com/huihui8/static/img/ban2.jpg',
				url: '',
				name: '净水器'
			},
			{
				id: 2,
				img: 'http://huihuishenghuo.com/huihui8/static/img/ban3.jpg',
				url: '',
				name: '旅拍啦'
			}
		],
		icons: [
			{
				id: 1,
				img: 'http://appuat.huihuishenghuo.com/img/index/icon_1.png',
				name: '美食',
			},
			{
				id: 2,
				img: 'http://appuat.huihuishenghuo.com/img/index/icon_2.png',
				name: '休闲娱乐',
			},
			{
				id: 3,
				img: 'http://appuat.huihuishenghuo.com/img/index/icon_3.png',
				name: '丽人',
			},
			{
				id: 4,
				img: 'http://appuat.huihuishenghuo.com/img/index/icon_4.png',
				name: '婚庆',
			},
			{
				id: 5,
				img: 'http://appuat.huihuishenghuo.com/img/index/icon_5.png',
				name: '购物',
			},
			{
				id: 6,
				img: 'http://appuat.huihuishenghuo.com/img/index/icon_6.png',
				name: '生活服务',
			},
			{
				id: 7,
				img: 'http://appuat.huihuishenghuo.com/img/index/icon_7.png',
				name: '酒店',
			},
			{
				id: 8,
				img: 'http://appuat.huihuishenghuo.com/img/index/icon_8.png',
				name: '景点',
			}
		],
		shops: app.globalData.shops
	},
	onLoad: function () {
		// var self = this;
		// wx.getLocation({
		// 	type: 'gcj02',
		// 	success: function (res) {
		// 		var latitude = res.latitude;
		// 		var longitude = res.longitude;
		// 		server.getJSON('/waimai/api/location.php', {
		// 			latitude: latitude,
		// 			longitude: longitude
		// 		}, function (res) {
		// 			console.log(res)
		// 			if (res.data.status != -1) {
		// 				self.setData({
		// 					address: res.data.result.ad_info.city
		// 				});
		// 			} else {
		// 				self.setData({
		// 					address: '定位失败'
		// 				});
		// 			}
		// 		});
		// 	}
		// })
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
	tapSearch: function () {
		wx.navigateTo({ url: '../search/search' });
	},
	toNearby: function (e) {
		var id = e.currentTarget.dataset.id;
		wx.setStorage({
			key: "shop_type",
			data: id
		})
		var url = '../shop/shop';
		wx.switchTab({
			url: url,
		})
	},
	toLocation: function (e) {
		wx.navigateTo({
			url: 'location',
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
	codeTap: function () {
		wx.scanCode({
			success: (res) => {
				wx.navigateTo({
					url: '../detail/detail',
					success: function (res) {
						wx.showToast({
							title: '扫码成功',
							icon: 'success',
							duration: 1000
						})
					},
					fail: function () {
						// fail
					},
					complete: function () {
						// complete
					}
				})
			},
			fail: (re) => {
				wx.showModal({
					content: '扫码失败,该扫码只支持扫描小程序二维码',
					success: function (res) {
						if (res.confirm) {
							wx.navigateBack({
							  delta: 1, // 回退前 delta(默认为1) 页面
							  success: function(res){
								// success
							  },
							  fail: function() {
								// fail
							  },
							  complete: function() {
								// complete
							  }
							})
						}
					}
				})

			}
		})
	}
});

