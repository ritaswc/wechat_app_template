Page({
	kuaidiInfo:function(){
		wx.navigateTo({
	      url: '../kuaidi_info/kuaidi_info'
	    })
	},
	toProductDetails:function(){
		 wx.navigateTo({
	      url: '../../details/index'
	    })
	},
	todeliver:function(){
		 wx.navigateTo({
	      url: '../deliver_info/deliver'
	    })
	}
});