const AV = require('../../../utils/av-weapp.js')

// 使用function初始化array，相比var initSubMenuDisplay = [] 既避免的引用复制的，同时方式更灵活，将来可以是多种方式实现，个数也不定的
function initSubMenuDisplay() {
	return ['hidden', 'hidden', 'hidden'];
}

//定义初始化数据，用于运行时保存
var initSubMenuHighLight = [
['','','','',''],
['',''],
['','','']
];

Page({
	data:{
		subMenuDisplay:initSubMenuDisplay(),
		subMenuHighLight:initSubMenuHighLight,
		goods: []
	},
	onLoad: function(options){
		var categoryId = options.categoryId;
		// 生成Category对象
		var category = AV.Object.createWithoutData('Category', categoryId);
		this.category = category;
		this.getGoods(category, 0);
	},
	getGoods: function(category, pageIndex){
		var pageSize = 7;
		var that = this;
		var query = new AV.Query('Goods');
        // 查询顶级分类，设定查询条件parent为null
        query.equalTo('category',category);
        // 分页查询
        query.limit(pageSize);// 最多返回 10 条结果
		query.skip(pageIndex * pageSize);// 跳过 20 条结果
		query.find().then(function (goods) {
        	// 关闭loading提示框
        	wx.hideToast();
        	// 让goods结果集迭加
        	var originGoods = that.data.goods;
        	// 如果初始有值，就合并；否则就是新数据集本身
        	var newGoods = originGoods.length > 0 ? originGoods.concat(goods) : goods;
        	that.setData({
        		goods: newGoods
        	});
        }).catch(function(error) {
        });
    },
    tapGoods: function(e) {
    	var objectId = e.currentTarget.dataset.objectId;
    	wx.navigateTo({
    		url:"../detail/detail?objectId=" + objectId
    	});
    },
    tapMainMenu: function(e) {
//		获取当前显示的一级菜单标识
var index = parseInt(e.currentTarget.dataset.index);
		// 生成数组，全为hidden的，只对当前的进行显示
		var newSubMenuDisplay = initSubMenuDisplay();
//		如果目前是显示则隐藏，反之亦反之。同时要隐藏其他的菜单
if(this.data.subMenuDisplay[index] == 'hidden') {
	newSubMenuDisplay[index] = 'show';
} else {
	newSubMenuDisplay[index] = 'hidden';
}
		// 设置为新的数组
		this.setData({
			subMenuDisplay: newSubMenuDisplay
		});
	},
	tapSubMenu: function(e) {
		// 隐藏所有一级菜单
		this.setData({
			subMenuDisplay: initSubMenuDisplay()
		});
		// 处理二级菜单，首先获取当前显示的二级菜单标识
		var indexArray = e.currentTarget.dataset.index.split('-');
		// 初始化状态
		// var newSubMenuHighLight = initSubMenuHighLight;
		for (var i = 0; i < initSubMenuHighLight.length; i++) {
			// 如果点中的是一级菜单，则先清空状态，即非高亮模式，然后再高亮点中的二级菜单；如果不是当前菜单，而不理会。经过这样处理就能保留其他菜单的高亮状态
			if (indexArray[0] == i) {
				for (var j = 0; j < initSubMenuHighLight[i].length; j++) {
					// 实现清空
					initSubMenuHighLight[i][j] = '';
				}
				// 将当前菜单的二级菜单设置回去
			}
		}

		// 与一级菜单不同，这里不需要判断当前状态，只需要点击就给class赋予highlight即可
		initSubMenuHighLight[indexArray[0]][indexArray[1]] = 'highlight';
		// 设置为新的数组
		this.setData({
			subMenuHighLight: initSubMenuHighLight
		});
	},
	onReachBottom: function () {
		this.getGoods(this.category, 1);
		wx.showToast({
			title: '加载中',
			icon: 'loading'
		})
	},
	onPullDownRefresh: function () {
		this.getGoods(this.category, 0);
	},
	addCart: function (e) {
		var objectId = e.currentTarget.dataset.objectId;
		var goods = AV.Object.createWithoutData('Goods', objectId);
		this.insertCart(goods);
	},
	insertCart: function (goods) {
		var that = this;
		// add cart
		var user = AV.User.current();
		// search if this goods exsit or not.if did exsit then quantity ++ updated cart object;
		// if not, create cart object
		// query cart
		var query = new AV.Query('Cart');
		query.equalTo('user', user);
		query.equalTo('goods', goods);
		// if count less then zero
		query.count().then(function (count) {
			if (count <= 0) {
				// if didn't exsit, then create new one
				var cart = AV.Object('Cart');
				cart.set('user', user);
				cart.set('quantity', 1);
				cart.set('goods', goods);
				cart.save().then(function(cart){
					that.showCartToast();
				},function(error) {
					console.log(error);
				});
			} else {
				// if exsit, get the cart self
				query.first().then(function(cart){
					// update quantity
					cart.increment('quantity', 1);
					// atom operation
					// cart.fetchWhenSave(true);
					that.showCartToast();
					return cart.save();
				}, function (error) {
					console.log(error);
				});
			}
		}, function (error) {

		});
	},
	showCartToast: function () {
		wx.showToast({
			title: '已加入购物车',
			icon: 'success',
			duration: 1000
		});
	}
});