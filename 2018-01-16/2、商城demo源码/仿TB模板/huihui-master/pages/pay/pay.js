var app = getApp();
Page({
	data:{
			img_url: 'http://appuat.huihuishenghuo.com/img/',
			checkType:true,
			focus:true,
			height:'440rpx'
	},
	checkTap:function(){
		var check=this.data.checkType;
		this.setData({
			checkType: !check
		})
	},
	paymentTap:function(){
		this.setData({
			height: '440rpx'
		})
	},
	payloseTap:function(){
		this.setData({
			focus:false,
			height: '0rpx'
		})
	},
	privilegeTap:function(){
		wx.navigateTo({
		  url: 'privilege',
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
	paycommentTap:function(){
		wx.navigateTo({
			url: 'paycomment',
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
})