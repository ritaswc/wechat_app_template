'use strict';

// 获取全局应用程序实例对象
// const app = getApp()

// 创建页面实例对象
Page({
  /**
   * 页面的初始数据
   */
  data: {
    title: 'gratuity',
    currentStar: 4,
    chooseArr: [],
    waiter: {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '张三',
      grade: 'four-star',
      score: '4.8',
      title: ['服务周到', '负责任', '有礼貌'],
      comment: ['服务态度好', '分量足', '热情周到', '味道赞', '长得帅有食欲', '人很nice']
    }
  },
  /**
   * 星星打分
   * @param e
   */
  chooseStar: function chooseStar(e) {
    this.setData({
      currentStar: e.currentTarget.dataset.star
    });
  },

  /**
   * 改变标签选择
   * @param e
   */
  choosetip: function choosetip(e) {
    var index = e.currentTarget.dataset.choose;
    this.data.chooseArr[index] = !this.data.chooseArr[index];
    this.setData({
      chooseArr: this.data.chooseArr
    });
  },

  /**
   * 打赏发送
   */
  send: function send() {
    wx.showToast({
      title: '成功',
      icon: 'success',
      duration: 2000,
      success: function success() {
        wx.switchTab({
          url: '../index/index'
        });
      }
    });
  },

  /**
   * 初始化标签选择项
   */
  setChosseArr: function setChosseArr() {
    var comment = this.data.waiter.comment;
    var chooseArr = [];
    for (var i = 0; i < comment.length; i++) {
      chooseArr.push(false);
    }
    this.setData({
      chooseArr: chooseArr
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function onLoad() {
    // TODO: onLoad
    // 初始化chooseArr
    this.setChosseArr();
  },


  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function onReady() {
    // TODO: onReady
  },


  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function onShow() {
    // TODO: onShow
  },


  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function onHide() {
    // TODO: onHide
  },


  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function onUnload() {
    // TODO: onUnload
  },


  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function onPullDownRefresh() {
    // TODO: onPullDownRefresh
  }
});
//# sourceMappingURL=gratuity.js.map
