const AV = require('../../../utils/av-weapp.js')
var app = getApp()
Page({
	navigateToAddress: function () {
		wx.navigateTo({
			url: '../../address/list/list'
		});
	},
	navigateToOrder: function (e) {
		var status = e.currentTarget.dataset.status
		wx.navigateTo({
			url: '../../order/list/list?status=' + status
		});
	},
	logout: function () {
		if (AV.User.current()) {
			AV.User.logOut();
			wx.showToast({
				'title': '退出成功'
			});
		} else {
			wx.showToast({
				'title': '请先登录'
			});
		}
	},
	onShow: function () {
		var that = this;
		// 获得当前登录用户
		const user = AV.User.current();
		// 调用小程序 API，得到用户信息
		wx.getUserInfo({
			success: ({userInfo}) => {
				// 更新当前用户的信息，昵称头像等
                user.set(userInfo).save().then(user => {
			    	// 成功，此时可在控制台中看到更新后的用户信息
					that.setData({
						userInfo: userInfo
					});
			    }).catch(console.error);
			}
		});
	},
	chooseImage: function () {
		var that = this;
		wx.chooseImage({
			count: 1, // 默认9
			sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
			sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
			success: function (res) {
				// 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
				var tempFilePath = res.tempFilePaths[0];
				new AV.File('file-name', {
					blob: {
						uri: tempFilePath,
					},
				}).save().then(
				// file => console.log(file.url())
					function(file) {
						// 上传成功后，将所上传的头像设置更新到页面<image>中
						var userInfo = that.data.userInfo;
						userInfo.avatarUrl = file.url();
						that.setData({
							userInfo, userInfo
						});
					}
				).catch(console.error);
			}
		})
	},
	navigateToAboutus: function () {
		wx.navigateTo({
			url: '/pages/member/aboutus/aboutus'
		});
	},
	navigateToDonate: function () {
		wx.navigateTo({
			url: '/pages/member/donate/donate'
		});
	},
	navigateToShare: function () {
		wx.navigateTo({
			url: '/pages/member/share/share'
		});
	}
})