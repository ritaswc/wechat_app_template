var app = getApp();
Page({
	data: {
		show: true,
		img_url: 'http://appuat.huihuishenghuo.com/img/'
	},
	onLoad: function () {
	},
	onShow: function () {
	},
	onScroll: function (e) {
	},
	tapSearch: function () {
	},
	evaluationTap: function () {
		wx.navigateTo({
		  url: 'evaluation',
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
	},
	tapShow: function () {
		var isShow = this.data.show;
		this.setData({
			show: !isShow
		})
	},
});

