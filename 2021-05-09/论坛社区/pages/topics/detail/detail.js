// pages/topics/detail/detail.js
Page({
  data: {
    id: null,
    topic: null,
    accesstoken: null,
    showReplyView: false,
    reply: null
  },
  reply: function () {
    if (!this.data.reply) {
      wx.showToast({
        title: '请填写回复内容',
        icon: 'success',
        duration: 2000
      })
    } else {
      wx.showToast({
        title: '正在提交回复',
        icon: 'loading',
        duration: 2000
      })
      wx.request({
        url: 'https://nutz.cn/yvr/api/v1/topic/'+this.data.id+'/replies', 
        method:"POST",
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        data: {
          id: this.data.id ,
          content: this.data.reply,
          accesstoken:this.data.accesstoken
        },
        success: (res)=> {
          wx.hideToast();
          if(res.data.success){
            wx.redirectTo({
              url: './detail?id='+this.data.id
            })
          }else{
            wx.showToast({
              title: '没有权限,请@兽总',
              icon: 'loading',
              duration: 2000
            })
          }
        }
      })
    }
  },
  feedBackInput: function (e) {
    this.setData({
      "reply": e.detail.value
    });
  },
  showReplyView: function () {
    this.setData({
      "showReplyView": true
    });
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    this.setData(options);

    wx.getStorage({
      key: 'accesstoken',
      success: (res) => {
        this.setData({
          "accesstoken": res.data
        });
      }
    });
    wx.request({
      url: 'https://nutz.cn/yvr/api/v1/topic/' + this.data.id,
      data: {
        "mdrender": false
      },
      success: (res) => {
        this.setData({
          "topic": res.data.data
        });
        console.log(this.data.topic);
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
  }
})