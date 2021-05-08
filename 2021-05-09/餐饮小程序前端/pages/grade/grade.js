'use strict';

// 获取全局应用程序实例对象
// const app = getApp()

// 创建页面实例对象
Page({
  /**
   * 页面的初始数据
   */
  data: {
    title: 'grade',
    text_ph: '菜品口味如何，服务如何？环境如何？',
    upPhotoList: [],
    currentStar: 4,
    checkStatus: false
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
   * 用户上传图片
   */
  upPhoto: function upPhoto() {
    var that = this;
    if (that.data.upPhotoList.length >= 6) {
      return wx.showToast({
        title: '最多6张图片',
        icon: 'success',
        duration: 2000
      });
    }
    wx.chooseImage({
      count: 1, // 默认9
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function success(res) {
        // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
        var tempFilePaths = res.tempFilePaths;
        var temp = tempFilePaths[0];
        that.data.upPhotoList.push(temp);
        that.setData({
          upPhotoList: that.data.upPhotoList
        });
      }
    });
  },

  /**
   * 图片展示
   */
  showImage: function showImage(e) {
    var that = this;
    wx.previewImage({
      current: e.currentTarget.dataset.imgsrc, // 当前显示图片的http链接
      urls: that.data.upPhotoList // 需要预览的图片http链接列表
    });
  },

  /**
   * 删除当前图片
   * @param e
   */
  delphoto: function delphoto(e) {
    var that = this;
    that.data.upPhotoList.splice(that.data.upPhotoList.indexOf(e.currentTarget.dataset.delsrc), 1);
    this.setData({
      upPhotoList: that.data.upPhotoList
    });
  },

  /**
   * 设置消费金额
   * @param e
   */
  setMoney: function setMoney(e) {
    this.setData({
      useMoney: e.detail.value
    });
  },

  /**
   * 提交评论信息
   */
  gradeBtn: function gradeBtn() {
    wx.switchTab({
      url: '../index/index'
    });
  },

  /**
   * checkbox 选项
   * @param e
   */
  changbox: function changbox() {
    this.setData({
      checkStatus: !this.data.checkStatus
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function onLoad() {
    // TODO: onLoad
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
//# sourceMappingURL=grade.js.map
