// var event = require('../event.js')

var xcxPage = require('../xcx.js');
var mixin1 = require('./mixin1.js');
var mixin2 = require('./mixin2.js');
// var getAllPages = require('../../utils/getAllPages.js');

//index.js
//获取应用实例
var app = getApp();

Page(xcxPage({
  mixins: [mixin1, mixin2],
  methods: {
    fn(data) {
      console.log('index page fn', data)
    },

    // 测试invoke方法
    testInvoke(arg1, arg2) {
      this.setData({
        motto: '测试invoke方法',
      })
      console.log('testInvoke', arg1, arg2);
    },
    /*
        fn1() {
          console.log('index page fn1')
        },
        fn2() {
          console.log('index page fn2')
        },
        fn3() {
          console.log('index page fn3')
        },*/

  },
  data: {
    motto: 'Hello World',
    userInfo: {}
  },
  events() {
    return {
      'index-emit' (res, red) {
        this.setData({
          motto: res
        });
        console.log(this.data)
      },
      /*'index-emit2'(res, red) {
        console.log(res, red);
      }*/
    }
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs?log=2'
    })
  },
  onLoad: function() {
    console.log('index page onload')
    this.fn(23);
    this.fn2();

    var that = this
      //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo) {
      //更新数据
      that.setData({
        userInfo: userInfo
      })
    })
  },
  onShow() {
    console.log('index onShow', this.data)
  },
  onReady() {
    console.log('index onReady')
  },
  onHide() {
    console.log('index page hide');
  }
}));