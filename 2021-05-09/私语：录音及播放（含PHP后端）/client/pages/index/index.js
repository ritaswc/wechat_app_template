//index.js
//获取应用实例
var app = getApp()

var whisper = require('../../vendor/index');
var config = require('../../config');

//页面图片配置
var Src = require('../../src');

Page({
  data: {
    isRecording: false,
    recordUi: {
      record: Src.image.record,
      recording: Src.image.recording,
    },
    recAnimation: {},
    contentAnimation: {},
    isPlaying: false,
    playUi: {
      play: Src.image.play,
      playing: Src.image.playing,
    },
    recSrc: null,
    duration: 0,
    maxDuration: 60,
  },
  onLoad: function () {
    var that = this

    //获取用户数据
    whisper.login({
      success(result) {
        console.log('登录成功:', result);
      },
      fail(error) {
        console.log('登录失败:', error);
      }
    });
  },
  startRecord: function(){
    var $this = this
    $this.setData({
      isRecording: true,
    });
    whisper.startRecord({
      success(result) {
        $this.setData({
          isRecording: false,
        });
        console.log('录音成功:', result);
      },
      fail(error) {
        console.log('录音失败:', error);
      },
      process(){
        var rec = wx.createAnimation({
                        duration: 1000,
                        timingFunction: 'ease',
                    }).opacity(1).step().opacity(0).step();
        var content = wx.createAnimation({
                        duration: 1000,
                        timingFunction: 'ease',
                    }).opacity(0.4).step().opacity(1).step();

        $this.setData({
          duration: whisper.getRecordDuration(),
          recAnimation: rec.export(),
          contentAnimation: content.export(),
        });
      },
      compelete(){

      },
    });
  },
  stopRecord: function(){
    var $this = this;
    whisper.stopRecord({
      success(result) {
        $this.setData({
          isRecording: false,
        });
        console.log('停止录音:', result);
      },
      fail(error) {
        console.log('停止录音失败:', error);
      }
    });
  },
  play: function(){
    var $this = this
    $this.setData({
      isPlaying: true,
    });
    whisper.playRecord({
      src: whisper.getRecordSrc(),
      success() {
        $this.setData({
          isPlaying: false,
        });
        console.log('播放/暂停成功:', result);
      },
      fail(error) {
        $this.setData({
          isPlaying: false,
        });
        console.log('播放/暂停失败:', error);
      }
    });
  },
  whisper: function(event){
    //上传
    var $this = this;
    var tag = event.detail.value;
    var src = whisper.getRecordSrc();
    if (!src){
      wx.showToast({
        title: '没有听清',
        icon: 'loading',
        duration: 1000
      });
      return;
    }
    wx.showToast({
      title: '千里传声',
      icon: 'loading',
      duration: 60000
    });
    whisper.uploadFile({
      url: config.service.whisperUrl + '/session='+whisper.getSession().session+'&tag='+tag+'&duration='+$this.data.duration,
      filePath: src,
      name: 'whisper',
      success: function(res){
        wx.hideToast();
        setTimeout(function(){
          wx.showToast({
            title: '听见你的声音',
            icon: 'success',
            duration: 1000
          });
        }, 2000);
      },
      fail: function(res){
        wx.hideToast();
        setTimeout(function(){
          wx.showToast({
            title: '传声失败，请重试',
            icon: 'loading',
            duration: 1000
          });
        }, 2000);
      }
    });
  },
  onShareAppMessage: function () {
    //页面分享
    return {
      title: '私语',
      desc: '一分钟语音日记，你说，我听，岁月有痕迹。',
      path: '/pages/index/index'
    }
  }
})
