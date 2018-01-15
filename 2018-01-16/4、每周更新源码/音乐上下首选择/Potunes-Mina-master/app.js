//app.js
let bsurl = 'https://poche.fm/api/app/playlists'

App({
  onLaunch: function () {
    //调用API从本地缓存中获取数据
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    wx.setStorageSync('logs', logs)
  },
  getUserInfo:function(cb){
    var that = this
    if(this.globalData.userInfo){
      typeof cb == "function" && cb(this.globalData.userInfo)
    }else{
      //调用登录接口
      wx.login({
        success: function () {
          wx.getUserInfo({
            success: function (res) {
              that.globalData.userInfo = res.userInfo
              typeof cb == "function" && cb(that.globalData.userInfo)
            }
          })
        }
      })
    }
  },
  stopmusic: function (type, cb) {
    var that = this;
    wx.pauseBackgroundAudio();
    wx.getBackgroundAudioPlayerState({
      complete: function (res) {
        that.globalData.currentPosition = res.currentPosition ? res.currentPosition : 0
      }
    })
  },
  seekmusic: function (type, cb, seek) {
    var that = this;

    this.globalData.playtype = type;
    this.playing(type)
  },
  playing: function (type, cb, seek) {
    wx.showToast({
      title: '加载歌曲中',
      icon: 'loading',
      duration: 1500
    })
    var that = this
    var m = {}
    // 获取上次播放数据
    let index = wx.getStorageSync('curIndex')
    let tracks = wx.getStorageSync('tracks')
    if (tracks) {
      let track = tracks[index]
      m = track
    }
    // 使用通知
    wx.playBackgroundAudio({
      dataUrl: m.url,
      title: m.name,
      success: function (res) {
        if (seek != undefined) {
          wx.seekBackgroundAudio({ position: seek })
        }
        that.globalData.globalStop = false;
        that.globalData.playtype = type
        cb && cb();
      },
      fail: function () {
        if (type == 1) {
          that.nextplay(1)
        } else {
          that.nextfm();
        }
      }
    })
  },
  nextplay: function (t) {
    //播放列表中下一首
    this.preplay();
    var list = this.globalData.list_am;
    var index = this.globalData.index_am;
    if (t == 1) {
      index++;
    } else {
      index--;
    }
    index = index > list.length - 1 ? 0 : (index < 0 ? list.length - 1 : index)
    this.globalData.curplay = list[index] || {};
    this.globalData.index_am = index;
    this.seekmusic(1)
  },
  preplay: function () {
    //歌曲切换 停止当前音乐
    this.globalData.globalStop = true
    // wx.stopBackgroundAudio();
  },
  onShow: function () {
    this.globalData.hide = false
  },
  onHide: function () {
    this.globalData.hide = true
  },
  globalData: {
    hasLogin: false,
    hide: false,
    list_am: [],
    list_fm: [],
    list_sf: [],
    index_fm: 0,
    index_am: 0,
    playtype: 1,
    curplay: {},
    shuffle: 1,
    globalStop: true,
    currentPosition: 0,
    userInfo: null,
    tracks:[],
    index: 0
  }
})
