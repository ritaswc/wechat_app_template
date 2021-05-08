// pages/login/login.js
Page({
	data: {
		code_text: '获取验证码',
		wait: 60,
		phone: '',
		vcode: '',
		disabled: false
	},
	onLoad: function (options) {

	},
	onBindInputPhone: function (e) {
		this.setData({
			phone: e.detail.value
		})
	},
	onBindInputCode: function (e) {
		this.setData({
			vcode: e.detail.value
		})
	},
	getCode: function (e) {
		console.log(this.data.phone);
		if (this.data.phone == '') {
			wx.showToast({
				title: "请检查手机号",
				icon: 'loading',
				duration: 1500
			});
		} else {
			var reg = /^[1][34578][0-9]{9}$/;
			if (true) {
				this.time();
				this.setData({
					disabled: !this.data.disabled
				})
				wx.showToast({
					title: "验证码已发送",
					icon: '成功',
					duration: 2000
				});
				var url = 'https://wx.viparker.com/valetparking/api/web/index.php/user/send-sms'
				wx.request({
					url: url,
					header: {
						"Content-Type": "application/x-www-form-urlencoded"
					},
					data: {
						phone: this.data.phone
					},
					method: "POST",
					success: function (res) {
						console.log(res)
					}
				})
			} else {
				wx.showToast({
					title: '请输入有效的手机号',
					icon: 'loading',
					duration: 1500
				})
			}
		}
	},
	time: function () {
		var that = this;
		var wait = that.data.wait
		if (that.data.wait == 0) {
			that.setData({
				code_text: "获取验证码",
				wait: 60
			});
			return;
		}
		var time = setTimeout(function () {
			that.setData({
				code_text: wait + "s",
				wait: wait - 1,
			});
			that.time();
		}, 1000);
	},
	bindSuccess: function () {
		wx.request({
			url: 'https://wx.viparker.com/valetparking/api/web/index.php/user/bind-phone ',
			header: {
				"Content-Type": "application/x-www-form-urlencoded"
			},
			data: {
				openid: wx.getStorageSync('openid'),
				phone: this.data.phone,
				code: this.data.vcode,
				nickname: wx.getStorageSync('nickName'),
				sex: wx.getStorageSync('gender'),
				avatarurl: wx.getStorageSync('avatarUrl')
			},
			method: "POST",
			success: function (res) {
				console.log(res)
				wx.navigateBack({
					delta: 1
				})
			}
		})
	}

})
