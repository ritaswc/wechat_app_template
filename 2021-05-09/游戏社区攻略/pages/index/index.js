
/**
 * index.js 
 * app.json中pages数组的第一项代表小程序的初始页
 * Page() 函数用来注册一个页面。接受一个 object 参数，其指定页面的初始数据、生命周期函数、事件处理函数等。
 * @description 游戏攻略社区首页
 * @version 1.0.0
 */

var app = getApp()    //获取应用实例

Page({

  // 页面的初始数据
  data: {
    gameList: [{
      id: '1',
      name: '式神搜索',
      icon: '../../static/images/tips1.png',
      linkUrl: '../onmyouji/hellspawnSearch/hellspawnSearch',
      desc: '查找阴阳师中式神位置'
    }, {
      id: '2',
      name: '式神属性',
      icon: '../../static/images/tips2.png',
      linkUrl: '',
      desc: '查看式神攻击等信息(TODO)'
    }, {
      id: '3',
      name: '御魂属性',
      icon: '../../static/images/tips3.png',
      linkUrl: '',
      desc: '查看对比御魂属性(TODO)'
    }, {
      id: '4',
      name: '标题待定(TODO)',
      icon: '../../static/images/tips4.png',
      linkUrl: '',
      desc: '描述待定(TODO)'
    }],
    userInfo: {}
  },

  //================================ 生命周期函数 START ============================//
  // 监听页面加载, 一个页面只会调用一次。
  onLoad: function () {
    console.log('onLoad', '监听页面加载')
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function (userInfo) {
      //更新数据
      that.setData({
        userInfo: userInfo
      })
      console.log(userInfo);
    })
  },

  // 监听页面初次渲染完成, 一个页面只会调用一次，代表页面已经准备妥当，可以和视图层进行交互。
  onReady: function () {
    console.log('onReady', '页面初次渲染完成');
  },

  // 监听页面显示
  onShow: function () {
    console.log('onShow', '页面显示');
  },

  // 监听页面卸载
  onUnload: function () {
    console.log('onUnload', '页面卸载');
  },

  // 监听页面隐藏
  onHide: function () {
    console.log('onHide', '页面隐藏');
  },

  //================================ 生命周期函数 END ===============================//

  // 事件处理函数
  bindViewTap: function () {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },

  // 用户分享
  onShareAppMessage: function () {
    return {
      title: '游戏攻略社区',
      path: '/pages/index/index'
    }
  }



})
