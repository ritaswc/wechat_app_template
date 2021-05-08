Page({
	data: {
		src: '',
		controls: true,
		loading: true,
	},
	hideControl() {
		this.setData({
			controls: !this.data.controls,
		});
	},
	onLoad(params) {
        //console.log(params);
		this.setData({
			src: params.videoUrl,
			loading: false,
		});
	},
	// 当开始/继续播放时触发play事件
	bindplay() {

	},
	// 当暂停播放时触发 pause 事件
	bindpause() {

	},
	//EventHandle		当播放到末尾时触发 ended 事件
	bindended() {

	},
	// 播放进度变化时触发，event.detail = {currentTime: '当前播放时间'} 。触发频率应该在 250ms 一次
	bindtimeupdate() {

	},
    // //右上角分享功能
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