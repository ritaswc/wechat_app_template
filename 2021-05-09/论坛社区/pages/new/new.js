// pages/new/new.js
Page({
  data: {
    title: null,
    content: null,
    accesstoken: null
  },
  feedBackTitle: function (e) {
    this.setData({
      "title": e.detail.value
    })
  },
  feedBackInput: function (e) {
    this.setData({
      "content": e.detail.value
    })
  },
  submit: function () {
    if (!this.data.title) {
      wx.showToast({
        title: '请填写标题',
        icon: 'success',
        duration: 2000
      })
    } else if(!this.data.content){
      wx.showToast({
        title: '请填写内容',
        icon: 'success',
        duration: 2000
      })
    }else {
      wx.showToast({
        title: '正在提交回复',
        icon: 'loading',
        duration: 2000
      })
      wx.request({
        url: 'https://nutz.cn/yvr/api/v1/topics',
        method: "POST",
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        data: {
          title: this.data.title,
          content: this.data.content,
          accesstoken: this.data.accesstoken
        },
        success: (res) => {
          wx.hideToast();
          console.log(res)
          if (res.data.success) {
            wx.navigateTo({
              url: '../topics/detail/detail?id=' + res.data.topic_id
            })
          } else {
            wx.showToast({
              title: res.data.message || '没有权限,请@兽总',
              icon: 'loading',
              duration: 2000
            })
          }
        }
      })
    }
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    wx.getStorage({
      key: 'accesstoken',
      success: (res) => {
        this.setData({
          "accesstoken": res.data
        });
      }
    });
  },
  onReady: function () {
    // 页面渲染完成
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
  feedBackContent: function (e) {

  }
})