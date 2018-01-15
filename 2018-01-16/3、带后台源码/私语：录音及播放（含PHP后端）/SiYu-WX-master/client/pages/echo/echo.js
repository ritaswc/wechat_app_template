// pages/echo/echo.js
//获取应用实例
var app = getApp()

var whisper = require('../../vendor/index');
var config = require('../../config');

//页面图片配置
var Src = require('../../src');

//语音缓存map
var tempFile = new Map();

Page({
  data:{
    userInfo: null,
    loadingAnimation: {},
    nomoreAnimation: {},
    nomore: false,
    isPlaying: false,
    playingSrc: null, //调用play接口 compelete设置
    playUi: {
      play: Src.image.play,
      playing: Src.image.playing
    },
    key: '',
    searchMode: false,
    recordsNum: 0,
    records: [],
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数

    var $this = this
    if (!whisper.getSession()){
      $this.getRecords($this.data.key, true, true);
    }
    $this.setData({
      userInfo: whisper.getSession().uinfo,
    });
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
    var $this = this
    $this.getRecords($this.data.key, false, true);
  },
  onShareAppMessage: function () {
    //页面分享
    return {
      title: '私语',
      desc: '一分钟语音日记，你说，我听，岁月有痕迹。',
      path: '/pages/index/index'
    }
  },
  refreshList: function(){
    //滑动触顶
    console.log("触顶")
    var $this = this;
    $this.setData({
      key: '',
      searchMode: false,
    });
    $this.getRecords($this.data.key, false, true);
  },
  appendList: function(){
    //滑动触底
    console.log("触底")
    var $this = this;
    if (!$this.data.nomore) {
      $this.getRecords($this.data.key, false, false);
    }
  },
  showActionSheet: function(event){
    var $this = this;
    var id = event.target.dataset.id;
    if (!id){
      console.log('传参失败');
      return;
    }
    wx.showActionSheet({
      itemList: ['删除'],
      success: function(res) {
        if (res.tapIndex == 0){
          whisper.request({
            url: config.service.deleteUrl,
            method: 'POST',
            data: {
              session: whisper.getSession().session,
              id: id,
            },
            success(result) {
              for(var i = 0; i < $this.data.records.length; ++i){
                if ($this.data.records[i].id == id){
                  $this.data.records.splice(i, 1);
                  break;
                }
              }
              $this.setData({
                records: $this.data.records,
              });
              wx.showToast({
                title: '删除成功',
                icon: 'success',
                duration: 1000
              });
              console.log('删除成功:', result);
            },
            fail(error) {
              wx.showToast({
                title: '删除失败',
                icon: 'loading',
                duration: 1000
              });
              console.log('删除失败:', error);
            }
          });
        }
      },
      fail: function(res) {
        console.log(res)
      }
    })
  },
  search: function(event){
    // 搜索tag
    var $this = this;
    $this.setData({
      key: event.detail.value,
      searchMode: true,
    });
    $this.getRecords($this.data.key, false, true);
  },
  play: function(event){
    //播放/暂停录音
    var $this = this;
    var path = event.target.dataset.src;  //点击传递参数 偶尔empty
    var url = 'https://' + config.service.host + path;  //语音文件地址
    var src = tempFile.get(path); //资源地址
    if (!path){
      //过滤空参
      return;
    }
    if (src){
      $this.playRecord(path, src);
    }else{
      wx.downloadFile({
        url: url,
        success: function(res) {
          tempFile.set(path, res.tempFilePath);
          $this.playRecord(path, res.tempFilePath);
        },
        fail: function(){
          wx.showToast({
            title: '载入失败',
            icon: 'loading',
            duration: 1000
          })
        },
      });
    }
  },

  //请求页面数据
  getRecords: function(key, requireLogin, isFresh){
    var $this = this
    if (isFresh) {
      var showAnimation = wx.createAnimation({
                        duration: 1000,
                        timingFunction: 'ease',
                    }).height(50).opacity(1).step();
      var hideAnimation = wx.createAnimation({
                        duration: 1000,
                        timingFunction: 'ease',
                    }).opacity(0).height(0).step();
      $this.setData({
        recordsNum: 0,
        records: [],
        loadingAnimation: showAnimation.export(),
        nomoreAnimation: hideAnimation.export(),
        nomore: false,
      });
    }
    var url;
    if ($this.data.searchMode){
      url = config.service.searchUrl;
    }else{
      url = config.service.viewUrl;
    }
    whisper.request({
      requireLogin: requireLogin,
      url: url,
      method: 'POST',
      data: {
        session: whisper.getSession().session,
        index: $this.data.recordsNum,
        number: 10,
        key: key,
      },
      success(result) {
        var nums = result.data.number;
        var records = result.data.data;
        var nomoreDisplay;
        if (!nums){
          var showAnimation = wx.createAnimation({
                            duration: 1000,
                            timingFunction: 'ease',
                        }).height(50).opacity(1).step();
          $this.setData({
            nomore: true,
            nomoreAnimation: showAnimation.export(),
          });
        }
        $this.setData({
          recordsNum: $this.data.recordsNum + nums,
          records: $this.data.records.concat(records),
        });
        if (isFresh){
          setTimeout(function(){
            var hideAnimation = wx.createAnimation({
                              duration: 1000,
                              timingFunction: 'ease',
                          }).opacity(0).height(0).step();
            $this.setData({
              loadingAnimation: hideAnimation.export(),
            });
          }, 1000);
        }
        console.log('载入成功:', result);
      },
      fail(error) {
        console.log('载入失败:', error);
      }
    });
  },

  //播放语音
  playRecord: function(path, src){
    var $this = this;
    $this.setData({
      isPlaying: true,
      playingSrc: path,
    });
    whisper.playRecord({
      src: src,
      success() {
        $this.setData({
          isPlaying: false,
          playingSrc: null,
        });
        console.log('播放/暂停成功:', result);
      },
      fail(error) {
        $this.setData({
          isPlaying: false,
          playingSrc: null,
        });
        console.log('播放/暂停失败:', error);
      }
    });
  }

})