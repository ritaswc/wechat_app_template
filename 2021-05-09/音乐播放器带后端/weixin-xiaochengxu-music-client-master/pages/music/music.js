// pages/music/music-list.js
Page({
  data: {
    musicList: [],
    currentMusic: { song: '', artist: '', url: '' }

  },

  onLoad: function (options) {
    var that = this
    // 页面初始化 options为页面跳转所带来的参数
    wx.request({
      url: 'http://localhost:8080/xiaochengxu-music/music-list',
      data: {},
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        console.log('音乐列表获取成功: ')
        var musicList = res.data.musicList
        var curerntMusic = musicList[0]
        that.setData({ musicList: musicList })
        that.setData({ currentMusic: curerntMusic })
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })


  },

  onReady: function (e) {
    this.audioCtx = wx.createAudioContext('myAudio')
  },

  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  },

  // 播放音乐
  playMusic: function (event) {
    var musicId = event.target.dataset.musicId
    var clickedMusic = this.data.musicList[musicId]
    this.setData({ currentMusic: clickedMusic })
    // 
    // 虽然在wxml中设置<audio>控件src属性可动态变化
    // 此处需要设置audioCtx.setSrc()，否则<button>需要按两下才会播放新的音乐
    // 为什么会这样？
    this.audioCtx.setSrc(clickedMusic.url)
    this.audioCtx.play()
  },



})