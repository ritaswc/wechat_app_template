var app = getApp();
Page({
	data: {
		img_url: 'http://appuat.huihuishenghuo.com/img/',
		number: 0,
		len: 0,
		payType: 'hb',
		checkhb: false,
		checkwx: true,
		checkqb: false,
		pay: false,
		focus: false
	},
	onLoad: function () {
	},
	onShow: function () {
		wx.setNavigationBarTitle({
			title: '汇汇演示'
		})
	},
	tapCheck: function (e) {
		var id = e.currentTarget.dataset.id;
		if (id == '1') {
			this.setData({
				checkhb: true,
				checkwx: false,
				checkqb: false
			})
		} else if (id == '2') {
			this.setData({
				checkhb: false,
				checkwx: true,
				checkqb: false
			})
		} else {
			this.setData({
				checkhb: false,
				checkwx: false,
				checkqb: true
			})
		}
	},
	numTap: function (e) {
		var id = e.currentTarget.id;
		var num = this.data.number;
		if (id == '1') {
			if (num == 0) {
				this.setData({
					number: num
				})
			} else {
				this.setData({
					number: num - 1
				})
			}
		} else {
			this.setData({
				number: num + 1
			})
		}
	},
	bindButtonTap: function () {
		var pay = this.data.checkwx;
		if (pay) {

			var timestamp = Date.parse(new Date());

			wx.requestPayment({
				'timeStamp': timestamp,
				'nonceStr': 'asd123123asd',
				'package': '12',
				'signType': 'MD5',
				'paySign': 'stringA&key=192006250b4c09247ec02edce69f6a2d',
				'success': function (res) {
				},
				'fail': function (res) {
				}
			})
		} else {
			this.setData({
				focus: true,
				pay: true
			})
		}
	},
	bindHideKeyboard: function (e) {
		var lens = e.detail.value.length;
		this.setData({
			len: lens
		})
		if (e.detail.cursor == 6) {
			wx.hideKeyboard();
			this.setData({
				focus: false,
				pay: false
			})
		}
	}
});

