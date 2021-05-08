const newsdata = require('../../libraries/newsdata.js');
const htmlToWxml = require('../../libraries/htmlToWxml.js');
Page({
	data: {
		title: '',
    	loading: true,
		body: {},
		disclaimer: '',
		wxml: {},
        option: ''
	},

	onLoad(option) {
    console.log(option)
    let params = option
    newsdata.find('ng.com/ipadtestdoc', params)
    .then((res) => {
      console.log(res)
      let wxml = htmlToWxml.html2json(res.body.text);
      this.setData({wxml: wxml});

      this.setData({
        option: option,
        loading: false,
        body: res.body,
        disclaimer: res.disclaimer
      });

			})
			.catch(err => {
				this.setData({ title: '获取数据异常', loading: false })
				console.log(err);
			})
	},
	dingyue() {
		wx.showModal({
			title: '提示',
			content: '点击也没用，这个功能根本就没做',
			success: () => {},
			fail: () => {}
		});
	},
	 /**
     * [onPullDownRefresh 下拉页面不做处理]
     * @return {[type]} [description]
     */
    onPullDownRefresh() {
        wx.stopPullDownRefresh();
    },
    // //右上角分享功能
    // onShareAppMessage: function (res) {
    //     var that = this;
    //     return {
    //         title: 'Sports News',
    //         //右上角分享功能
    //         onShareAppMessage: function (res) {
    //             var that = this;
    //             return {
    //                 title: 'Sports News',
    //                 path: '/pages/article-page/article-page?id=' + that.data.option,
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
})