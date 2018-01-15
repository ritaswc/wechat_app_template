//index.js
const index = require('../../controllers/indexController.js').controller

//获取应用实例
var app = getApp()

Page({
  data: {
    topScroll:[],
    footerNav:[],
    bannerIcon:[],
    indicatorDots: true,
    autoplay: true,
    interval: 3000,
    duration: 1000,
    pageIndex:1,
    pageSize:10,
    loading: true,
    loadtitle:"康康正在努力为你加载更多...",
    hasMore:true,
    guessProducts:[]
  },  
  loadMoreGuessLike:function(){
      // if (!this.data.hasMore) return
      // this.setData({ loadtitle: '康康正在努力为你加载更多...', loading: true })
      // index.find(this.data.page++, this.data.size).then(d => {
      //   if (d.PageIndex<TotalPage) {
      //     this.setData({ loadtitle:'康康正在努力为你加载更多...',guessProducts: d.Data, loading: false })
      //   } else {
      //     this.setData({ loadtitle: '', hasMore: false, loading: false })
      //   }
      // })
      // .catch(e => {
      //   this.setData({ loadtitle: '获取数据异常', loading: false })
      //   console.error(e)
      // })
  },
   onLoad: function () {
    console.log('onLoad')
    var _this = this
    index.getBannerIcon().then(d=>_this.setData({ bannerIcon : d }))
    index.getFooter().then(d=>_this.setData({ footerNav : d }))
    index.getTopScroll().then(d=>_this.setData({ topScroll : d }))
    _this.loadMoreGuessLike()
  },
  onReady () {
      var _this = this
      index.getFooter2().then(d=>_this.setData({ footerNav : d }))
      // 页面渲染完成
      wx.setNavigationBarTitle( {
          title: "首页-康爱多微商城"
      })
  },
  onShow () {
      wx.setNavigationBarTitle( {
          title: "首页-康爱多微商城"
      })
  },
})