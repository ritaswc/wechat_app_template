// 获取到小程序实例
var app = getApp();
Page({
	data: {
		shop:{
			name:'味觉牛庄',
			desc:'凡在本店办理会员，一律享受8.8折优惠'
		},
		goods: {
			1: {
				id: 1,
				name: '果盘3',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 1014,
				price: 120
			},
			2: {
				id: 2,
				name: '龙舌兰',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 1029,
				price: 100
			},
			3: {
				id: 3,
				name: '方便面',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 1030,
				price: 5
			},
			4: {
				id: 4,
				name: '粉丝',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 1059,
				price: 5
			},
			5: {
				id: 5,
				name: '果盘1',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 1029,
				price: 130
			},
			6: {
				id: 6,
				name: '果盘2',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 1064,
				price: 150
			},
			7: {
				id: 7,
				name: '锐澳',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 814,
				price: 200
			},
			8: {
				id: 8,
				name: '尊尼获加',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 124,
				price: 220
			},
			9: {
				id: 9,
				name: '芝士华',
				pic: 'http://img1.gtimg.com/health/pics/hv1/138/79/2068/134491983.jpg',
				sold: 102,
				price: 300
			}
		},
		goodsList: [
			{
				id: 'hot',
				classifyName: '热销',
				goods: [1, 2, 3, 4, 5]
			},
			{
				id: 'new',
				classifyName: '小吃',
				goods: [1, 3]
			},
			{
				id: 'vegetable',
				classifyName: '果盘',
				goods: [1, 6, 5]
			},
			{
				id: 'mushroom',
				classifyName: '鸡尾酒',
				goods: [1, 7, 8, 9]
			},
			{
				id: 'food',
				classifyName: '主食',
				goods: [3, 4]
			}
		],
		cart: {
			count: 0,
			total: 0,
			list: {}
		},
		cartList:{},
		showCartDetail: false
	},
	// 生命周期函数--监听页面加载
	// 一个页面只会调用一次。
	onLoad: function (options) {
		var that = this
		//调用应用实例的方法获取全局数据
		app.getUserInfo(function(userInfo){
		//更新数据
		that.setData({
			userInfo: userInfo
		});
		that.update();
		console.log(userInfo)
		});
	},
	// 生命周期函数--监听页面初次渲染完成
	onReady: function(){},
	// 生命周期函数--监听页面显示
	// 每次打开页面都会调用一次
	onShow: function () {
		this.setData({
			classifySeleted: this.data.goodsList[0].id
		});
	},
	// 生命周期函数--监听页面隐藏
	// 当navigateTo或底部tab切换时调用
	onHide: function(){},
	// 生命周期函数--监听页面卸载
	// 当redirectTo或navigateBack的时候调用。
	onUnload:function(){},
	// 页面相关事件处理函数--监听用户下拉动作
	onPullDownRefresh:function(){},
	// 页面上拉触底事件的处理函数
	onReachBottom:function(){},

	// 开发者可以添加任意的函数或数据到
	//  object 参数中，在页面的函数中用 this 可以访问
	checkOrderSame: function(name){
		var list = this.data.goods;
		for(var index in list){
			if(list[index].name === name){
				return index;
			}
		}
		return false;
	},
	tapAddCart: function (e) {
		this.addCart(e.target.dataset.id);
	},
	tapReduceCart: function (e) {
		this.reduceCart(e.target.dataset.id);
	},
	addCart: function (id) {
		var num = this.data.cart.list[id] || 0;
		this.data.cart.list[id] = num + 1;
		this.countCart();
		var price = this.data.goods[id].price;
		var name  = this.data.goods[id].name;
		var img   = this.data.goods[id].pic;
		var list  = this.data.cartList;
		var sortedList = [];
		var index;
		if(index = this.checkOrderSame(name)){
			sortedList = list[index];
			var num = this.data.cart.list[id] || 0;
			num = num + 1;
		}
		else{
			var order = {
				"price" : price,
				"num" : 1,
				"name": name,
				'img':  img,
				"shopId": this.data.shopId,
				"shopName": this.data.shop.restaurant_name,
				"pay": 0,
			}
			list.push(order);
			
			sortedList = order;
		}
		this.setData({
			cartList: list,
		});
		console.log(list)
	},
	reduceCart: function (id) {
		var num = this.data.cart.list[id] || 0;
		if (num <= 1) {
			delete this.data.cart.list[id];
		} else {
			this.data.cart.list[id] = num - 1;
		}
		this.countCart();
	},
	countCart: function (index,lists) {
		var count = 0,
			total = 0;
		var goods;
		for (var id in this.data.cart.list) {
		    goods = this.data.goods[id];
			count += this.data.cart.list[id];
			total += goods.price * this.data.cart.list[id];
		}
		this.data.cart.count = count;
		this.data.cart.total = total;
		this.setData({
			cart: this.data.cart
		});
		// 存储订单页所需要的数据
		wx.setStorage({
			key: 'orderList',
			data: {
				count: this.data.cart.count,
				total: this.data.cart.total,
				list: this.data.cart.list,
			}
		})
		
	},
	follow: function () {
		this.setData({
			followed: !this.data.followed
		});
	},
	onGoodsScroll: function (e) {
		if (e.detail.scrollTop > 10 && !this.data.scrollDown) {
			this.setData({
				scrollDown: true
			});
		} else if (e.detail.scrollTop < 10 && this.data.scrollDown) {
			this.setData({
				scrollDown: false
			});
		}

		var scale = e.detail.scrollWidth / 570,
			scrollTop = e.detail.scrollTop / scale,
			h = 0,
			classifySeleted,
			len = this.data.goodsList.length;
		this.data.goodsList.forEach(function (classify, i) {
			var _h = 70 + classify.goods.length * (46 * 3 + 20 * 2);
			if (scrollTop >= h - 100 / scale) {
				classifySeleted = classify.id;
			}
			h += _h;
		});
		this.setData({
			classifySeleted: classifySeleted
		});
	},
	tapClassify: function (e) {
		var id = e.target.dataset.id;
		this.setData({
			classifyViewed: id
		});
		var self = this;
		setTimeout(function () {
			self.setData({
				classifySeleted: id
			});
		}, 100);
	},
	showCartDetail: function () {
		this.setData({
			showCartDetail: !this.data.showCartDetail
		});
	},
	hideCartDetail: function () {
		this.setData({
			showCartDetail: false
		});
	},
	submit: function (e) {
		var agrs = JSON.stringify(this.data.cart);
		console.log(agrs)
		wx.navigateTo({
		url: '../order/order?order=' + agrs
		})
		}
});