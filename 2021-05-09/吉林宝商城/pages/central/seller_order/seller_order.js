Page({
	allOrderPage:function (){
		wx.navigateTo({
	      url: '../seller_allOrder/seller_allOrder'
	    })
	},
	toOrderDetail:function () {
		wx.navigateTo({
	      url: '../order_detail/order_details'
	    })
	}
	
});