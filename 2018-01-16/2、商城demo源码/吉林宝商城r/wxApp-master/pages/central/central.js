//userhome.js
Page({
	toOrder:function() {
		 wx.navigateTo({
	      url: 'seller_order/seller_order'
	    })
	},
	toAddress:function (){
		wx.navigateTo({
	      url: 'address/address'
	    })
	},
	tocollectStore:function (){
		wx.navigateTo({
	      url: 'collect_store/collect_store'
	    })
	},
	toclllectProduct:function (){
		wx.navigateTo({
	      url: 'collect_product/collect_product'
	    })
	},
	toBuyerOrder:function (){
		wx.navigateTo({
	      url: 'buyer_order/buyer_order'
	    })
	}
})