const AV = require('../../../utils/av-weapp.js')
var that;
var deltaX = 0;
var minValue = 1;
var app = getApp();
Page({
	data: {
		value: 0,
		canvasHeight: 80
	},
	onLoad: function (options) {
		that = this;
		// 绘制标尺
		that.drawRuler();
		// 绘制三角形游标
		that.drawCursor();
	},
	drawRuler: function() {

		/* 1.定义变量 */

		// 1.1 定义原点与终点，x轴方向起点与终点各留半屏空白
		var origion = {x: app.screenWidth / 2, y: that.data.canvasHeight};
		var end = {x: app.screenWidth / 2, y: that.data.canvasHeight};
		// 1.2 定义刻度线高度
		var heightDecimal = 50;
		var heightDigit = 25;
		// 1.3 定义文本标签字体大小
		var fontSize = 20;
		// 1.4 最小刻度值
		// 已经定义在全局，便于bindscroll访问
		// 1.5 总刻度值
		var maxValue = 200;
		// 1.6 当前刻度值
		var currentValue = 20;
		// 1.7 每个刻度所占位的px
		var ratio = 10;
		// 1.8 画布宽度
		var canvasWidth = maxValue * ratio + app.screenWidth - minValue * ratio;
		// 设定scroll-view初始偏移
		that.setData({
			canvasWidth: canvasWidth,
			scrollLeft: (currentValue - minValue) * ratio
		});

		/* 2.绘制 */

		// 2.1初始化context
		const context = wx.createCanvasContext('canvas-ruler');
		// 遍历maxValue
		for (var i = 0; i <= maxValue; i++) {
			context.beginPath();
			// 2.2 画刻度线
			context.moveTo(origion.x + (i - minValue) * ratio, origion.y);
			// 画线到刻度高度，10的位数就加高
			context.lineTo(origion.x + (i - minValue) * ratio, origion.y - (i % ratio == 0 ? heightDecimal : heightDigit));
			// 设置属性
			context.setLineWidth(2);
			// 10的位数就加深
			context.setStrokeStyle(i % ratio == 0 ? 'gray' : 'darkgray');
			// 描线
			context.stroke();
			// 2.3 描绘文本标签
			context.setFillStyle('gray');
			if (i % ratio == 0) {
				context.setFontSize(fontSize);
				// 为零补一个空格，让它看起来2位数，页面更整齐
				context.fillText(i == 0 ? ' ' + i : i, origion.x + (i - minValue) * ratio - fontSize / 2, fontSize);
			}
			context.closePath();
		}

		// 2.4 绘制到context
		context.draw();
	},
	drawCursor: function () {
		/* 定义变量 */
		// 定义三角形顶点 TODO x
		var center = {x: app.screenWidth / 2, y: 5};
		// 定义三角形边长
		var length = 20;
		// 左端点
		var left = {x: center.x - length / 2, y: center.y + length / 2 * Math.sqrt(3)};
		// 右端点
		var right = {x: center.x + length / 2, y: center.y + length / 2 * Math.sqrt(3)};
		// 初始化context
		const context = wx.createCanvasContext('canvas-cursor');
		context.moveTo(center.x, center.y);
		context.lineTo(left.x, left.y);
		context.lineTo(right.x, right.y);
		// fill()填充而不是stroke()描边，于是省去手动回归原点，context.lineTo(center.x, center.y);
		context.setFillStyle('#48c23d');
		context.fill();
		context.draw();
	},
	bindscroll: function (e) {
		// deltaX 水平位置偏移位，每次滑动一次触发一次，所以需要记录从第一次触发滑动起，一共滑动了多少距离
		deltaX += e.detail.deltaX;
		// 数据绑定
		that.setData({
			value: Math.floor(- deltaX / 10 + minValue)
		});
		console.log(deltaX)
	},
	donateButtonTapped: function () {
		// 生成订单
		var order = new AV.Object('Donate');
		order.set('user', AV.User.current());
		order.set('amount', that.data.value);
		order.set('status', false);
		// 保存订单
		order.save().then(function (savedOrder) {
			// 保存成功
			var orderId = savedOrder.get('objectId');
			// 发起支付
			//统一下单接口对接
			wx.request({
				url: 'https://lendoo.leanapp.cn/index.php/WXPay',
				data: {
					openid: getApp().openid,
					body: '赞赏捐赠',
					tradeNo: orderId,
					totalFee: parseFloat(that.data.value) * 100
				},
				method: 'POST',
				header: {
					'content-type': 'application/x-www-form-urlencoded'
				},
				success: function (response) {
					// 发起支付
					wx.requestPayment({
						'timeStamp': response.data.timeStamp,
						'nonceStr': response.data.nonceStr,
						'package': response.data.package,
						'signType': 'MD5',
						'paySign': response.data.paySign,
						'success':function(res){
							wx.showModal({
								title: '谢谢赞赏',
								showCancel: false
							});
							// update order
							var query = new AV.Query('Donate');
							query.get(orderId).then(function (order) {
								order.set('status', true);
								order.save();
							}, function (err) {
								
							});
						}
					});
				}
			});
		});
	}
});