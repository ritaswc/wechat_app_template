//获取应用实例
var app = getApp();
var network_util = require('../../utils/network_util.js');
Page({
    data: {
        indicatorDots: true,
        vertical: false,
        autoplay: true,
        interval: 3000,
        duration: 1000,
        loadingHidden: false,  // loading
        pageIndex: 1,
        countPerPage: 5,
        goodsItems: [],
        bannerList: []
    },
    onLoad: function() {
        this.getBannerList();
        this.getGoodsList(this.data.pageIndex, this.data.countPerPage);
    },
    //获取轮播列表数据
    getBannerList: function () {
        var that = this;
        var url = app.globalData.apiUrl + 'mall_ad_carousel/list';
        //sliderList
        network_util._get(url,
            function(res){
                that.setData({
                    images: res.data.data.mallAdCarousels
                });
            },function(res){
                console.log(res);
            }
        );
    },
    //获取商品列表数据
    getGoodsList: function (pageIndex, countPerPage) {
        var that = this;
        var url = app.globalData.apiUrl + 'mall_commodity/recommendList?userId=8976&countPerPage=' + countPerPage + '&pageIndex=' + pageIndex;
        //goodsList
        network_util._get(url,
            function(res){
                if (res.data.returnValue === 1) {
                    if (pageIndex === 1) { //首次或者是下拉刷新
                        that.setData({
                            goodsItems: res.data.data.mallCommodities,
                            loadingHidden: true
                        })
                    } else {
                        that.setData({
                            goodsItems: that.data.goodsItems.concat(res.data.data.mallCommodities),
                            loadingHidden: true
                        })
                    }
                } else { //returnValue返回值不为1
                    setTimeout(function () {
                        that.setData({
                            loadingHidden: true
                        })
                    }, 1500)
                }
            }, function(res){
                console.log(res);
            }
        );
    },
    //事件处理函数
    swiperchange: function(e) {
        //console.log(e.detail.current)
    },

    onPullDownRefresh: function() {
        this.getBannerList();
        this.setData({pageIndex: 1})
        this.getGoodsList(this.data.pageIndex, this.data.countPerPage);
    },

    onReachBottom: function() {
        this.setData({pageIndex: this.data.pageIndex + 1})
        this.getGoodsList(this.data.pageIndex, this.data.countPerPage)
    }
})
