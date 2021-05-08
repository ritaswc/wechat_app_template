var http = require( '../../utils/util' )
var app = getApp()
var url = 'http://japi.juhe.cn/joke/content/text.from'

Page( {
  data: {
    page: 1,
    loadingHide: false,
    hideFooter: true,
    jokeList: [],
  },
  onLoad: function( options ) {
    // 页面初始化 options为页面跳转所带来的参数
    var that = this
    //请求笑话列表
    http.request( url, this.data.page, function( dataJson ) {
      that.setData( {
        jokeList: that.data.jokeList.concat( dataJson.result.data ),
        loadingHide: true
      })
    }, function( reason ) {
      console.log( reason )
      that.setData( {
        loadingHide: true
      })
    })

  },

  /**
   * 下拉刷新
   */
  onPullDownRefresh() {
    // var that = this
    // this.setData( {
    //   page: 1,
    //   jokeList:[]
    // })
    // http.request( url, this.data.page, function( dataJson ) {
    //   that.setData( {
    //     jokeList: that.data.jokeList.concat( dataJson.result.data )
    //   })
    //   wx.stopPullDownRefresh()
    // }, function( reason ) {
    //   console.log( reason )
    //   wx.stopPullDownRefresh()
    // })
  },

  /**
   * 滑动到底部加载更多
   */
  loadMore() {
    //请求笑话列表
    var that = this
    //显示footer
    this.setData( {
      hideFooter: !this.data.hideFooter
    })
    //请求笑话列表
    http.request( url, ++this.data.page, function( dataJson ) {
      that.setData( {
        jokeList: that.data.jokeList.concat( dataJson.result.data ),
        hideFooter: !that.data.hideFooter
      })

    }, function( reason ) {
      console.log( reason )
      that.setData( {
        hideFooter: !that.data.hideFooter
      })
    })


  },

})