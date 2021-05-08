const newsdata = require('../../libraries/newsdata.js');
const dealUrl = require('../../libraries/dealUrl.js');
const app = getApp();
Page({
    data: {
        swiper: {},
        special: {},
        news: {},
        loading: true,
        hasMore: true,
        subtitle: '',
        scrollTop: 0,
        showGoTop: false,
        showSearch: true,
        inputValue: '',
    },
    
    showLoading() {
        wx.showNavigationBarLoading();
        this.setData({
            subtitle: '加载中...',
            loading: true,
        });
    },
    hideLoading() {
        wx.hideNavigationBarLoading();
        wx.stopPullDownRefresh();
        this.setData({
            loading: false
        });
    },
    /**
     * [initLoad 初始化加载数据]
     * @return {[type]} [description]
     */
    initLoad() {
        this.showLoading();
        newsdata.find('ClientNews', {
                id: 'TY43,FOCUSTY43,TYTOPIC',
                page: 1
            })
            .then(d => {
              console.log(d)
                d.forEach((obj, index) => {
                    let validData = obj.item;
                    if (!validData)
                        return;
                    let typeData = obj.type;
                    if (typeData == 'focus') { //首页轮播图
                        this.setData({
                            swiper: obj,
                        });
                    } else if (typeData == 'secondnav') { //首页专题导航
                        this.setData({
                            special: obj,
                        });
                    } else if (typeData == 'list') { //首页新闻列表
                        this.setData({
                            news: obj,
                        });
                    }
                    this.hideLoading();
                })
            })
            .catch(e => {
                console.error(e)
                this.setData({
                    movies: [],
                })
                this.hideLoading();
            })
    },

    /**
     * [loadMore 加载更多数据]
     * @return {[type]} [description]
     */
    loadMore() {
      
        this.showLoading();
        let currentPage = this.data.news.currentPage;
        if (currentPage >= this.data.news.totalPage) {
            this.setData({
                hasMore: false,
            });
            return;
        }
        newsdata.find('ClientNews', {
                id: 'TY43',
                page: ++currentPage
            })
            .then(d => {
                let newnews = d[0];

                let olditem = this.data.news.item;
                newnews.item = olditem.concat(newnews.item);
                this.setData({
                    news: newnews,
                });
                this.hideLoading();
            })
            .catch(e => {
                this.setData({
                    subtitle: '获取数据异常',
                })
                console.error(e);
                this.hideLoading();
            })
           
    },
    navToSpecial(event) {
      console.log(event)
        let str = dealUrl.getUrlTypeId(event);
        wx.navigateTo({
            url: '../special-page/special-page' + str ,
            success: (res) => {},
            fail: (err) => {
                console.log(err)
            }
        });
    },
    navToPicture(event) {
        let str = dealUrl.getUrlTypeId(event);
        wx.navigateTo({
            url: '../picture-page/picture-page' + str,
            success: (res) => {},
            fail: (err) => {
                console.log(err)
            }
        });
    },
    navToArticle(event) {
        let str = dealUrl.getUrlTypeId(event);
        //console.log(str)
        wx.navigateTo({
            url: '../article-page/article-page' + str,
            success: (res) => {},
            fail: (err) => {
                console.log(err)
            }
        });
    },
    navToVideo(event) {
        let str = event.currentTarget.dataset.id;
        wx.navigateTo({
            url: '../video-page/video-page?videoUrl=' + str,
            success: (res) => {},
            fail: (err) => {
                console.log(err)
            }
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
    toTop() {
        console.log(111)
    },
    searchIcon() {
        // wx.navigateTo({ url: '../logs/logs' });
        this.setData({
            showSearch: false,
            inputValue: '',
        });    
    },
    bindKeyInput: function(event) {//获取输入的数据
        this.setData({
          inputValue: event.detail.value
        })
    },
    bindSearch() {//输入框点击完成事件
        let searchValue = this.data.inputValue;
        if(searchValue != '') {
            console.log(this.data.inputValue)
        }
        wx.showModal({
            title: '提示',
            content: `你输入的数据：${this.data.inputValue != '' ? this.data.inputValue : '是空的'} ,但是没用，我没做这个功能。`,
            success: () => {},
            fail: () => {}
        });
    },
    ensureBtn(event) {//确定按钮事件
        this.bindSearch();
    },
    scroll(event) {
        this.setData({
            showSearch: true,
        }); 
    },
    /**
     * [onLoad 载入页面时执行的生命周期初始函数]
     * @return {[type]} [description]
     */
    onLoad() {
        this.initLoad();
    },

    /**
     * [onPullDownRefresh 下拉刷新数据]
     * @return {[type]} [description]
     */
    onPullDownRefresh() {
        this.initLoad();
    },

    /**
     * [onReachBottom 上拉加载更多]
     * @return {[type]} [description]
     */
    onReachBottom() {
        this.loadMore();
    },

    //右上角分享功能
    onShareAppMessage: function (res) {
        var that = this;
        return {
            title: 'Sports News',
            //path: '/pa
            //右上角分享功能
            onShareAppMessage: function (res) {
                var that = this;
                return {
                    title: 'Sports News',
                    //path: '/pages/main-page/main-page?id=' + that.data.scratchId,
                    success: function (res) {
                        // 转发成功
                        wx.showToast({
                            title: '转发成功！',
                        })
                        that.shareClick();
                    },
                    fail: function (res) {
                        // 转发失败
                        wx.showToast({
                            icon: 'none',
                            title: '转发失败',
                        })
                    }
                }
            }
        }
    }

})