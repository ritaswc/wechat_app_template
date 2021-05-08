const AV = require('../../../utils/av-weapp.js')
Page({
	data: {
		amount : 0,
		carts: [],
		addressList: [],
		addressIndex: 0
	},
	addressObjects: [],
	onLoad: function (options) {
		this.readCarts(options);
	},
	onShow: function () {
		this.loadAddress();
	},
	readCarts: function (options) {
		var that = this;
		// from carts
		// amount
		var amount = parseFloat(options.amount);
		this.setData({
			amount: amount
		});

		// cartIds str
		var cartIds = options.cartIds;
		var cartIdArray = cartIds.split(',');
		// restore carts object
		for (var i = 0; i < cartIdArray.length; i++) {
			var query = new AV.Query('Cart');
			query.include('goods');
			query.get(cartIdArray[i]).then(function (cart) {
				var carts = that.data.carts == undefined ? [] : that.data.carts;
				carts.push(cart);
				that.setData({
					carts: carts
				});
			}, function (error) {

			});
		}
	},
	confirmOrder: function () {
		// submit order
		var carts = this.data.carts;
		var that = this;
		var user = AV.User.current();
		var order = new AV.Object('Order');
		order.set('user', user);
		order.set('status', 0);
		order.set('amount', this.data.amount);
		// set address
		var address = this.addressObjects[this.data.addressIndex];
		order.set('address', address);
		order.save().then(function (saveResult) {
			if (saveResult) {
				// OrderGoodsMap数组，批量提交
				var orderGoodsMapArray = [];
				// create buys & delete carts
				for (var i = 0; i < carts.length; i++) {
					// 创建订单商品中间表OrderGoodsMap
					var orderGoodsMap = AV.Object('OrderGoodsMap');
					// 遍历购物车对象
					// move cart to buy
					var cart = carts[i];
					orderGoodsMap.set('order', saveResult);
					orderGoodsMap.set('goods', cart.get('goods'));
					orderGoodsMap.set('quantity', cart.get('quantity'));
					orderGoodsMap.set('user', cart.get('user'));
					cart.destroy();
					orderGoodsMapArray.push(orderGoodsMap);
				}
				AV.Object.saveAll(orderGoodsMapArray).then(function () {
	            	// 保存到云端
	            	wx.navigateTo({
	               		url: '../../../../../payment/payment?orderId=' + order.get('objectId') + '&totalFee=' + that.data.amount
	            	});
					
				});
			}
		});
	},
	loadAddress: function () {
		var that = this;
		var user = AV.User.current();
		var query = new AV.Query('Address');
		query.equalTo('user', user);
		query.find().then(function (address) {
			var addressList = [];
			var addressObjects = [];
			for (var i = 0; i < address.length; i ++) {
				// find the default address
				if (address[i].get('isDefault') == true) {
					that.setData({
						addressIndex : i
					});
				}
				addressList.push(address[i].get('detail'));
			}
			that.setData({
				addressList: addressList
			});
			that.addressObjects = address;
		});
	},
	bindPickerChange: function (e) {
		this.setData({
	    	addressIndex: e.detail.value
	    })
	},
	bindCreateNew: function () {
		var addressList = this.data.addressList;
		if (addressList.length == 0) {
			wx.navigateTo({
				url: '../../address/add/add'
			});
		}
	}
})