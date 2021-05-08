let bsurl = 'https://poche.fm/api/app/playlists?id=1.0.1&v=mina'

var common = require('../../../utils/util.js');

let seek = 0
let defaultdata = {
  winWidth: 0,
  winHeight: 0,
  listHeight: 0,
  // tab切换
  currentTab: 0,
  // 播放列表
  playlists: [],
  tracks: [],
  coverImgUrl: "http://lastshrek.b0.upaiyun.com/icon.jpg",
  nowPlayingTitle:"请选择歌曲",
  nowPlayingArtist: "",
  playing:false,
  playtime: '00:00',
  duration: '00:00',
  percent: 1,
  lrc: [],
  lrcindex: 0,
  showlrc: false,
  disable: false,
  downloadPercent: 0,
  curIndex: 0,
  initial: true,
  shuffle: 1,
  music: {},
  animationData: {},
  pop: false,
  scroll: false,
  currentTab: 0
}
//获取应用实例
let app = getApp()
Page({
  data: defaultdata,
  onLoad: function(options) {
    var that = this
    if (options.currentTab) {
      that.setData({
        currentTab: options.currentTab
      })
    }
    wx.request({
      url: bsurl,
      success: function (res) {
        var playlists = []
        if (res.data.length != 3) {
          var playlist = {
            id:0,
            cover:'https://s.poche.fm/nowlistening/cover.png',
            title:'破车最近在听的歌',
          }
          playlists.push(playlist)
        }
        res.data.forEach(function(playlist) {
          playlists.push(playlist)
        })
        playlists.push(res.data)
        that.setData({
          listHeight: res.data.length * 230,
          playlists: playlists,
          loadingHide:true
        })
      }
    })
    //获取系统信息
    wx.getSystemInfo( {
      success: function( res ) {
        that.setData( {
          winWidth: res.windowWidth,
          winHeight: res.windowHeight
        })
      }
    })
    // 获取上次播放数据
    let index = wx.getStorageSync('curIndex')
    let tracks = wx.getStorageSync('tracks')
    if (tracks) {
      let track = tracks[index]
      that.setData( {
        curIndex: index,
        tracks: tracks,
        coverImgUrl:track.cover,
        nowPlayingArtist: track.artist,
        nowPlayingTitle: track.name,
        music: track
      })
    }

    //监听停止,自动下一首
    wx.onBackgroundAudioStop(function(){
      that.playnext();
    })
  },
  bindChange: function(e) {
    var that = this
    that.setData( { currentTab: e.detail.current })
  },
  swichNav: function(e) {
    var that = this;
    if( this.data.currentTab === e.target.dataset.current ) {
      return false;
    } else {
      that.setData( {
        currentTab: e.target.dataset.current
      })
    }
  },
  // 跳转下一页
  tracks: function(event) {
    var index = event.currentTarget.id
    var playlist = this.data.playlists[index]
    var p = playlist.id
    var title = playlist.title
    wx.navigateTo({
        url: '../tracks/index?id=' + p + '&title=' + title
    })
  },
  // 接收点击数据
  changeData: function(tracks, index, tab) {
    var curMusic = tracks[index]
    this.setData({
      curIndex: index,
      tracks: tracks,
      coverImgUrl:curMusic.cover,
      nowPlayingArtist: curMusic.artist,
      nowPlayingTitle: curMusic.name,
      playing: true,
      music: curMusic,
      lrc: [],
      lrcindex: 0,
      currentTab: tab
    })
    app.globalData.curplay.id = curMusic.id
    //存储当前播放
    wx.setStorageSync("curIndex", index)
    wx.setStorageSync("tracks", tracks)
    app.seekmusic(1)
    if (this.data.showlrc) {
      common.loadlrc(this)
    }
  },
  //播放方法
  playingtoggle:function() {
    var that = this
    if (this.data.initial) {
      // this.play(this.data.tracks, this.data.curIndex)
      this.setData({
        initial: false
      })
      this.changeData(this.data.tracks, this.data.curIndex)
      wx.showToast({
        title: '开始播放',
        icon: 'success',
        duration: 2000
      })
      return
    }
    if (this.data.playing) {
      that.setData({
        playing: false
      })
      app.stopmusic(1)
      wx.showToast({
        title: '暂停播放',
        icon: 'success',
        duration: 2000
      })
    } else {
      app.seekmusic(1, function () {
        wx.showToast({
          title: '继续播放',
          icon: 'success',
          duration: 2000
        })
        that.setData({
          playing: true
        })
      }, app.globalData.currentPosition)
    }
  },
  playnext: function (e) {
    if (this.data.initial) {
      this.setData({
        initial: false
      })
    }
    let shuffle = this.data.shuffle
    let count = this.data.tracks.length
    let lastIndex = parseInt(this.data.curIndex)

    if (shuffle == 3) {
      //随机播放
      lastIndex = Math.floor(Math.random() * count)
    } else if (shuffle == 1) {
      if (lastIndex == count - 1) {
        lastIndex = 0
      } else {
        lastIndex = lastIndex + 1
      }
    }
    this.changeData(this.data.tracks, lastIndex)
  },
  playprev: function (e) {
    if (this.data.initial) {
      this.setData({
        initial: false
      })
    }
    let shuffle = this.data.shuffle
    let lastIndex = parseInt(this.data.curIndex)
    let count = this.data.tracks.length
    if (shuffle == 3) {
      //随机播放
      lastIndex = Math.floor(Math.random() * count)
    } else if (shuffle == 1) {
      if (lastIndex == 0) {
        lastIndex = count - 1
      } else {
        lastIndex = lastIndex - 1
      }
    }
    this.changeData(this.data.tracks, lastIndex)
  },
  playshuffle: function() {
    if (this.data.shuffle == 1) {
      this.setData({
        shuffle: 2
      })
      return
    }
    if (this.data.shuffle == 2) {
      this.setData({
        shuffle: 3
      })
      return
    }
    if (this.data.shuffle == 3) {
      this.setData({
        shuffle: 1
      })
    }
  },
  musicinfo: function() {
    let pop = this.data.pop
    var animation = wx.createAnimation({
      duration: 100,
    })
    this.animation = animation
    this.setData({
      animationData:animation.export()
    })
    if (!pop) {
      // 创建动画
      this.animation.translate(0, -this.data.winHeight + 31).step()

    } else {
      this.animation.translate(0, this.data.winHeight - 81).step()
    }
    this.setData({
      animationData: this.animation.export(),
      pop: !pop
    })
  },
  // 点击播放列表
  itemClick: function(event) {
    var p = event.currentTarget.id
    this.changeData(this.data.tracks, p)
    this.musicinfo()
  },
  // 加载歌词
  loadlrc: function(event) {
    if (this.data.showlrc == false) {
      this.setData({
        showlrc: true
      })
      common.loadlrc(this);
    } else {
      this.setData({
        showlrc: false
      })
    }
  },
  onShow: function (options) {
    var that = this
    app.globalData.playtype = 1;
    common.playAlrc(that, app);
    seek = setInterval(function () {
      common.playAlrc(that, app);
    }, 1000)
  },
  onUnload: function () {
    clearInterval(seek)
  },
  onHide: function () {
    clearInterval(seek)
  },
})
