var app = getApp()

Page({
	data: {
		userInfo: {},
		items: [
			{
				icon: '../../src/img/bookFav.png',
				text: '我的收藏',
				path: '../fav/fav'
			},
		],
		settings: [
			{
				icon: '../../src/img/clear.png',
				text: '清除缓存',
				path: '0.0KB'
			}
		]
	},

	onPullDownRefresh: function () {
		wx.stopPullDownRefresh();
	},
	/* 页面加载 */
	onLoad: function () {
		var that = this;
		app.getUserInfo(function (res) {
			that.setData({
				userInfo: res
			})
		})
	},

	/* 页面显示 */
	onShow: function () {
		var that = this;
		that.showCurrentStorage();
	},

	/* 显示我的收藏 */
	navigateTo: function (e) {
		var index = e.currentTarget.dataset.index;
		var path = e.currentTarget.dataset.path;
		wx.navigateTo({
			url: path
		});
	},

	/* 显示当前缓存 */
	showCurrentStorage: function () {
		var that = this;
		wx.getStorageInfo({
			success: function (res) {
				that.setData({
					'settings[0].path': res.currentSize + 'KB'
				})
			}
		})
	},

	/* 清除缓存 */
	bindtap: function (e) {
		var that = this;
		var index = e.currentTarget.dataset.index;
		var path = e.currentTarget.dataset.path;
		wx.showModal({
			title: '友情提示',
			content: '确定要清除缓存吗？',
			success: function (res) {
				if (res.confirm) {
					wx.clearStorage();
					that.showCurrentStorage();
				}
			}
		})
	}
})