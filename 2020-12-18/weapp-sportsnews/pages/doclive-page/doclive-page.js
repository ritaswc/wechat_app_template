const newsdata = require('../../libraries/newsdata.js');
Page({
	data: {
		title: '',
		loading: true,
		winWidth: 0,
		winHeight: 0,
		item: { //这样写为了兼容
		},
		localParams: '',
		lives: [], //直播数据
		chats: [], //聊天数据
		current: 0,
		timer: null
	},
	dataInit(params) {
		let option = JSON.parse(params.option);
		let data = {};
		data.sportsLiveExt = option;
		this.setData({
			item: data,
			loading: false
		});
	},
	refeshLive(params) {
		//http://sports.live.ifeng.com/API/  LiveAPI.php?matchid=5261
		newsdata.findLive('LiveAPI.php', {
				matchid: this.data.item.sportsLiveExt.matchid
			})
			.then(d => {
				this.setData({
					lives: d,
					loading: false
				})
			})
			.catch(e => {
				console.error(e)
				this.setData({
					movies: [],
					loading: false
				})
			})
	},
	refeshChat(params) {
		//http://sports.live.ifeng.com/API/ChatAPI.php?matchid=5261&type=init&chatid=1807610
		newsdata.findLive('ChatAPI.php', {
				matchid: this.data.item.sportsLiveExt.matchid,
				type: 'init',
				chatid: 1807610
			})
			.then(d => {
				this.setData({
					chats: d,
					loading: false
				})
			})
			.catch(e => {
				console.error(e)
				this.setData({
					movies: [],
					loading: false
				})
			})
	},
	refeshNews(params) {

	},
	onceRefesh(current) {
		switch (current) {
			case 0:
				this.refeshLive();
				break;
			case 1:
				this.refeshChat();
				break;
			case 2:
				this.refeshNews();
				break;
			default:
				break;
		}
	},
	autoRefesh(timeout) {
		let that = this;
		clearInterval(this.data.timer);
		this.setData({
			timer: setInterval(() => {
				this.onceRefesh(that.data.current);
			}, timeout)
		});
	},
	toggleTab(params) { //切换tab栏
		let tabId = params.currentTarget.dataset.id;
		this.setData({
			current: tabId
		});
		this.onceRefesh(tabId);
	},
	slideSwiper(params) { //滑动轮播图
		let swiperId = params.detail.current;
		this.setData({
			current: swiperId
		});
		this.onceRefesh(swiperId);

	},
	onLoad(params) {
		this.setData({ //存储数据留着给刷新用
			localParams: params,
		});
		this.dataInit(params); //初始化表头数据

		let that = this; //获取设备信息
		wx.getSystemInfo({
			success: function(res) {
				// console.log(res)
				that.setData({
					winWidth: res.windowWidth,
					winHeight: res.windowHeight - 150
				});
			}
		});
		this.onceRefesh(this.data.current); //初始化数据
		this.autoRefesh(10000); //自动刷新直播或聊天的数据,时间间隔10s
	},
	onUnload() {//页面返回撤毁
		console.log('onUnload');
		clearInterval(this.data.timer);
	},
	onShow() {//从后台进入前台
		this.autoRefesh(10000); 
	},
	onHide() {//进入后台
		console.log('onHide');
		clearInterval(this.data.timer);
	},
	onPullDownRefresh() {
		this.dataInit(this.data.localParams);
		this.onceRefesh(this.data.current);
		wx.stopPullDownRefresh();
	},
    //右上角分享功能
    // onShareAppMessage: function (res) {
    //     var that = this;
    //     return {
    //         title: 'Sports News',
    //         //path: '/pa
    //         //右上角分享功能
    //         onShareAppMessage: function (res) {
    //             var that = this;
    //             return {
    //                 title: 'Sports News',
    //                 //path: '/pages/main-page/main-page?id=' + that.data.scratchId,
    //                 success: function (res) {
    //                     // 转发成功
    //                     wx.showToast({
    //                         title: '转发成功！',
    //                     })
    //                     that.shareClick();
    //                 },
    //                 fail: function (res) {
    //                     // 转发失败
    //                     wx.showToast({
    //                         icon: 'none',
    //                         title: '转发失败',
    //                     })
    //                 }
    //             }
    //         }
    //     }
    // }
});