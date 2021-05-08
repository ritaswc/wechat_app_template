const newsdata = require('../../libraries/newsdata.js');
const dealUrl = require('../../libraries/dealUrl.js');
const app = getApp();
Page({
    data: {
    	content: {},//存放说明、分享链接等信息
    	subjects: {},//主体内容，这里将他分为几个部分
    	head: {},
        loading: true,
        hasMore: true,
        navTitle: [],
        toTitle: 'to',
        showGoTop: false,
        scrollTop: 0,
        winHeight: 0,
        localParams: {}
    },

    loadData(option) {
        // if(app.getNetworkType() == 'none') {
        //     this.setData({
        //         loading: false
        //     })
        //     return false;
        // }

        let params = {};
        let urlType = option.urlType;
        for(let index in option) {
            if(index != 'urlType') {
                params[index] = option[index];
            }
        }
        // delete params.urlType; //返回的是一个bool值
        this.setData({
            subtitle: '加载中...',
            loading: true
        })
        newsdata.find(urlType, params, null)
        .then(d => {
          console.log(d)
            let tempTitle = [];
            d.body.subjects.forEach((item, index) => {
                if(item.title) {
                    tempTitle.push(item.title);
                }
            });

            this.setData({
                content: d.body.content,
                subjects: d.body.subjects,
                head: d.body.head,
                navTitle: tempTitle,
                loading: false,
                hasMore: false
            });
        })
        .catch(e => {
            this.setData({
                subtitle: '获取数据异常',
                loading: false
            })
            console.error(e)
        })
    },
    navToPicture(event) {
        let str = dealUrl.getUrlTypeId(event);
            wx.navigateTo({
            url: '../picture-page/picture-page' + str,
            success: (res) => {},
            fail: (err) => {console.log(err)}
        });
    },
    navToArticle(event) {
        let str = dealUrl.getUrlTypeId(event);
        wx.navigateTo({
            url: '../article-page/article-page' + str,
            success: (res) => {},
            fail: (err) => {console.log(err)}
        });
    },
    navToVideo(event) {
        let str = event.currentTarget.dataset.id;
        wx.navigateTo({
            url: '../video-page/video-page?videoUrl=' + str,
            success: (res) => {},
            fail: (err) => {console.log(err)}
        });
    },
    navToDocLive(event) {
        let str = JSON.stringify(event.currentTarget.dataset.liveext);
        wx.navigateTo({
            url: '../doclive-page/doclive-page?option=' + str,
            success: (res) => {},
            fail: (err) => {
                console.log(err)
            }
        });
    },
    moveTo(event) {//导航标签快速定位
        console.log(event.currentTarget.dataset.id)
        this.setData({
            toTitle: event.currentTarget.dataset.id,
        });
    },
    refesh() {
        this.loadData(this.data.localParams);
    },
    toTop(event) {//点击返回顶部
        this.setData({
            scrollTop: 0,
        });
    },
    bindScroll(event) {//页面滚动时候触发
        if(event.detail.scrollTop > 300) {
            this.setData({
                showGoTop: true,
            });
        } else {
            this.setData({
                showGoTop: false,
            });            
        }
    },
    onLoad(params) {
        // let that = this; //获取设备信息
        // wx.getSystemInfo({
        //     success: function(res) {
        //         that.setData({
        //             winHeight: res.windowHeight
        //         });
        //     }
        // });
        console.log(params)
        this.setData({//存储数据留着给刷新用
            localParams: params,
        });
        this.loadData(this.data.localParams);
    },
    onPullDownRefresh() {
        this.loadData(this.data.localParams);
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
})